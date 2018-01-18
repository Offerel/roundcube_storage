<?php

error_reporting(0); // Set E_ALL for debuging
// This part is needed to use the Roundcube Session for authentication. Don't remove this !!!
// ------------------------------------------------------------------------------------------
define('INSTALL_PATH', realpath(__DIR__ . '/../../../../') . '/');
include INSTALL_PATH . 'program/include/iniset.php';
$rcmail = rcmail::get_instance();

if (!empty($rcmail->user->ID)) {
	$path = $rcmail->config->get('storage_basepath', false).$rcmail->user->get_username().'/files';
	$storage_name = $rcmail->config->get('storage_name', false);
	
	// check if attachment path exists and create if not exist
	$attpath = $path.'/'.$rcmail->config->get('storage_attachments', false);
	
	if (!is_dir($attpath))
	{
		mkdir($attpath);         
	}
}
else {
	die('Not logged in.');
}
// Roundcube authentication finished. You can use now the $path variable as path for elFinder.
// ------------------------------------------------------------------------------------------


// elFinder autoload
require './autoload.php';

function access($attr, $path, $data, $volume, $isDir, $relpath) {
	$basename = basename($path);
	return $basename[0] === '.'                  // if file/folder begins with '.' (dot)
			 && strlen($relpath) !== 1           // but with out volume root
		? !($attr == 'read' || $attr == 'write') // set read+write to false, other (locked+hidden) set to true
		:  null;                                 // else elFinder decide it itself
}

// Documentation for connector options:
// https://github.com/Studio-42/elFinder/wiki/Connector-configuration-options
$opts = array(
	//'debug' => true,
	'roots' => array(
		// Items volume
		array(
			'driver'        => 'LocalFileSystem',           // driver for accessing file system (REQUIRED)
			'path'          => $path,                 // path to files (REQUIRED)
			'uploadAllow'   => array('all'),// Mimetype `image` and `text/plain` allowed to upload
			'alias'	=> $storage_name,
			'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
			'accessControl' => 'access',                     // disable and hide dot starting files (OPTIONAL)
			'tmbPath' => '/tmp',
			'quarantine' => '/tmp'
		)
	)
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();


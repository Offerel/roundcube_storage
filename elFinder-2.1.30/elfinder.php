<?php
define('INSTALL_PATH', realpath(__DIR__ . '/../../../') . '/');
require_once INSTALL_PATH . 'program/include/iniset.php';
$rcmail = rcmail::get_instance();

if (!empty($rcmail->user->ID)) {
	include('elfinder.html');
}
else {
	header('location: ../../../');
}
die();
//session_start();
//require_once(str_replace('/public_html/plugins/storage','/vendor/autoload.php',dirname(dirname($_SERVER['SCRIPT_FILENAME']))));
/*
$include_path = '/var/www/roundcubemail-1.3.3/program/lib/';                                                                                                                                                     
$include_path.= ini_get('include_path');
set_include_path($include_path);                                                                                                                                                                     
require_once '/var/www/roundcubemail-1.3.3/program/include/iniset.php';

	function test()  {
		$rcmail = rcmail::get_instance();
		
		if (!empty($rcmail->user->ID)) {
		// user is authenticated, allow access
			$username = $rcmail->user->get_username();
			die('bin drin');
			//include('elfinder.html');
		} else {
			die('hau ab');
		}
	}
*/
include(dirname(dirname($_SERVER['SCRIPT_FILENAME'])).DIRECTORY_SEPARATOR."config.inc.php");
$passwdFile = $config['htpasswd'];

function crypt_apr1_md5($plainpasswd, $salt)
{
    $tmp = "";
    $len = strlen($plainpasswd);
    $text = $plainpasswd.'$apr1$'.$salt;
    $bin = pack("H32", md5($plainpasswd.$salt.$plainpasswd));
    for($i = $len; $i > 0; $i -= 16) { $text .= substr($bin, 0, min(16, $i)); }
    for($i = $len; $i > 0; $i >>= 1) { $text .= ($i & 1) ? chr(0) : $plainpasswd{0}; }
    $bin = pack("H32", md5($text));
    for($i = 0; $i < 1000; $i++)
    {
        $new = ($i & 1) ? $plainpasswd : $bin;
        if ($i % 3) $new .= $salt;
        if ($i % 7) $new .= $plainpasswd;
        $new .= ($i & 1) ? $bin : $plainpasswd;
        $bin = pack("H32", md5($new));
    }
    for ($i = 0; $i < 5; $i++)
    {
        $k = $i + 6;
        $j = $i + 12;
        if ($j == 16) $j = 5;
        $tmp = $bin[$i].$bin[$k].$bin[$j].$tmp;
    }
    $tmp = chr(0).chr(0).$bin[11].$tmp;
    $tmp = strtr(strrev(substr(base64_encode($tmp), 2)),
    "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/",
    "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz");
 
    return "$"."apr1"."$".$salt."$".$tmp;
}

function get_htpasswd($passwdFile, $username)
{
    $lines = file($passwdFile);
    foreach ($lines as $line)
    {
        $arr = explode(":", $line);
        $fileUsername = $arr[0];
        if ($fileUsername == $username)
        {
            $filePasswd = trim($arr[1]);
            return $filePasswd;
        }
    }
    return false;
}

function matches($password, $filePasswd)
{
    if (strpos($filePasswd, '$apr1') === 0)
    {
        // MD5
        $passParts = explode('$', $filePasswd);
        $salt = $passParts[2];
        $hashed = crypt_apr1_md5($password, $salt);
        return $hashed == $filePasswd;
    }
    elseif (strpos($filePasswd, '{SHA}') === 0)
    {
        // SHA1
        $hashed = "{SHA}" . base64_encode(sha1($password, TRUE));
        return $hashed == $filePasswd;
    }
    elseif (strpos($filePasswd, '$2y$') === 0)
    {
       // Bcrypt
       return password_verify ($password, $filePasswd);
    }
    else
    {
        // Crypt
        $salt = substr($filePasswd, 0, 2);
        $hashed = crypt($password, $salt);
        return $hashed == $filePasswd;
    }
    return false;
}

function dform() 
{
	$lformular = "<!DOCTYPE html>
	<html>
	<body>
	<form id='lform' action='elfinder.php' method='post'>
	<input type='text' id='username' name='username' value=''>
	<input type='text' id='password' name='password' value=''>
	<input id='send'  type='submit' name='send' value='Login'>
	</form>
	</body>
	</html>";
	return $lformular;
}

function logon($user, $password) {
	global $passwdFile;
	$filePasswd = get_htpasswd($passwdFile, $user);
	
	if ( matches($password, $filePasswd) )
	{
		$_SESSION['authorized'] = true;
		$_SESSION['uf'] = $_POST['username'];
		header('Location: elfinder.php');
		exit();
	}
	else
	{
		die("Incorrect username or password\n");
	}
}

if(isset($_POST['send'])) {
	logon($_POST['username'], $_POST['password']);
}

if(isset($_GET['logout']))
{
	session_destroy();
	$path = dirname($_SERVER['PHP_SELF']);
	header('Location: '.substr($path, 0, strpos($path, 'plugins')));
	exit();
}

if(!isset($_SESSION['authorized']))
{
	echo dform();
	//die("Loginform");
} 
else 
{
	include('elfinder.html');
	//die("bin drin");
}
 ?>

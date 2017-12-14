<?php
/**
 * Roundcube elfinder Plugin
 * Integrate elFinder in to Roundcube
 *
 * @version 1.0.1
 * @author Offerel
 * @copyright Copyright (c) 2017, Offerel
 * @license GNU General Public License, version 3
 */
class storage extends rcube_plugin
{
	public $task = '.*';

	function init()
	{
		$rcmail = rcmail::get_instance();
		$this->load_config();

		$this->add_texts('localization/', true);

		$this->include_stylesheet($this->local_skin_path() . '/elfinder.css');

		$this->register_task('storage');

		$this->add_button(array(
			'label'      => 'storage.storage',
			'command'    => 'storage',
			'class'      => 'button-storage',
			'classsel'   => 'button-storage button-selected',
			'innerclass' => 'button-inner'
		), 'taskbar');

		if ($rcmail->task == 'storage') {
			$this->register_action('index', array($this, 'action'));
		}
	}

	function action()
	{
		$rcmail = rcmail::get_instance();
		$rcmail->output->add_handlers(array('storagecontent' => array($this, 'content'),));
		$rcmail->output->set_pagetitle($this->gettext('storage'));
		$rcmail->output->send('storage.storage');
	}

	function content($attrib)
	{
		$rcmail = rcmail::get_instance();
		$src = $rcmail->config->get('storage_url', false);

		$attrib['src'] = $src;
		if (empty($attrib['id']))
			$attrib['id'] = 'rcmailstoragecontent';
		$attrib['name'] = $attrib['id'];
		return $rcmail->output->frame($attrib);
	}
}
?>

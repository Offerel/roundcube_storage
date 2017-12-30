<?php
/**
 * Roundcube elfinder Plugin
 * Integrate elFinder in to Roundcube
 *
 * @version 1.1.0
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

		$this->include_script('client.js');

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
		
		$this->add_hook('template_container',                 array($this, 'add_saveatt_link'));

		$this->register_action('save_one', array($this, 'save_one'));
	}
	
	public function add_saveatt_link($p)
    {
        if ($p['name'] == 'attachmentmenu') {
            $link = $this->api->output->button(array(
                'type' => 'link',
                'id'   => 'attachmentmenusave',
                'command'  => 'plugin.storage.save_one',
                'class' => 'savelink icon active',
                'content'  => html::tag('span', array('class'=>'icon saveatt'),
                    rcube::Q($this->gettext('saveattachment')))
            ));
            $p['content'] .= html::tag('li', array('role'=>'menuitem'), $link);
        }
        return $p;
    }

	public function save_one($args)
	{
		$rcmail = rcmail::get_instance();
		
		$path = $rcmail->config->get('storage_basepath', false).$rcmail->user->get_username().'/files';	
		$attpath = $path.'/'.$rcmail->config->get('storage_attachments', false);		
		if (!is_dir($attpath))
		{
			mkdir($attpath);         
		}
		
		$uid = rcube_utils::get_input_value('_uid', rcube_utils::INPUT_POST);
		$mbox    = rcube_utils::get_input_value('_mbox', rcube_utils::INPUT_POST);
		$mime_id = rcube_utils::get_input_value('_part', rcube_utils::INPUT_POST);
		
		$message = new rcube_message($uid, $mbox);
		
		foreach ($message->attachments as $attachment) {
			if($attachment->mime_id == $mime_id) {
				list($mime_id, $index) = explode(':', $mime_id);
				$part = $message->get_part_content($mime_id, null, true);
				$fname = $attachment->filename;
				
				$myfile = fopen($attpath."/".$fname, "w") or die("Unable to open file!");
				fwrite($myfile, $part);
				fclose($myfile);
			}
        }
		
		$rcmail->output->command('storage/dmessage', array('message' => $fname));
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

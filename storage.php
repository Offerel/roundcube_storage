<?php
/**
 * Roundcube elfinder Plugin
 * Integrate elFinder in to Roundcube
 *
 * @version 1.2.1
 * @author Offerel
 * @copyright Copyright (c) 2018, Offerel
 * @license GNU General Public License, version 3
 */
class storage extends rcube_plugin
{
	public $task = '.*';

	public function init()
	{
		$rcmail = rcmail::get_instance();
		$this->load_config();
		$this->add_texts('localization/', true);
		$this->include_stylesheet($this->local_skin_path() . '/elfinder.css');
		$this->include_script('client.js');
		$this->register_task('storage');

		$this->add_button(array(
			'label'	=> 'storage.storage',
			'command'	=> 'storage',
			'id'		=> 'stbutton',
			'class'		=> 'button-storage',
			'classsel'	=> 'button-storage button-selected',
			'innerclass'=> 'button-inner'
		), 'taskbar');

		if ($rcmail->task == 'storage') {
			$this->register_action('index', array($this, 'action'));
		}

		$this->add_hook('template_container', array($this, 'add_saveatt_link'));

		$this->register_action('save_one', array($this, 'save_one'));
		$this->register_action('elattach', array($this, 'attach_file'));
		//$this->register_action('add_note', array($this, 'add_nnote'));
		
		$rcmail->output->set_env('spath', dirname($rcmail->config->get('storage_url', false))."/");
		$rcmail->output->set_env('elbutton', $this->gettext('loadattachment'));
		
		/*
		if (!$rcmail->action) {
            $this->plugin_submenu();
        }
		*/
	}
	/*
	public function plugin_submenu()
    {
		$rcmail  = rcmail::get_instance();
		$this->add_texts('localization/', true);
		
		$rcmail->output->set_env('ntitle', $this->gettext('note_title'));
		$rcmail->output->set_env('ntags', $this->gettext('note_tags'));
			
		$li = '';
		$li .= html::tag('li', null, $rcmail->output->button(array('command' => 'plugin.storage.add_note', 'type' => 'link', 'class' => 'myclass3', 'label' => 'storage.note_new')));
		$out = html::tag('ul', array('id' => 'storage_sub'), $li);
		$rcmail->output->add_footer(html::div(array('style' => 'display: none;'), $out));
    }
	*/
	
	
	function add_nnote()
	{
		$title = rcube_utils::get_input_value('_title', rcube_utils::INPUT_POST);
		$tag_arr = preg_split ('/[\s*,\s*]*,+[\s*,\s*]*/', rcube_utils::get_input_value('_tags', rcube_utils::INPUT_POST));
		$tag_str = implode(",", $tag_arr);
		
		$inhalt = "<html><head><title>$title</title><meta name=\"keywords\" content=\"$tag_str\" /><meta name=\"author\" content=\"Sebastian Pfohl\" /></head><body></body></html>";
		
		$path = $rcmail->config->get('storage_basepath', false).$rcmail->user->get_username().'/files';	
		$notespath = $path.'/'.$rcmail->config->get('storage_notes', false);		
		if (!is_dir($notespath))
		{
			mkdir($notespath);         
		}
		
		$filepath = $notespath."test.html";
		
		$myfile = fopen($filename, "w") or die("Unable to open file!");
		fwrite ($myfile, $inhalt);
		fclose ($myfile);
		
		// Editor nun mit $filename öffnen
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
		$mbox = rcube_utils::get_input_value('_mbox', rcube_utils::INPUT_POST);
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
		//$rcmail->output->command('storage/dmessage', array('message' => 'The File '.$fname.' is saved.'));
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

	function attach_file()
	{
		$rcmail = rcmail::get_instance();
		$uploadid = rcube_utils::get_input_value('_tid', rcube_utils::INPUT_POST);
		$filepath = rcube_utils::get_input_value('_file', rcube_utils::INPUT_POST);
		$COMPOSE_ID = rcube_utils::get_input_value('_cid', rcube_utils::INPUT_POST);
		$COMPOSE = null;

		if ($COMPOSE_ID && $_SESSION['compose_data_'] . $COMPOSE_ID)
			{
				$SESSION_KEY = 'compose_data_' . $COMPOSE_ID;
				$COMPOSE =& $_SESSION[$SESSION_KEY];
			}

		if (!$COMPOSE) {
		  die("Invalid session var!");
		}

		$elpath = $rcmail->config->get('storage_basepath', false).$rcmail->user->get_username().'/files'.substr($filepath, strpos($filepath, "/"));
		$rcmail->output->reset();

		if (is_file($elpath)) {
			$temp_dir = $rcmail->config->get('temp_dir');
			$tmpfname = tempnam($temp_dir, 'rcmAttmnt');
			copy($elpath, $tmpfname);

			$attachment = array(
              'path' => $tmpfname,
              'size' => filesize($elpath),
              'name' => basename($elpath),
              'mimetype' => rcube_mime::file_content_type($elpath, basename($elpath)),
              'group' => $COMPOSE_ID,
            );

			$attachment = $rcmail->plugins->exec_hook('attachment_save', $attachment);
			$id = $attachment['id'];

			$COMPOSE['attachments'][$id] = $attachment;

			$rcmail->session->append($SESSION_KEY.'.attachments', $id, $attachment);

			if (($icon = $COMPOSE['deleteicon']) && is_file($icon))
			{
				$button = html::img(array(
					'src' => $icon,
					'alt' => $rcmail->gettext('delete')
				));
			}
			else {
				$button = rcube::Q($rcmail->gettext('delete'));
			}

			$link_content = sprintf('%s <span class="attachment-size"> (%s)</span>',
				rcube::Q($attachment['name']), $rcmail->show_bytes($attachment['size']));

			$content_link = html::a(array(
					'href'    => "#load",
					'class'   => 'filename',
					'onclick' => sprintf("return %s.command('load-attachment','rcmfile%s', this, event)", rcmail_output::JS_OBJECT_NAME, $id),
				), $link_content);

			$delete_link = html::a(array(
				'href' => "#delete",
				'onclick' => sprintf("return %s.command('remove-attachment','rcmfile%s', this, event)", rcmail_output::JS_OBJECT_NAME, $id),
				'title' => $rcmail->gettext('delete'),
				'class' => 'delete',
				), $button);

			$content = $COMPOSE['icon_pos'] == 'left' ? $delete_link.$content_link : $content_link.$delete_link;

			$rcmail->output->command('add2attachment_list', "rcmfile$id", array(
              'html' => $content,
              'name' => $attachment['name'],
              'mimetype' => $attachment['mimetype'],
              'classname' => rcube_utils::file2class($attachment['mimetype'], $attachment['name']),
              'complete' => true), $uploadid);

            $rcmail->output->command('auto_save_start', false);
            $rcmail->output->send('iframe');         

        } else {
            $rcmail->output->show_message("\"$filepath\" is not a file", 'error');
            $rcmail->output->send('iframe');
        }
    }
}
?>

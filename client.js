window.rcmail && rcmail.addEventListener('init', function(evt) {
	rcmail.register_command('plugin.storage.save_one', save_one, true);

	rcmail.addEventListener('beforemenu-open', function(p) {
		if (p.menu == 'attachmentmenu') {
			rcmail.env.selected_attachment = p.id;
		}
	});
});

function save_one()
{
	var part = rcmail.env.selected_attachment;
	rcmail.http_post('storage/save_one', '_mbox=' + urlencode(rcmail.env.mailbox) + '&_uid=' + rcmail.env.uid + '&_part=' + part);
}

function dmessage(response)
    {
      alert(response.message);
    }
/**
 * Roundcube elfinder Plugin
 *
 * @version 1.4.8
 * @author Offerel
 * @copyright Copyright (c) 2022, Offerel
 * @license GNU General Public License, version 3
 */
window.rcmail && rcmail.addEventListener('init', function(evt) {
	rcmail.register_command('plugin.storage.save_one', save_one, true);

	rcmail.addEventListener('beforemenu-open', function(p) {
		if (p.menu == 'attachmentmenu') {
			rcmail.env.selected_attachment = p.id;
		}
	});
	
	var fbutton = $("#compose-attachments").contents().find(".button");
	fbutton.after("<a class='button' tabindex='2' href='#' onclick='cform();'>" + rcmail.env.elbutton + "</a>");
	
	$("#compose-attachments").contents().find("button").after("<button class='btn btn-secondary attach' tabindex='3' href='#' style='margin-left: 5px;' onclick='cform();'>" + rcmail.env.elbutton + "</button>");
});

if('serviceWorker' in navigator) {
	window.addEventListener('load', function() {
		navigator.serviceWorker.register('plugins/storage/sw.js').then(function(registration) {
			console.info('ServiceWorker registered');
		}, function(err) {
			console.error('ServiceWorker registration failed: ', err);
		});
	});
}

function save_one() {
	var part = rcmail.env.selected_attachment;
	rcmail.http_post('storage/save_one', '_mbox=' + urlencode(rcmail.env.mailbox) + '&_uid=' + rcmail.env.uid + '&_part=' + part);
}

function dmessage(response) {
	alert(response.message);
}
    
function cform() {
	let specs = "location=no,menubar=no,resizable=no,scrollbars=no,status=no,titlebar=no,toolbar=no,width=840,height=370,";
	var elwindow = window.open('plugins/storage/elfinder/elopen.html?id=' + location.search.split('id=')[1], 'elopen', specs, false);
}

function elcallback(files,cid,tid) {
	for (id in files) {
		rcmail.http_post('storage/elattach', '_tid=' + tid + '&_cid=' + cid + '&_file=' + files[id].path);
	}

}
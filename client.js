function onLoadHandler() {
	var iframe = document.getElementById("storagecontentframe");
	var innerDoc = iframe.contentDocument || iframe.contentWindow.document;

	innerDoc.getElementById('username').value = rcmail.env.storage_username;
	innerDoc.getElementById('password').value = rcmail.env.storage_password;
	
	innerDoc.getElementById('send').click();
}
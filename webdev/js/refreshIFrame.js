function reloadChat() {
    var iframes = document.getElementsByTagName('iframe');
    for (var i = 0; i < iframes.length; i++) {
	iframes[i].contentWindow.location.reload();
    }
}

function startChat() {
    setInterval(reloadChat, 2000);
    console.log('Sucessfully keeping up with chat and online user');
}
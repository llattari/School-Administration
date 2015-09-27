function reloadChat() {
    var iframe = document.getElementsByTagName('iframe')[0];
    iframe.contentWindow.location.reload();
    console.log('Test');
}

function startChat() {
    setInterval(reloadChat, 2000);
}
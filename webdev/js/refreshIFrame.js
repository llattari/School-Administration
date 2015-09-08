function reloadChat() {
    var iframe = document.getElementsByTagName('iframe')[0];
    iframe.contentWindow.location.reload();
}

function startChat() {
    setInterval(reloadChat(), 5000);
}
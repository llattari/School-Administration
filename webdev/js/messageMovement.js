
var element;

function show() {
    var idSuc = document.getElementById('mSuc');
    var idFail = document.getElementById('mError');
    if (idFail == null) {
	element = idSuc;
	setTimeout(hide, 1000);
    } else {
	element = idFail;
	setTimeout(hide, 1000);
    }
}

function hide() {
    if (element) {
	element.style.display = "none";
    } else {
	window.console.log('Could not find object to hide');
    }
}
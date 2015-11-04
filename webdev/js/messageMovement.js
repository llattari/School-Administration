function show(element) {
    element.style.visiblity = "visible";
    setTimeout(hide(), 2000);
}

function hide(element) {
    element.style.visiblity = "hidden";
}
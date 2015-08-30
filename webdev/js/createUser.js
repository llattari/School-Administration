function openHidden(element) {
    var allElements = document.getElementsByClassName('hidden');
    var value = 'none';
    if(element.value == "s"){
	value = 'block';
    }
    for(var i = 0; i < allElements.length; i++){
	allElements[i].style.display = value;
    }
}


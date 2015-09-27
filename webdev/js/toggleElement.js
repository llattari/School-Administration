var allContent = Array();

function toggleVisibility(element) {
    allDivs = document.getElementsByTagName("div");
    for (var i = 0; i < allDivs.length; i++) {
	if (allDivs[i] == element) {
	    if (allDivs[i].className == "card") {
		allDivs[i].className = "colapsed";
		textElement = allDivs[i].getElementsByTagName("p")[0];
		allContent[i] = allDivs[i].removeChild(textElement);
	    } else {
		allDivs[i].className = "card";
		allDivs[i].appendChild(allContent[i]);
	    }
	}
    }
    return false;
}


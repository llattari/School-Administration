
// Toogels the class of an element that is clicked
function toggleCheck(entry) {
    var id = getID(entry);
    if (!id)
	return false;
    document.location = '?toggleId=' + id;
    return true;
}

// Removes any selection from the document
function clearSelection() {
    if (window.getSelection)
	window.getSelection().removeAllRanges();
    else if (document.selection)
	document.selection.empty();
}

// Filters the table with different aspects
function filterTable() {
    var toDoFilter = document.getElementsByName('toDoFilter')[0].value;
    var typeFilter = document.getElementsByName('typeFilter')[0].value;
    var priortiyFilter = document.getElementsByName('priortiyFilter')[0].value;
    var subjectFilter = document.getElementsByName('subjectFilter')[0].value;
    var allTRs = document.getElementsByTagName('tr');
    for (var i = 0; i < allTRs.length; i++)
	if (allTRs[i].getAttribute('class') != null) {
	    allTRs[i].removeAttribute('style');
	    if (toDoFilter != 'None' && allTRs[i].getAttribute(
		    'class') != toDoFilter.toLowerCase())
		allTRs[i].style.display = 'none';
	    if (typeFilter != 'None') {
		var child = allTRs[i].children[2].innerHTML;
		var endPos = child.indexOf(' (');
		var substr = child.substring(0, endPos);
		if (substr != typeFilter)
		    allTRs[i].style.display = 'none';
	    }
	    if (priortiyFilter != 'None' && allTRs[i].children[3].innerHTML != priortiyFilter)
		allTRs[i].style.display = 'none';
	    if (subjectFilter != 'None') {
		var child = allTRs[i].children[2].innerHTML;
		var endPos = child.indexOf(' (');
		var substr = child.substring(endPos + 2, child.length - 1);
		alert(substr);
		if (substr != subjectFilter)
		    allTRs[i].style.display = 'none';
	    }
	}
}

//Reset the filter
function resetFilter() {
    var allTRs = document.getElementsByTagName('tr');
    for (var i = 0; i < allTRs.length; i++) {
	allTRs[i].removeAttribute('style');
    }
}

//Saving the sates of the original table to see the differences with submitting
var ids = new Array();

function saveState() {
    var allTRs = document.getElementsByTagName('tr');
    for (var i = 0; i < allTRs.length; i++)
	if (allTRs[i].getAttribute('onclick')) {
	    var content = allTRs[i].children[0].innerHTML;
	    allTRs[i].children[0].innerHTML = content.substring(
		    content.indexOf('.') - 2);
	    ids[ids.length] = Number(content.substring(0,
		    content.indexOf('.') - 2));
	}
}

//Gets the id of a certain row
function getID(entry) {
    if (entry.getAttribute('class') == null)
	return false;
    var allTRs = document.getElementsByTagName('tr');
    for (var i = 0; i < allTRs.length; i++)
	if (allTRs[i] == entry)
	    return ids[i - 1];
    return false;
}

// Forwards a user to the delete website
function deleteEntry(entry) {
    var id = getID(entry);
    var confirm = confirm('Do you realy want to delete the task?');
    if (!id && confirm) {
	return false;
    }
    document.location = 'delete.php?id=' + id;
    return true;
}
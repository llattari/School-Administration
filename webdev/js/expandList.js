function toggle(aLink) {
    var element = document.getElementById('no');
    if(element.style.display == 'block'){
	element.style.display = 'none';
	aLink.innerHTML = 'Show all';
    }else{
	element.style.display = 'block';
	aLink.innerHTML = 'Hide inactive';
    }
}



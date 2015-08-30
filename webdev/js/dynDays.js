function checkDays() {
    var elements = document.getElementsByTagName('td');
    if(window.innerWidth < 650){
	elements[1].innerHTML = 'Mon';
	elements[2].innerHTML = 'Tues';
	elements[3].innerHTML = 'Wed';
	elements[4].innerHTML = 'Thurs';
	elements[5].innerHTML = 'Fri';
	return 'Short';
    }
    else{
	elements[1].innerHTML = 'Monday';
	elements[2].innerHTML = 'Tuesday';
	elements[3].innerHTML = 'Wednesday';
	elements[4].innerHTML = 'Thursday';
	elements[5].innerHTML = 'Friday';
	return 'Long';
    }
}


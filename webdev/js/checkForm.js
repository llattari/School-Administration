//todo formchecking

function markInvalidFields(invalid) {
    //Marking all invalid fields red
    for (var i = 0; i < invalid.length; i++) {
	invalid[i].style.backgroundColor = 'red';
    }
}

function checkMail() {
    var valid = true;
    var invalid = [];

    //Subject can not be empty
    if (document.getElementsByName('subject')[0].value.length == 0) {
	valid = false;
	invalid.push(document.getElementsByName('subject')[0]);
	alert('Subject can\'t be empty.');
    }

    //Reciever can not be 0
    if (document.getElementsByName('receiver')[0].value == 0) {
	valid = false;
	invalid.push(document.getElementsByName('receiver')[0]);
	alert('You have not selected a receiver.');
    }

    //Message content can not be the default message
    if (document.getElementsByName('message')[0].value.length < 5) {
	valid = false;
	invalid.push(document.getElementsByName('message')[0]);
	alert('The text has to be at least 5 characters long.');
    }
    markInvalidFields(invalid);
    return valid;
}

function checkEvent() {
    var valid = true;
    var invalid = [];

    //Name of the event can't be empty
    if (document.getElementsByName('eventName')[0].value.length == 0) {
	valid = false;
	invalid.push(document.getElementsByName('name')[0]);
	alert('Name can\'t be empty.');
    }

    var dateRegEx = /(\d{1,2})([\.\/])(\d{1,2})\2?(\d{4})?$/g;
    //Start time
    if (document.getElementsByName('start')[0].value.length == 0) {
	var string = document.getElementsByName('start')[0].value;
	var result = dateRegEx.test(string);
	if (!result) {
	    valid = false;
	    invalid.push(document.getElementsByName('start')[0]);
	    alert('Invalid format for starting date');
	}
    }

    //End time
    if (document.getElementsByName('end')[0].value.length == 0) {
	var string = document.getElementsByName('end')[0].value;
	var result = dateRegEx.test(string);
	if (!result) {
	    valid = false;
	    invalid.push(document.getElementsByName('end')[0]);
	    alert('Invalid format for ending date');
	}
    }
    return valid;
}
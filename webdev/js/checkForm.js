//todo formchecking

function checkMail() {
    var valid = true;
    var invalid = [];
    //Sibject can not be empty
    if(document.getElementsByName('subject')[0].value.length == 0){
	valid = false;
	invalid.push(document.getElementsByName('subject')[0]);
	alert('Subject can\'t be empty.');
    }

    //Reciever can not be 0
    if(document.getElementsByName('receiver')[0].value == 0){
	valid = false;
	invalid.push(document.getElementsByName('receiver')[0]);
	alert('You have not selected a receiver.');
    }

    //Message content can not be the default message
    if(document.getElementsByName('message')[0].value.length < 5){
	valid = false;
	invalid.push(document.getElementsByName('message')[0]);
	alert('The text has to be at least 5 characters long.');
    }

    //Marking all invalid fields red
    for(var i = 0; i < invalid.length; i++){
	invalid[i].style.backgroundColor = 'red';
    }
    return valid;
}
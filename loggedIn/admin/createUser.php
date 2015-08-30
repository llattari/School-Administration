<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Generators/randomGenerator.php';
require_once '../../webdev/php/Classes/ClassPerson.php';

$HTML = new HTMLGenertator\HTMLfile('Create new user', ['form.css', 'userCreate.css'], ['createUser.js'], NULL, 1);
$HTML->outputHeader();

if(isset($_GET['username'])){
    $uname = escapeStr($_GET['username']);
    $name = Array(
	'name' => escapeStr($_GET['name']),
	'surname' => escapeStr($_GET['lastName'])
    );
    $mailPassword = (bool) $_GET['mailPassword'];
    $bday = strtotime($_GET['bday']);
    //Contact information
    $adress = Array(
	'street' => escapeStr($_GET['street']),
	'zip' => escapeStr($_GET['zip']),
	'city' => escapeStr($_GET['city']),
	'tel' => escapeStr($_GET['telephone']),
	'mail' => escapeStr($_GET['eMail'])
    );
    $type = $_GET['type'];
    if($type == "s"){
	$schoolInformation = (int) $_GET['grade'];
    }
    //Checking the information entered
    $valid = check($adress['mail'], $adress['tel'], $adress['zip']) && $bday > 0;
    if($valid){
	$randLen = randomNumber(1, 50);
	$password = randomString(20);
	$suc1 = safeQuery('INSERT INTO user__overview VALUES (NULL,"' . $name['name'] . '", "' . $name['surname'] . '", "' . $adress['mail'] . '", "' . $adress['tel'] . '", NULL, "' . $adress['street'] . '", ' . $adress['zip'] . ', "' . $adress['city'] . '", \'' . date('Y-m-d', $bday) . '\', "' . $type . '", NULL, "' . $uname . '", NULL, \'' . randomString($randLen) . '\');');
	$insertId = mysql_insert_id();
	Logger::log("Creating user with the id $insertId.", Logger::USERMANAGEMENT);
	if($schoolInformation != 0){
	    $suc2 = safeQuery("UPDATE user__overview SET grade = $schoolInformation WHERE id = $insertId;");
	    Logger::log("Changes grade for user with the id $insertId.", 'User adminstration');
	    if($suc1 && $suc2){
		Message::castMessage('User succesfully created', true, 'createUser.php');
	    }else{
		Message::castMessage('Something went wrong during creation', false, 'createUser.php');
	    }
	}
	$errorNo = mysql_errno();
	if($errorNo == 1062){
	    Message::castMessage('User already exists', false, 'createUser.php');
	    Logger::log('Trying to insert a username that already exists.', Logger::USERMANAGEMENT);
	}else{
	    Message::castMessage('Some Error occured');
	}
    }
}
?>
<h1>Create new user</h1>
<form method="GET" action="#" name="newUser">
    <fieldset>
	<legend>User information</legend>
	<input type="text" name="username" placeholder="Username"/>
	<br />
	<input type="checkbox" checked="checked" name="mailPasswd"/>
        <label for="mailPasswd">Send the user an email to set the password</label>
	<br />
	<input type="password" name="password" placeholder="Enter the password you want" />
    </fieldset>
    <fieldset>
        <legend>Basic information</legend>
        <input type="text" name="name" placeholder="Firstname" />
        <input type="text" name="lastName" placeholder="Lastname" />
        <br />
        <input type="date" name="bday" min="0" max="100" placeholder="Birthday" />
    </fieldset>
    <fieldset>
        <legend>Contact information</legend>
        <input type="text" name="street" placeholder="Street and Housenumber">
        <br />
        <input type="text" name="zip" pattern="\d{5}" placeholder="ZIP Code" />
        <input type="text" name="city" placeholder="City" />
        <br />
        <input type="tel" name="telephone" placeholder="Telephone" value="02131-"/>
        <br />
        <input type="email" name="eMail" placeholder="E-Mail Adress" />
    </fieldset>
    <fieldset>
        <legend>School information</legend>
        <select name="type" onchange="openHidden(this)">
            <option selected="selected">Select type...</option>
            <option value="s">Student</option>
            <option value="t">Teacher</option>
            <option value="h">Headmaster</option>
        </select>
        <!-- if the person is a student this will be extended -->
        <fieldset class="hidden">
            <legend>Student information</legend>
            <input type="checkbox" name="inSchool">
            <label for="inSchool">Student is not in school anymore</label>
            <input type="number" min="1" max="14" placeholder="Grade" />
        </fieldset>
    </fieldset>
    <!-- Form buttons -->
    <button type="reset">Reset page</button>
    <button type="submit">Create new user</button>
</form>
<?php
$HTML->outputFooter();
?>
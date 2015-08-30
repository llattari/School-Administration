<?php

require_once 'webdev/php/essentials/essentials.php';
require_once 'webdev/php/Classes/Messages.php';
require_once 'webdev/php/Classes/debuging/Logger.php';
connectDB();

// Getting the inputs form the form
$uname = escapeStr($_POST['username']);
$psw = escapeStr($_POST['psw']);

/**
 * createSESSION()
 * Creates a session out of a databse row
 * @param string $query
 * @return boolean Returns wheather succesfull or not.
 */
function createSESSION($query) {
    session_start();
    $result = safeQuery($query);
    if(mysql_num_rows($result) != 1){
	return false;
    }
    $row = mysql_fetch_assoc($result);
    $_SESSION['studentId'] = $row['id'];
    if(is_null($row['grade'])){
	$_SESSION['grade'] = NULL;
    }else{
	$_SESSION['grade'] = $row['grade'];
    }
    $_SESSION['teacher'] = is_null($row['grade']);
    //Setting the UI settings
    $_SESSION['ui'] = Array();
    foreach($row as $key => $value){
	$_SESSION['ui'][$key] = $value;
    }
    return true;
}

//Gets the userId with the username
$userIdResult = safeQuery("SELECT id FROM user__overview WHERE username = '$uname';");
if(mysql_num_rows($userIdResult) != 1){
    Message::castMessage('Unkown username', false, 'index.php');
    return;
}else{
    $userIdRow = mysql_fetch_row($userIdResult);
    $userId = $userIdRow[0];
}

// Querrying the password
$passwordResult = safeQuery("SELECT forget FROM user__password
    WHERE id = $userId
    AND password = MD5(CONCAT('scnhjndur4hf389ur4h3fbäjqdjsdncsjkvdnkvj', '$psw', passwordSuffix));");
$passwordRow = mysql_fetch_row($passwordResult);
if($passwordRow[0] == true){
    Message::castMessage('You have to set a new password first.', false, 'index.php');
}else{
    //Creating a session object
    $suc = createSESSION(
	    "SELECT
		user__overview.id AS 'id',
		grade, status, markNames, darkTheme
	    FROM user__overview
	    JOIN user__interface ON user__interface.userID = user__overview.id
	    WHERE user__overview.id = $userId;");
    if($suc){
	Message::castMessage('Successfully logged in', true, 'loggedIn/index.php');
	Logger::log('User logged in.', Logger::USERMANAGEMENT);
    }else{
	Message::castMessage('SESSION creation failed please contact the administrator.', false, 'help.php');
    }
}

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
    if (mysql_num_rows($result) != 1) {
	return false;
    }
    $row = mysql_fetch_assoc($result);
    $_SESSION['studentId'] = $row['id'];
    if (is_null($row['grade'])) {
	$_SESSION['grade'] = NULL;
    } else {
	$_SESSION['grade'] = $row['grade'];
    }
    $_SESSION['teacher'] = is_null($row['grade']);
    //Setting the UI settings
    $_SESSION['ui'] = [];
    foreach ($row as $key => $value) {
	$_SESSION['ui'][$key] = $value;
    }
    return true;
}

$destination = 'index.php';

//Gets the userId with the username
$userIdResult = safeQuery(
	"SELECT user__overview.id, forget
	FROM user__overview
	JOIN user__password
	ON user__overview.id = user__password.id
	WHERE username = '$uname';");
if (mysql_num_rows($userIdResult) != 1) {
    Message::castMessage('Unknown username', false, 'index.php');
    Logger::log("Username $uname was used to login.", LoggerConstants::LOGIN);
    return;
}
$userIdRow = mysql_fetch_row($userIdResult);
if ($userIdRow[1] == true) {
    Message::castMessage('You have to set a new password first.', false, $destination);
} else {
    $userId = $userIdRow[0];
}

// Querrying the password
$passwordResult = safeQuery("SELECT forget FROM user__password
			    WHERE id = $userId
			    AND password = MD5(CONCAT('scnhjndur4hf389ur4h3fbäjqdjsdncsjkvdnkvj', '$psw', passwordAppendix));");
if (mysql_num_rows($passwordResult) == 0) {
    Logger::log("User ($uname) tried to login.", LoggerConstants::LOGIN);
    Message::castMessage('Wrong password for this username.', false, $destination);
} else {
    //Creating a session object
    $suc = createSESSION(
	    "SELECT
		user__overview.id AS 'id',
		user__interface.*
	    FROM user__overview
	    JOIN user__interface ON user__interface.id = user__overview.id
	    WHERE user__overview.id = $userId;");
    if ($suc) {
	Message::castMessage('Successfully logged in', true, 'loggedIn/index.php');
	Logger::log('User logged in.', Logger::USERMANAGEMENT);
    } else {
	Message::castMessage('Session creation failed please contact the administrator.', false, 'index.php');
    }
}

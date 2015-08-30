<?php

session_start();
require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Classes/debuging/Logger.php';
require_once '../../webdev/php/Classes/Messages.php';

$newFriends = (int) $_POST['newFriends'];
$home = 'index.php';

#If the default element is still in the list. => Remove it
if($newFriends[0] == 0){
    $newFriends = array_slice($newFriends, 1);
    if(count($newFriends) == 0){
	Message::castMessage('You have just selected the default text.', false, $home);
    }
}

$result = safeQuery('INSERT INTO friendsList(fOne, fTwo)VALUES (' . $_SESSION['studentId'] . ", $newFriends);");
if($result){
    Message::castMessage('Successfully send friend requests', true, $home);
    Logger::log('New friend request for user: ' . $newFriends, Logger::SOCIAL);
}else{
    Message::castMessage('There was a problem with the database.', false, $home);
}
?>
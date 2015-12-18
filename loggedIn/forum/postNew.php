<?php

//Setting everything up

require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Classes/Messages.php';
session_start();
connectDB();

//Check if there is a valid topicId
if (!isset($_POST['topicId'])) {
    Message::castMessage('Invalid topic id', false, 'index.php');
} else {
    $topicId = (int) $_POST['topicId'];
}

$topicPath = 'readTopic.php?topicId=' . $topicId;

//Check if the postMessage was not empty
if (!isset($_POST['postMessage'])) {
    Message::castMessage('No valid post request.', false, $topicPath);
} else if (empty($_POST['postMessage'])) {
    Message::castMessage('Can\'t submit empty message.', false, $topicPath);
}

//Gathering data
$posterId = $_SESSION['studentId'];
$messageContent = escapeStr($_POST['postMessage']);

//Querrying the database
$querry = safeQuery("INSERT INTO forum__post VALUES(NULL, $topicId, '$messageContent', NOW(), $posterId);");

//Evaluate querry
if ($querry) {
    Message::castMessage('Succesfully posted the message.', true, $topicPath);
} else {
    Message::castMessage('Could not post the message.', false, $topicPath);
}
?>
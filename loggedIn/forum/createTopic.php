<?php

//Setting up
require_once '../../webdev/php/Forum/Section.php';
require_once '../../webdev/php/Classes/Messages.php';
require_once '../../webdev/php/essentials/databaseEssentials.php';
session_start();
connectDB();

//If there is no forum that the topic could be associated => reroute.
if (!isset($_POST['forumId'])) {
    Message::castMessage('Please select forum', false, 'index.php');
}

//Aquiring the data
$forumId = (int) $_POST['forumId'];
$topicName = escapeStr($_POST['topicName']);
$description = escapeStr($_POST['description']);
$creatorId = $_SESSION['studentId'];


//Querrying the database
$query = safeQuery("INSERT INTO forum__topic VALUES(NULL, $forumId, '$topicName', '$description', $creatorId);");
if ($query) {
    Message::castMessage('Successfully created topic.', true, 'index.php?forumId=' . $forumId);
} else {
    Message::castMessage('Could not create topic.', false, 'newTopic.php?forumId=' . $forumId);
}

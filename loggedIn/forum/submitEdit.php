<?php

require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Classes/Messages.php';
connectDB();

if (isset($_POST['id']) && isset($_POST['newMessageText']) && isset($_POST['topicId'])) {
    $id = intval($_POST['id']);
    $topicId = intval($_POST['topicId']);
    $newMessage = escapeStr($_POST['newMessageText']);
    $result = safeQuery('UPDATE forum__post SET post = "' . $newMessage . '" WHERE id = ' . $id . ';');
    $destination = 'readForum.php?topicId=' . $topicId;
    if ($result) {
	Message::castMessage('Successfully changed the post.', true, $destination);
    } else {
	Message::castMessage('Could not save the changes that were made.', false, $destination);
    }
} else {
    if (isset($_POST['id']) && isset($_POST['topicId'])) {
	Message::castMessage('No message provided', false, 'editPost.php?topicId=' . $_POST['topicId'] . '&id=' . $_POST['id']);
    } else {
	Message::castMessage('Invalid id', false, 'index.php');
    }
}
?>
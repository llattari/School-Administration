<?php

require_once '../../../webdev/php/essentials/databaseEssentials.php';
require_once '../../../webdev/php/Classes/Messages.php';
session_start();
$destination = 'list.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id == 0) {
	Message::castMessage('Invalid id', false, $destination);
    } elseif ($id == $_SESSION['studentId']) {
	Message::castMessage('You can not delete your own profile.', false, $destination);
    }
    $result = safeQuery('DELETE FROM user__overview WHERE id=' . $id . ';');
    if ($result) {
	Message::castMessage('User sucessfully deleted', true, $destination);
    } else {
	Message::castMessage('User could not be deleted', false, $destination);
    }
} else {
    Message::castMessage('Please specify an id', false, $destination);
}
?>
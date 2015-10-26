<?php

require_once '../../webdev/php/Classes/debuging/Logger.php';
require_once '../../webdev/php/Classes/Messages.php';
$destination = 'create.php';

//Get the name
$name = isset($_POST['eventName']) ? $_POST['eventName'] : NULL;
if ($name == NULL) {
    Message::castMessage('Event\'s name can not be empty', false, $destination);
}

$creator = (int) $_POST['studentId'];
if ($creator < 1) {
    Message::castMessage('Invalid creator id', false, $destination);
}
$description = isset($_POST['description']) ? $_POST['description'] : 'NULL';
$isPrivate = isset($_POST['private']) ? 'true' : 'false';

//Get starting time and ending time
$startTime = strtotime($_POST['start'] . ' ' . $_POST['startH'] . ':' . $_POST['startM'] . ':00');
$endTime = strtotime($_POST['end'] . ' ' . $_POST['endH'] . ':' . $_POST['endM'] . ':00');

if ($startTime === false || $endTime === false) {
    Message::castMessage('Wrong format of date', false, $destination);
}

safeQuery('INSERT INTO event__participants(`type`, `value`)VALUE("p", ' . $creator . ');');
$insertId = mysql_insert_id();
$eventSQL = 'INSERT INTO event__upcoming(creatorID, name, startTime, endTime, description, participants, private) VALUES (' .
	"$creator, '$name', $startTime, $endTime, '$description', $insertId, $isPrivate);";
$result = safeQuery($eventSQL);
if (!$result) {
    Message::castMessage('Created a new event', true, $description);
    Logger::log('Created an event.', LoggerConstants::TASKMANAGEMENT);
}
?>
<?php

require_once '../../webdev/php/essentials/databaseEssentials.php';
connectDB();
require_once '../../webdev/php/Classes/Messages.php';

$id = intval($_POST['id']);
$list = safeQuery('SELECT CONCAT("i",studentId,"t", task) FROM marks__points WHERE testId = ' . $id . ';');
$dataAlreadyEntered = Array();
while ($row = mysql_fetch_row($list)) {
    array_push($dataAlreadyEntered, $row[0]);
}

print_r($dataAlreadyEntered);
$smooth = true;
foreach ($_POST as $key => $value) {
    if ($key == 'id') {
	continue;
    }
    preg_match('/i(\d+)t(\d+)/i', $key, $regEx);
    if (in_array($key, $dataAlreadyEntered)) {
	$worked = safeQuery('UPDATE marks__points SET score=' . $value . ' WHERE studentId=' . $regEx[1] . ' AND testId=' . $id . ' AND task=' . $regEx[2] . ';');
	if (!$worked) {
	    $smooth = false;
	}
    } else {
	$worked = safeQuery('INSERT INTO marks__points (studentId, testId, task, score)VALUES(' . $regEx[1] . ', ' . $id . ', ' . $regEx[2] . ', ' . $value . ');');
	if (!$worked) {
	    $smooth = false;
	}
    }
}
if ($smooth) {
    Message::castMessage('Sucessfully updated the scores of the students.', true, 'enterScore.php?id=' . $id);
} else {
    Message::castMessage('Could not enter the new marks.', false, 'enterScore.php?id=' . $id);
}
?>
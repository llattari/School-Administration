<?php

function getTimetable() {
    $queryTable = '
	SELECT
	    course__overview.subject AS "subject",
	    day, lesson, room
        FROM course__student
        RIGHT JOIN timetable__overview
        ON
	    timetable__overview.classID = course__student.classID
        LEFT JOIN course__overview
        ON
	    course__student.classID = course__overview.id
        WHERE
	    course__student.studentID IS NULL
	    OR
	    course__student.studentID = ' . $_SESSION['studentId'] . '
        ORDER BY lesson, day;';
    $times = getTimes();
    $result = safeQuery($queryTable);
    if (!$result) {
	return '';
    }
    $allData = Array();
    for ($i = 0; $i < count($times); $i++) {
	$allData[$i] = Array($times[$i]);
    }
    while ($row = mysql_fetch_assoc($result)) {
	if (is_null($row['subject'])) {
	    $allData[$row['lesson']][$row['day']] = 'Unknown course';
	} else {
	    $allData[$row['lesson']][$row['day']] = dataToString($row);
	}
    }
    return $allData;
}

/**
 * dataToString($arrayData)
 * 	Extracts the information out of the sql result
 * @param Array $arrayData
 * @return type
 */
function dataToString($arrayData) {
    return $arrayData['subject'] . '<br />(' . $arrayData['room'] . ')';
}

/**
 * getTimes()
 * 	Returns an array of the standard times in the timetable
 * @return Array
 */
function getTimes() {
    $times = Array();
    $result = safeQuery('SELECT start, end FROM timetable__standardTimes;');
    if ($result) {
	for ($i = 0; $row = mysql_fetch_row($result); $i++) {
	    $beginning = date('H:i', strtotime($row[0]));
	    $end = date('H:i', strtotime($row[1]));
	    array_push($times, $beginning . '<br />' . $end);
	}
    }
    return $times;
}

?>
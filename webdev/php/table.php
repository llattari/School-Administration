<?php

function getTimetable() {
    $queryTable = 'SELECT
	    course__overview.subject AS "subject",
	    day, lesson, room
        FROM course__student
        JOIN timetable__overview
        ON timetable__overview.classID = course__student.classID
        LEFT JOIN course__overview
        ON
	    course__student.classID = course__overview.id
        WHERE
	    course__student.studentID = ' . $_SESSION['studentId'] . '
        ORDER BY lesson, day;';
    $times = getTimes();
    $result = safeQuery($queryTable);
    if (!$result) {
	return '';
    }
    $final = '<tr><td>' . $times[0] . '</td>';
    $day = $hour = 1;
    while ($row = mysql_fetch_assoc($result)) {
	if ($hour != $row['lesson']) {
	    while ($hour != $row['lesson'] + 1) {
		$final.= '</tr><tr><td>' . $times[$row['lesson'] - 1];
		$hour++;
	    }
	    $day = 1;
	}
	while ($day < $row['day']) {
	    $final.= '<td class="free">Freetime</td>';
	    $day++;
	}
	$final.= '<td>' . dataToString($row) . '</td>';
	$day++;
    }
    $final.= '</tr>';
    return $final;
}

/**
 * dataToString($arrayData)
 * 	Extracts the information out of the sql result
 * @param Array $arrayData
 * @return type
 */
function dataToString($arrayData) {
    return $arrayData['subject'] . '<br /> (' . $arrayData['room'] . ')';
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
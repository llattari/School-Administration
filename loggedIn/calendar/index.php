<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Generators/CalenderGenerator.php';

$HTML = new HTMLGenertator\HTMLfile('Calendar', ['table.css', 'calendar.css', 'form.css'], NULL, NULL, 1);
$HTML->outputHeader();

function evalGet($var) {
    $year = (int) $var['y'];
    $month = (int) $var['m'];
    if ($year != 0 && $month != 0) {
	return new Calendar($var['m'], $var['y']);
    }
    if ($month != 0) {
	return new Calendar($var['m']);
    }
    return new Calendar();
}

$calendar = evalGet($_GET);

if (isset($_GET['date']) && isDate($_GET['date'])) {
    $date = strtotime($_GET['date']);
} else {
    $result = safeQuery(
	    'SELECT
	    startTime, endTime, event__upcoming.`name` AS "name",
	    event__upcoming.creatorID AS "Creator",
	    event__upcoming.participants AS "PartID",
	    user__overview.username AS "Student",
	    course__student.studentID AS "ClassStudent"
	FROM event__upcoming
	LEFT JOIN event__participants
	ON event__upcoming.participants = event__participants.id
	LEFT JOIN course__student
	ON
	    (event__participants.`value` = course__student.classID
	    AND
	    event__participants.`type` = "c")
	LEFT JOIN user__overview
	ON
	    (event__participants.`value` = user__overview.id
	    AND
	    event__participants.`type` = "p")
	WHERE
	    (MONTH(startTime) =' . $calendar->getMonth() . '
	    AND
	    YEAR(startTime) =  ' . $calendar->getYear() . ')
	    AND
	    (not private OR (private AND creatorID = ' . $_SESSION['studentId'] . '));'
    );

    $RandCol = Array('green', 'red', 'blue', 'yellow', 'orange', 'grey');

    if (mysql_num_rows($result) != 0) {
	$eventID = 0;
	while ($row = mysql_fetch_row($result)) {
	    global $RandCol;
	    $start = strtotime($row[0]);
	    $end = strtotime($row[1]);
	    for ($i = $start; $i <= $end; $i += $oneDayInSec) {
		$calendar->markDate(date('j', $i), $RandCol[$eventID % count($RandCol)]);
	    }
	    $eventID++;
	}
    }
}

if (!isset($date)) {
    ?>
    <h1>Calendar of <?php echo $calendar->getMonthName(); ?></h1>
    <form method="GET">
        Month:
        <select name="m">
	    <?php
	    for ($i = 1; $i < 13; $i++) {
		$monthName = date('F', mktime(0, 0, 0, $i, 10));
		$selected = '';
		if ($i == $calendar->getMonth()) {
		    $selected = 'selected="selected"';
		}
		echo "<option value=\"$i\" $selected> $monthName</option>";
	    }
	    ?>
        </select>
        Year:
        <select name="y">
    	<option value="<?php echo date('Y') ?>" selected="selected"><?php echo date('Y') ?></option>';
	    <?php
	    for ($i = date('Y') - 1; $i >= 1970; $i--) {
		$selected = '';
		if ($i == $calendar->getYear()) {
		    $selected = 'selected="selected"';
		}
		echo "<option value=\"$i\" $selected> $i</option>";
	    }
	    ?>
        </select>
        <button type="submit">Set</button>
    </form>
    <?php
    echo $calendar->output();
}

$HTML->outputFooter();
?>


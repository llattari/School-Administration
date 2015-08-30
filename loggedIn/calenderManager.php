<?php
require_once '../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../webdev/php/Generators/CalenderGenerator.php';

function isDate($date) {
    if(!isset($date) && strtotime($date) === false){
	return false;
    }
    return true;
}

function evalGet($var) {
    $year = (int) $var['y'];
    $month = (int) $var['m'];
    if($year != 0 && $month != 0){
	return new Calendar($var['m'], $var['y']);
    }
    if($month != 0){
	return new Calendar($var['m']);
    }
    return new Calendar();
}

$calendar = evalGet($_GET);

if(isset($_GET['date']) && isDate($_GET['date'])){
    $date = strtotime($_GET['date']);
}else{
    $result = safeQuery(
	    'SELECT
		startTime, endTime, `name`,
		upcomingEvents.creatorID AS "Creator",
		upcomingEvents.participants AS "PartID",
		user__overview.username AS "Student",
		course__student.studentID AS "ClassStudent"
	FROM upcomingEvents
	LEFT JOIN eventParticipants
	ON upcomingEvents.participants = eventParticipants.id
	LEFT JOIN course__student
	ON
		(eventParticipants.`value` = course__student.classID
		AND
		eventParticipants.`type` = "c")
	LEFT JOIN user__overview
	ON
		(eventParticipants.`value` = user__overview.id
		AND
		eventParticipants.`type` = "p")
	WHERE
		(MONTH(startTime) =' . $cal->getMonth() . '
		AND
		YEAR(startTime) =  ' . $cal->getYear() . ')
		AND
		(not private OR (private AND creatorID = ' . $_SESSION['studentId'] . '));'
    );

    $RandCol = Array('green', 'red', 'blue', 'yellow', 'orange', 'grey');

    if(mysql_num_rows($result) != 0){
	$eventID = 0;
	while($row = mysql_fetch_row($result)){
	    global $RandCol;
	    $start = strtotime($row[0]);
	    $end = strtotime($row[1]);
	    for($i = $start; $i <= $end; $i += $oneDayInSec){
		$cal->markDate(date('j', $i), $RandCol[$eventID % count($RandCol)]);
	    }
	    $eventID++;
	}
    }
}

$HTML = new HTMLGenertator\HTMLfile('Testpage', ['table.css', 'calendar.css', 'form.css']);
$HTML->outputHeader();
if(!isset($date)){
    ?>
    <h1>Calendar of <?php echo $cal->getMonthName(); ?></h1>
    <form method="GET">
        Month:
        <select name="m">
	    <?php
	    for($i = 1; $i < 13; $i++){
		$monthName = date('F', mktime(0, 0, 0, $i, 10));
		$selected = '';
		if($i == $cal->getMonth()){
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
	    for($i = date('Y') - 1; $i > 1970; $i--){
		$selected = '';
		if($i == $cal->getYear()){
		    $selected = 'selected="selected"';
		}
		echo "<option value=\"$i\" $selected> $i</option>";
	    }
	    ?>
        </select>
        <button type="submit">Set</button>
    </form>
    <?php
    $cal->output();
}else{
    echo '<h1>Eventlist for the ' . $_GET['date'] . '</h1>';
    echo '<a href="?">Get back</a>';
    $sqlDate = date('Y-m-d', $date);
    $result = safeQuery(
	    "SELECT upcomingEvents.id FROM upcomingEvents
            LEFT JOIN eventParticipants
            ON upcomingEvents.participants = eventParticipants.id
            LEFT JOIN course__student
            ON (eventParticipants.`value` = course__student.classID AND eventParticipants.`type` = 'c')
            LEFT JOIN user__overview
            ON (eventParticipants.`value` = user__overview.id AND eventParticipants.`type` = 'p')
            WHERE (\"" . $sqlDate . "\" BETWEEN DATE(startTime) AND DATE(endTime))
            AND (not private OR (private AND creatorID = " . $_SESSION['studentId'] . "));");
    if(mysql_num_rows($result) != 0){
	while($row = mysql_fetch_row($result)){
	    $event = new EventClass($row[0]);
	    echo '<h2>' . $event->getName() . '</h2><div class="twoCols">';
	    echo 'From: ' . $event->getStart() . '<br />';
	    echo 'To: ' . $event->getEnd() . '</div>';
	    echo $event->getDescription();
	}
    }else{
	echo 'Today there is nothing happening.';
    }
    ?>
    <?php
}
$HTML->outputFooter();
?>


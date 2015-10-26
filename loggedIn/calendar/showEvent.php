<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/EventClass.php';

$HTML = new HTMLGenertator\HTMLfile('List of events', ['form.css'], NULL, NULL, 1);
$HTML->outputHeader();

//Check for get variable
if (!isset($_GET['date'])) {
    Header('Location: index.php');
}

//Convert to timestamp
$datestamp = strtotime($_GET['date']);
if ($datestamp === false || $datestamp == -1) {
    Message::castMessage('Invalid dateformat', false, 'index.php');
}

//peparing datestamp
$stamp = 'm=' . date('m', $datestamp) . '&y=' . date('Y', $datestamp);
?>

<h1>Eventlist for the <?php echo date('d.m.Y', $datestamp); ?></h1>
<a href="index.php?<?php echo $stamp ?>">Get back</a><br />
<?php
$sqlDate = date('Y-m-d', $datestamp);
$result = safeQuery(
	"SELECT DISTINCT
	    event__upcoming.id
	FROM event__upcoming
	LEFT JOIN event__participants
	    ON event__upcoming.participants = event__participants.id
	LEFT JOIN course__student
	    ON (event__participants.`value` = course__student.classID AND event__participants.`type` = 'c')
	LEFT JOIN user__overview
	    ON (event__participants.`value` = user__overview.id AND event__participants.`type` = 'p')
	WHERE
	    (\"" . $sqlDate . "\" BETWEEN DATE(startTime) AND DATE(endTime))
	    AND (not private OR (private AND creatorID = " . $_SESSION['studentId'] . "));");
if (mysql_num_rows($result) != 0) {
    while ($row = mysql_fetch_row($result)) {
	$event = new EventClass($row[0]);
	echo '<h2>' . $event->getName() . '</h2><div class="twoCols">';
	echo 'From: ' . $event->getStart() . '<br />';
	echo 'To: ' . $event->getEnd() . '</div>';
	echo $event->getDescription();
    }
} else {
    echo 'Today there is nothing happening.';
}

$HTML->outputFooter();
?>
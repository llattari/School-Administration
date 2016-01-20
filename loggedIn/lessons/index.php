<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/ClassClass.php';
require_once '../../webdev/php/Classes/ClassPerson.php';
require_once '../../webdev/php/Classes/Lesson.php';

#Initing objects
$HTML = new HTMLGenertator\HTMLfile('Your class', ['table.css', 'form.css', 'lesson.css'], NULL, NULL, 1);
$lesson = new Lesson($_SESSION['studentId']);

$HTML->outputHeader();
if (!$lesson->lessonToday()) {
    ?>
    <h1>No lesson</h1>
    <p>You have the day off. Enjoy it. :D </p>
    <?php
    $HTML->outputFooter();
    return;
} else if ($lesson->takesPlace()) {
    echo '<h1>Your current lesson</h1>';
} else {
    $timeString = timeString($lesson->getTimeToStart());
    echo '<h1>Your next lesson is in ' . $timeString . '</h1>';
}
?>
<p class="twoCols">
    Name: <?php echo $lesson->getClassName(); ?>
    by <?php echo ClassPerson::staticGetName($lesson->getTeacherId(), $_SESSION['nickName']); ?>
    <br />
    Takes place at: <?php echo $lesson->getLocation(); ?><br />
    Starts: <?php echo $lesson->getStartingTime(); ?> o'clock <br />
    Ends: <?php echo $lesson->getEndingTime(); ?> o'clock
</p>
<h2>Student list</h2>
<?php
if ($_SESSION['teacher']) {
    ?>
    <form method="POST" class="center" action="setAttendence.php">
        <!-- Hidden fields -->
        <input type="hidden" value="<?php echo $lesson->getId() ?>" name="lessonId" />
        Topic: <input type="text" placeholder="Set topic of the lesson" id="lessonTopic" />
    <?php } ?>
    <table>
	<?php
	//Making the table head
	$cols = Array('Name');
	if ($_SESSION['teacher']) {
	    $cols = array_merge($cols, Array('Attending', 'Homework'));
	}
	$tableHead = array_push($cols, 'Mail');
	echo generateTableHead($tableHead);
	//Querying all the students
	$result = safeQuery(
		'SELECT
		    status,
		    user__overview.id AS "id",
		    CONCAT(`name`, " ", surname) AS "name"
		FROM
		    course__student
		JOIN user__overview
		    ON user__overview.id = course__student.studentID
		WHERE
		    classID = ' . $lesson->getClassId() . '
		ORDER BY
		    status DESC, surname ASC;');
	//Outputting them in a table
	$img = '<img src="' . getRootURL('../webdev/images/mail.png') . '" title="Mailsymbol" style="height:1em"/>';
	while ($row = mysql_fetch_assoc($result)) {
	    $id = $row['id'];
	    echo '<tr>';
	    echo '<td><a href="' . getRootURL('profile/profile.php') . '?id=' . $id . '">' . $row['name'] . '</a></td>';
	    if ($_SESSION['teacher']) {
		if ($row['status'] == 't') {
		    echo '<td></td><td></td>';
		} else {
		    echo
		    '<td><input type="checkbox" checked="checked" name="attend[]" value="' . $id . '" /></td>
		    <td><input type="checkbox" checked="checked" name="homework[]" value="' . $id . '" /></td>';
		}
	    }
	    echo '<td>';
	    if ($_SESSION['studentId'] != $id) {
		echo "<a href=\"../mails/write.php?receiver=$id\">$img</a>";
	    } else {
		echo 'X';
	    }
	    echo '</td></tr>';
	}
	?>
    </table>
    <?php if ($_SESSION['teacher']) { ?>
        <br />
        <button type="submit">Submit changes</button>
    </form>
    <?php
}
$HTML->outputFooter();
?>
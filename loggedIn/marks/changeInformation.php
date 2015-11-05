<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';

$HTML = new HTMLGenertator\HTMLfile('Enter score for a test', ['form.css'], NULL, NULL, 1);
$HTML->outputHeader();

//Evaluating post request
if (isset($_POST['topic'])) {
    $id = intval($_POST['testId']);
    $topic = escapeStr($_POST['topic']);
    $dateWritten = date('Y-m-d', strtotime($_POST['dateWritten']));
    $taskCount = intval($_POST['taskCount']);
    $maxScore = intval($_POST['maxScore']);
    $updateTestInformation = safeQuery("UPDATE course__tests SET topic='$topic', dateWritten=\"$dateWritten\" WHERE id=$id;");
    $insertScoreInformation = safeQuery("INSERT INTO marks__test VALUES ($id, $maxScore, $taskCount) ON DUPLICATE KEY UPDATE maxScore=$maxScore, taskCount=$taskCount;");
    if ($updateTestInformation && $insertScoreInformation) {
	Message::castMessage('Sucessfully updated information.', true, 'enterScore.php?id=' . $id);
    } else {
	Message::castMessage('Failed to update the information.', true, 'changeInformation.php?id=' . $id);
    }
}

//Evaluating id and providing input fields
$id = (isset($_GET['id'])) ? intval($_GET['id']) : 0;
$result = safeQuery(
	'SELECT topic, dateWritten, maxScore, taskCount
	FROM course__tests
	LEFT JOIN marks__test
	ON course__tests.id = marks__test.id
	WHERE course__tests.id=' . $id . ';');
if (mysql_num_rows($result) == 1) {
    $row = mysql_fetch_assoc($result);
} else {
    Message::castMessage('Test not found', false, 'setMarks.php');
}
?>
<form method="POST">
    <input type="hidden" value="<?php echo $id; ?>" name="testId" />
    <fieldset>
	<legend>General information</legend>
	<label for="topic">Topic:</label>
	<input type="text" name="topic" maxlength="300" placeholder="Topic" value="<?php echo $row['topic']; ?>" />
	<br />
	<label for="topic">Date written:</label>
	<input type="date" name="dateWritten" placeholder="Date written" value="<?php echo $row['dateWritten']; ?>" title="YYYY-MM-DD"/>
    </fieldset>
    <fieldset>
	<legend>Score information</legend>
	<label for="taskCount">Number of tasks:</label>
	<input type="number" min="0" max="127" placeholder="Number of tasks" name="taskCount" value="<?php echo $row['taskCount'] ?>"/>
	<br />
	<label for="maxScore">Maximum number of points:</label>
	<input type="number" min="0" placeholder="Max Score" name="maxScore" value="<?php echo $row['maxScore'] ?>"/>
    </fieldset>
    <button type="submit">Save</button>
    <button type="reset">Discard</button>
</form>

<?php
$HTML->outputFooter();
?>
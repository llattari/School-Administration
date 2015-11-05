<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/Messages.php';

$HTML = new HTMLGenertator\HTMLfile('View Marks', ['form.css', 'marks.css'], NULL, NULL, 1);
$HTML->outputHeader();

if (isset($_POST['topic'])) {
    $id = intval($_POST['classId']);
    $topic = escapeStr($_POST['topic']);
    $date = strtotime($_POST['dateWritten']);
    $taskCount = intval($_POST['taskCount']);
    $maxScore = intval($_POST['maxScore']);
    if ($date < time()) {
	Message::castMessage('Invalid date. Can\'t enter a date in the past', false, 'insertTest.php?classId=' . $id);
    }
    $sqlDate = date('Y-m-d', $date);
    $insertTest = safeQuery("INSERT INTO course__tests(classId, topic, dateWritten) VALUES($id, '$topic',\"$sqlDate\");");
    $insertScore = safeQuery("INSERT INTO marks__test(maxScore, taskCount) VALUES($maxScore, $taskCount);");
    if ($insertTest && $insertScore) {
	Message::castMessage('Sucessfully created the test.', true, 'setMarks.php');
    } else {
	Message::castMessage('The test was not created. Please check your information', false, 'setMarks.php');
    }
}

$classId = isset($_GET['classId']) ? intval($_GET['classId']) : 0;
if ($classId == 0) {
    Message::castMessage('Please select a course first', false, 'setMarks.php');
}
?>
<h1>Adding a new test</h1>
<form method="POST">
    <fieldset>
	<input type="hidden" value="<?php echo $classId; ?>" name="classId"/>
	<legend>General information</legend>
	<label for="topic">Topic: </label><input type="text" maxlength="300" placeholder="Topic" name="topic">
	<br />
	<label for="dateWritten">Date: </label><input type="datetime" placeholder="YYYY-MM-DD" name="dateWritten">
    </fieldset>
    <fieldset>
	<legend>Score information</legend>
	<label for="taskCount">Number of tasks:</label>
	<input type="number" min="0" max="127" placeholder="Number of tasks" name="taskCount" value="0"/>
	<br />
	<label for="maxScore">Maximum number of points:</label>
	<input type="number" min="0" placeholder="Max Score" name="maxScore" value="0"/>
    </fieldset>
    <button type="submit">Create</button>
    <button type="reset">Dismiss</button>
</form>

<?php
$HTML->outputFooter();
?>

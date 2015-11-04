<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/Messages.php';

$HTML = new HTMLGenertator\HTMLfile('View Marks', ['form.css', 'marks.css'], NULL, NULL, 1);
$HTML->outputHeader();

if (isset($_POST['topic'])) {
    $id = intval($_POST['classId']);
    $topic = escapeStr($_POST['topic']);
    $date = strtotime($_POST['dateWritten']);
    if ($date < time()) {
	Message::castMessage('Invalid date. Can\'t enter a date in the past', false, 'insertTest.php?classId=' . $id);
    }
    $sqlDate = date('Y-m-d', $date);
    echo "INSERT INTO course__tests(classId, topic, dateWritten) VALUES($id, '$topic',\"$sqlDate\");";
    $insert = safeQuery("INSERT INTO course__tests(classId, topic, dateWritten) VALUES($id, '$topic',\"$sqlDate\");");
    if ($insert) {
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
    <button type="submit">Create</button>
    <button type="reset">Dismiss</button>
</form>

<?php
$HTML->outputFooter();
?>

<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/Messages.php';
require_once '../../webdev/php/Classes/ClassPerson.php';

$HTML = new HTMLGenertator\HTMLfile('Enter score for a test', ['marks.css', 'form.css'], NULL, NULL, 1);
$HTML->outputHeader();
if (!isset($_GET['id'])) {
    Message::castMessage('Please select a test first.', false, 'setMarks.php');
}
$id = intval($_GET['id']);
if ($id == 0) {
    Message::castMessage('Invalid Id.', false, 'setMarks.php');
}
//Fetching the information
$result = safeQuery('SELECT taskCount, maxScore FROM marks__test WHERE id=' . $id . ';');
if (mysql_num_rows($result) == 0) {
    Message::castMessage('Invalid id', false, 'setMarks.php');
} else {
    $row = mysql_fetch_assoc($result);
    $taskCount = $row['taskCount'];
    $maxScore = $row['maxScore'];
}
?>
<form action="submitMarks.php" method="POST">
    <input type="hidden" value="<?php echo $id; ?>" name="id" />
    <fieldset>
	<legend>Test settings</legend>
	Changing the test settings will discard all the changes that are not saved on this sheet.
	<br />
	<a href="changeInformation.php?id=<?php echo $id; ?>">Change test information</a>

    </fieldset>
    <fieldset>
	<legend>Marks</legend>
	<table>
	    <thead>
	    <th>Id</th>
	    <?php
	    for ($i = 1; $i <= $taskCount; $i++) {
		echo '<th>Task ' . $i . '</th>';
	    }
	    ?>
	    <th>Sum</th>
	    </thead>
	    <tbody>
		<?php
		$students = safeQuery('SELECT * FROM marks__points WHERE testId=' . $id . ' ORDER BY studentId, task;');
		$allStudents = safeQuery('
		SELECT studentID
		FROM course__student
		LEFT JOIN course__tests
		ON course__student.classID = course__tests.classId
		WHERE course__tests.id = ' . $id . ';');
		$markArray = Array();
		while ($student = mysql_fetch_array($allStudents)) {
		    $markArray[$student[0]] = Array();
		}
		while ($row = mysql_fetch_assoc($students)) {
		    $sId = intval($row['studentId']);
		    if (!isset($markArray[$sId])) {
			$markArray[$sId] = Array();
		    }
		    array_push($markArray[$sId], $row['score']);
		}
		foreach ($markArray as $key => $value) {
		    $sum = 0;
		    echo '<tr>';
		    echo '<td>' . ClassPerson::staticGetName($key) . '</td>';
		    for ($i = 0; $i < $taskCount; $i++) {
			$outScore = isset($value[$i]) ? $value[$i] : 0;
			$sum += $outScore;
			echo '<td><input type="number" value="' . $outScore . '" min="0" name="i' . $key . 't' . ($i + 1) . '" /></td>';
		    }
		    $percent = $sum / $maxScore * 100;
		    $colored = '';
		    if ($percent > 100) {
			$colored = 'class="red"';
		    } else if ($percent < 100) {
			$colored = 'class="yellow"';
		    } else {
			$colored = 'class="green"';
		    }
		    echo"<td $colored>" . $sum . ' p.<br />(' . roundUp($percent, 1) . '%)</td></tr>';
		}
		?>
	    </tbody>
	</table>
    </fieldset>
    <fieldset>
	<legend>Save and discard</legend>
	<button type="submit" title="You can also hit enter in order to save the changes.">Save</button>
	<button type="reset">Don't save</button>
    </fieldset>
</form>
<?php
$HTML->outputFooter();
?>
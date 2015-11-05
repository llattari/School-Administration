<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';

$HTML = new HTMLGenertator\HTMLfile('View Marks', ['form.css', 'marks.css'], NULL, NULL, 1);
$HTML->outputHeader();
$classId = isset($_GET['classId']) ? intval($_GET['classId']) : 0;
echo '<h1>Adding a mark</h1>';
if ($classId == 0) {
    echo 'Please choose a class that you want to edit: ';
} else {
    echo 'You are currently editing: ';
}

//Offering the user to select a course
echo '<form style="display: inline"><select name="classId">';
$result = safeQuery('SELECT id, CONCAT(subject," (", type, ")") FROM course__overview WHERE teacherID = ' . $_SESSION['studentId']);
while ($row = mysql_fetch_row($result)) {
    $selected = ($row[0] == $classId) ? 'selected' : '';
    echo '<option value="' . $row[0] . '" ' . $selected . '>' . $row[1] . "</option>\n";
}
echo '</select>
    <button type="submit">Go</button>
    </form>';

//Offering the user to chose a test that they want to edit
if ($classId != 0) {
    echo '<h2>Tests in this course</h2>
	<ul>';
    $tests = safeQuery('SELECT id, topic FROM course__tests WHERE classId = ' . $classId . ' ORDER BY dateWritten;');
    $boldIfNotComplete = (mysql_num_rows($tests) < 4) ? 'class="bold"' : '';
    while ($testRow = mysql_fetch_array($tests)) {
	echo '<li><a href="enterScore.php?id=' . $testRow[0] . '">' . $testRow[1] . '</a></li>';
    }
    echo '<li><a href="insertTest.php?classId=' . $classId . '" ' . $boldIfNotComplete . '>Create a new test</a></li>';
    echo '</ul>';
}
$HTML->outputFooter();
?>

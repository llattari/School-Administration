<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/ClassPerson.php';
require_once '../../webdev/php/Forum/Section.php';

$HTML = new HTMLGenertator\HTMLfile('Forum', ['form.css', 'forum.css'], NULL, NULL);
$HTML->outputHeader();
?>
<h1>Select the forum</h1>
<form action="readForum.php" method="GET">
    Select a course for the forum:
    <select name="forumId">
	<?php
	$result = safeQuery(
		'SELECT
		      classID, course__overview.subject
		FROM course__student
		JOIN course__overview
		ON course__overview.id = course__student.classID
		WHERE studentID = ' . $_SESSION['studentId'] . ';');
	while ($row = mysql_fetch_row($result)) {
	    echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
	}
	?>
    </select>
    <br />
    <button type="submit">Select</button>
</form>
<?php
$HTML->outputFooter();
?>

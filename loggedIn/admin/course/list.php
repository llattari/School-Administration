<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../../webdev/php/Generators/tableGenerator.php';

$HTML = new \HTMLGenertator\HTMLfile('List courses', ['todo.css', 'form.css', 'table.css'], ['checkForm.js'], NULL, 2);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();

$showAll = isset($_GET['showAll']);
?>
<h1>List of all courses</h1>
<hr />
<form method="GET" class="twoCols center">
    <label><input type="checkbox" value="showAll" name="showAll" <?php echo ($showAll) ? 'checked' : '' ?> /> Also show inactive</label>
    <br />
    <button type="submit">Filter</button>
</form>
<hr />
<table>
    <?php
    $condition = ($showAll) ? '1=1' : 'active = true';
    // SQL Querry
    $result = safeQuery('
	SELECT
	    CONCAT("<a href=\"change.php?id=", course__overview.id, "\">", course__overview.id, "</a>") AS "ID",
	    CONCAT(user.name, " ", user.surname) AS "Teacher",
	    CONCAT(subject, " (", type, ")") AS "Subject",
	    course__overview.grade AS "Grade",
	    active
	FROM course__overview
	JOIN user__overview user
	ON teacherId = user.id
	WHERE ' . $condition . ';
    ');
    if (mysql_num_rows($result) > 0) {
	echo generateTableHead(getFields($result, false));
	while ($row = mysql_fetch_assoc($result)) {
	    $color = ($row['active']) ? "done" : "unDone";
	    unset($row['active']);
	    echo generateTableRow(array_values($row), 'class="' . $color . '"');
	}
    } else {
	echo '<tr><td colspan="' . $numFields . '">No courses matched your filter.</td></tr>';
    }
    ?>
</table>
<?php
$HTML->outputFooter();
?>


<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../../webdev/php/Generators/tableGenerator.php';

$HTML = new \HTMLGenertator\HTMLfile('List courses', ['form.css', 'table.css'], ['checkForm.js'], NULL, 2);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();
?>
<form method="GET">
    <label><input type="checkbox" value="showAll" name="showAll"/> Also show inactive</label>
    <button type="submit">Filter</button>
</form>
<table>
    <?php
// SQL Querry
    $result = safeQuery('
	SELECT
	    course__overview.id AS "ID",
	    CONCAT(user.name, " ", user.surname) AS "Teacher",
	    CONCAT(subject, " (", type, ")") AS "Subject",
	    course__overview.grade AS "Grade",
	    active
	FROM course__overview
	JOIN user__overview user
	ON teacherId = user.id;
    ');
    if (mysql_num_rows($result) > 0) {
	echo generateTableHead(getFields($result, false));
	while ($row = mysql_fetch_assoc($result)) {
	    $color = ($row['active']) ? "green" : "red";
	    unset($row['active']);
	    echo generateTableRow(array_values($row), 'style="background-color:' . $color . ';"');
	}
    } else {
	echo '<tr><td colspan="' . $numFields . '">No courses matched your filter.</td></tr>';
    }
    ?>
</table>
<?php
$HTML->outputFooter();
?>


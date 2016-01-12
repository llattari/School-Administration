<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../../webdev/php/Generators/tableGenerator.php';

$HTML = new \HTMLGenertator\HTMLfile('List courses', ['todo.css', 'form.css', 'table.css'], ['checkForm.js'], NULL, 2);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();
?>
<!-- Providing a filter for the courses -->
<h1>List of all courses</h1>
<hr />
<form method="GET" class="twoCols center">
    Type: <select name="courseType">
	<option value="G" <?php echo (isset($_GET['courseType']) && $_GET['courseType'] == 'G') ? 'selected' : ''; ?>>O-Level</option>
	<option value="L" <?php echo (isset($_GET['courseType']) && $_GET['courseType'] == 'L') ? 'selected' : ''; ?>>A-Level</option>
    </select>
    <br />
    Grade: <select name="grade">
	<?php
	for ($i = 1; $i < 13; $i++) {
	    $selected = (isset($_GET['grade']) && $_GET['grade'] == $i) ? 'selected' : '';
	    echo '<option value="' . $i . '" ' . $selected . '>Grade ' . $i . '</li>';
	}
	?>
    </select>
    <br />
    <label><input type="checkbox" value="showAll" name="showAll" <?php echo ($showAll) ? 'checked' : '' ?> /> Also show inactive</label>
    <br />
    <button type="submit">Filter</button>
</form>
<hr />
<table>
    <?php
    $conditions = Array();
    if (!isset($_GET['showAll'])) {
	array_push($conditions, 'active = true');
    }
    if (isset($_GET['courseType'])) {
	array_push($conditions, 'type = "' . escapeStr($_GET['courseType']) . '"');
    }
    if (isset($_GET['grade'])) {
	array_push($conditions, 'course__overview.grade = ' . escapeStr($_GET['grade']));
    }
    $sqlCondition = implode(' AND ', $conditions);
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
	WHERE ' . $sqlCondition . ';
    ');
    // Outputting the result
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


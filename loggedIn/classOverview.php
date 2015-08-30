<?php
require_once '../webdev/php/Generators/HTMLGenerator/Generator.php';

$sqlClasses = '
	SELECT
	    course__student.classID AS "id",
	    abbr, subject,
	    done AS "HW done"
	FROM
	    course__overview
	JOIN course__student ON
	    course__overview.id = course__student.classID
	LEFT OUTER JOIN task__toDo ON
	    task__toDo.classID = course__overview.id
	WHERE
	    course__student.studentID = ' . $_SESSION['studentId'] . '
	GROUP BY subject;';

$HTML = new HTMLGenertator\HTMLfile('Class Overview');
$HTML->outputHeader();
?>
<h1>Class Overview</h1>
<ul class="twoCols">
    <?php
    $result = safeQuery($sqlClasses);
    while($row = mysql_fetch_assoc($result)){
	echo '<li>';
	echo '<a href="class.php?classID=' . $row['id'] . '">' . $row['subject'] . ' (' . $row['abbr'] . ')</a>';
	if(!is_null($row['HW done'])){
	    echo ' - <a href="toDo.php">You haven\'t done your homework, yet.</a>';
	}
	echo '</li>';
    }
    ?>
</ul>
<?php
$HTML->outputFooter();
?>

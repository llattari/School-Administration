<?php
//Including everthing
require_once '../webdev/php/Classes/Mark.php';
require_once '../webdev/php/Classes/ClassClass.php';
require_once '../webdev/php/Generators/HTMLGenerator/Generator.php';

use MarkAdministration;

//Setting variables
$week = Array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
$HTML = new HTMLGenertator\HTMLfile('Classes', ['table.css'], ['selectionToggle.js']);

$HTML->outputHeader();
if(isset($_GET['classID']) && intval($_GET['classID'])){
    $cID = $_GET['classID'];
    $class = new StudentClass($cID);
    if($class->isValid()){
	$marks = new MarkAdministration\Subject($_SESSION['studentId'], $cID);
	echo '<h1>Information about the course: ' . $class . '</h1>';
	?>
	<br />
	<?php
	echo 'Teacher: ' . $class->getTeacher() . '<br />';
	echo 'Student-count: ' . $class->getMemberCount();
	?>
	<h2>Lesson</h2>
	<ul>
	    <?php
	    global $table;
	    $resultLesson = safeQuery('SELECT `day`, lesson, room FROM timetable__overview WHERE classID=' . $cID . ' ORDER BY `day`, lesson');
	    while($row = mysql_fetch_assoc($resultLesson)){
		echo '<li>' . $row['lesson'] . '. lesson: ' . $week[$row['day'] - 1] . ' in ' . $row['room'] . '</li>';
	    }
	    echo '</ul><br />';
	    $res = safeQuery('SELECT COUNT(id) FROM task__toDo WHERE studentId = ' . $_SESSION['studentId'] . ' AND classID = ' . $cID . ' AND done = false;');
	    $counter = mysql_fetch_row($res);
	    //Listing the homeworks
	    echo '<h2>Homework (' . $counter[0] . ')</h2>';
	    echo '<ul>';
	    $result = safeQuery('SELECT done, deadline, content FROM task__toDo WHERE studentId = ' . $_SESSION['studentId'] . ' AND classID = ' . $cID . ' ORDER BY done, deadline ASC LIMIT 20;');
	    if(mysql_num_rows($result) == 0){
		echo '<li>No homework to do.</li>';
	    }
	    while($row = mysql_fetch_assoc($result)){
		$done = ($row['done']) ? 'done' : 'toDo';
		$date = date('D, d.m.', strtotime($row['deadline']));
		echo "<li class=\"$done\">[$date] " . $row['content'] . '</li>';
	    }
	    echo
	    '</ul><br/>
	    <h2>Marks</h2>
	    <br />';
	}else{
	    echo
	    '<h1>ERROR while loading page.</h1>
			The requested class does not exist';
	}
    }else{
	echo
	'<h1>ERROR while loading page.</h1>
		The requested site is not available';
    }
    $HTML->outputFooter();
    ?>
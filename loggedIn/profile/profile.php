<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/Settings.php';
require_once '../../webdev/php/Classes/ClassPerson.php';
require_once '../../webdev/php/Classes/ClassClass.php';

$HTML = new HTMLGenertator\HTMLfile('Your Profile', NULL, ['expandList.js'], NULL, 1);
$HTML->outputHeader();

if (isset($_GET['id']) && (intval($_GET['id']) != 0)) {
    $person = new ClassPerson(intval($_GET['id']));
} else {
    $person = new ClassPerson($_SESSION['studentId']);
}

$name = $person->getName();

if ($person->isValid()) {
    $status = $person->getStatus();
    ?>
    <h1>Profile information</h1>
    <?php
    $settings = new Settings();
    if ($settings->getProfile) {
	echo '<img src="' . $person->getPicURL() . '" heigth="100px" title="Profile picture of ' . $name[2] . '/>';
    }
    ?>
    <ul>
        <li>
    	Name: <?php echo $name[0] . ' ' . $name[1] . '<br />known as: ' . $name[2]; ?>
        </li>
        <li>
    	Birthday: <?php echo date('d.m.Y', $person->getBDate()); ?>
        </li>
        <li>
    	Status:
	    <?php
	    if ($status == 't') {
		echo 'Teacher';
	    } else {
		echo 'Student';
		echo '</li><li>';
		$grade = $person->getGrade();
		if (!is_null($grade)) {
		    echo 'Grade: ' . $grade;
		} else {
		    echo 'This student is no longer in school';
		}
	    }
	    ?>
        </li>
        <li>
    	Takes part in:
    	<ul>
		<?php
		if ($status == 't') {
		    $result = safeQuery(
			    'SELECT
					id, grade, subject, active
				FROM course__overview
				WHERE teacherID = ' . $_SESSION['studentId'] . '
				ORDER BY active DESC;');
		    $active = true;
		    while ($row = mysql_fetch_assoc($result)) {
			if ($row['active'] != $active) {
			    echo '</ul><ul id="no" style="display:none;">';
			    $active = false;
			}
			echo '<li><a href="class.php?classID=' . $row['id'] . '">Grade: ' . $row['grade'] . ': ' . $row['subject'] . '</a></li>';
		    }
		    echo ($active ? '' : 'The red marked courses are inactive.');
		} else {
		    $result = safeQuery('SELECT classID FROM course__student WHERE studentId=' . $_SESSION['studentId'] . ';');
		    while ($row = mysql_fetch_row($result)) {
			$newClass = new StudentClass($row[0]);
			echo $newClass . '<br />';
		    }
		}
		?>
    	</ul>
    	<a onclick="toggle(this)">Show all</a>
        </li>
    </ul>
    <?php
} else {
    echo '<h1>Profile not found</h1>';
}
$HTML->outputFooter();
?>
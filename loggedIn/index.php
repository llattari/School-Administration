<?php
require_once '../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../webdev/php/Classes/Overview.php';

$HTML = new HTMLGenertator\HTMLfile('Overview', ['overview.css'], ['toggleElement.js']);
$overview = new Overview($_SESSION['studentId']);
$HTML->outputHeader();
?>
<a href="#" title="Displays the next lesson">
    <div id="lesson" class="card" onContextMenu="return toggleVisibility(this);">
	<h1>Next Lesson</h1>
	<p>
	    <?php
	    $nextLesson = $overview->getNextLesson();
	    if ($nextLesson != NULL) {
		echo "You're next lesson is: " . $nextLesson[0] . '(' . $nextLesson[1] . ')<br />
		It takes place in: "' . $nextLesson[3] . '" at ' . date('H:i', strtotime($nextLesson[2]));
	    } else {
		echo 'No more lessons today.';
	    }
	    ?>
	</p>
    </div>
</a>
<a href="#">
    <div id="homework" class="card" onContextMenu="return toggleVisibility(this);">
	<h1>To-Do</h1>
	<p>English for tomorrow and two other important tasks.</p>
    </div>
</a>
<a href="#">
    <div id="news" class="card" onContextMenu="return toggleVisibility(this);">
	<h1>Important news</h1>
	<p>
	<ul>
	    <?php
	    $news = $overview->getImportantNews();
	    for ($i = 0; $i < count($news); $i++) {
		echo '<li>' . $news[$i] . '</li>';
	    }
	    ?>
	</ul>
	</p>
    </div>
</a>
<a href="#">
    <div id="friends" class="card" onContextMenu="return toggleVisibility(this);">
	<h1>Friends</h1>
	<p>No special activity.</p>
    </div>
</a>
<a href="#">
    <div id="friends" class="card" onContextMenu="return toggleVisibility(this);">
	<h1>Marks</h1>
	<p>A teacher has entered a new mark in Arts.</p>
    </div>
</a>
<a href="lessons/timetable.php">
    <div id="friends" class="card" oncontextmenu="return toggleVisibility(this);">
	<h1>Timetable</h1>
	<p>See your timetable.</p>
    </div>
</a>
<br style="clear: both" />
<p>
    Right click to minimize.
</p>
<?php
$HTML->outputFooter();
?>
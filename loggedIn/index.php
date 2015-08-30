<?php
require_once '../webdev/php/Generators/HTMLGenerator/Generator.php';

$HTML = new HTMLGenertator\HTMLfile('Overview', ['overview.css'], ['toggleElement.js']);
$HTML->outputHeader();
?>
<a href="#" title="Displays the next lesson">
    <div id="lesson" class="card" onContextMenu="return toggleVisibility(this);">
        <h1>Next Lesson</h1>
        <p>
            Maths in room A13.
	    <br />
            Starts at 9:30 pm.
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
        <p>No lesson off today.</p>
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
<a href="timetable.php">
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
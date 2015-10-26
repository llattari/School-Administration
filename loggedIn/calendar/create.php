<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Generators/timeSelector.php';

$HTML = new HTMLGenertator\HTMLfile('Calendar', ['form.css'], ['checkForm.js'], NULL, 1);
$HTML->outputHeader();
?>

<h1>Create a new event</h1>
<form action="createEvent.php" method="POST" onsubmit="return checkEvent()">
    <input type="hidden" value="<?php echo $_SESSION['studentId']; ?>" name="studentId" />
    <fieldset>
	<legend>General information</legend>
	<input type="text" name="eventName" placeholder="Name of the event" />
	<br />
	<textarea name="description">Describe the event</textarea>
	<br />
	<label>
	    <input type="checkbox" name="private" value="private" checked="checked"/>
	    Is this event private?
	</label>
    </fieldset>
    <fieldset>
	<legend>Times</legend>
	Start: <input type="text" name="start" value="<?php echo date('d.m.Y'); ?>" />
	Time: <?php echo timeSelector('start'); ?>
	<br />
	End: <input type="text" name="end" value="<?php echo date('d.m.Y', time() + 24 * 3600); ?>" />
	Time: <?php echo timeSelector('end'); ?>
    </fieldset>
    <fieldset>
	<legend>Submit</legend>
	<button type="submit">Create</button>
	<button type="reset">Discard</button>
    </fieldset>
</form>
<?php
$HTML->outputFooter();
?>
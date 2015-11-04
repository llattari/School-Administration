<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';

$HTML = new \HTMLGenertator\HTMLfile('Change personal information', ['form.css'], NULL, NULL, 1);
$HTML->changeMenuFile(__DIR__ . '/menu.php');
$HTML->outputHeader();
?>

<form>
    <fieldset>
	<legend>Functionality</legend>
	<input type="checkbox" id="chat" /> <label for="chat">Enable Chat</label>
    </fieldset>
    <fieldset>
	<legend>Defaults</legend>

    </fieldset>
    <fieldset>
	<legend>Submit and reset</legend>
	<button type="submit">Submit</button>
	<button type="reset">Reset</button>
    </fieldset>
</form>

<?php
$HTML->outputFooter();
?>
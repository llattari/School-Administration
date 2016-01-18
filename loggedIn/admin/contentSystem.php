<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/Settings.php';

$HTML = new \HTMLGenertator\HTMLfile('Change personal information', ['form.css'], NULL, NULL, 1);
$HTML->changeMenuFile(__DIR__ . '/menu.php');
$HTML->outputHeader();

$settings = new Settings();
$suc = $settings->saveNew($_GET);
$chat = $settings->getChat();
$system = $settings->getUnitSystem();
$profilePic = $settings->getProfilePic();
?>
<form method="GET">
    <fieldset>
	<legend>Functionality</legend>
	<label for="chat"><input type="checkbox" name="chat" <?php echo $chat ? 'checked' : ''; ?>/> Enable Chat</label>
    </fieldset>
    <fieldset>
	<legend>Defaults</legend>
	<select name="system">
	    <option value="i" <?php echo ($system == 'i') ? 'selected' : ''; ?>>Imperial</option>
	    <option value="m" <?php echo ($system == 'm') ? 'selected' : ''; ?>>Metric</option>
	</select>
	system
	<br />
	<label><input type="checkbox" name="profilePic" <?php echo $profilePic ? 'checked' : ''; ?>/> Show profile picture</label>
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
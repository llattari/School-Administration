<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Generators/optionGenerator.php';
require_once '../../webdev/php/Classes/ClassPerson.php';

function isChecked($bool) {
    return $bool ? 'checked="checked"' : '';
}

$HTML = new HTMLGenertator\HTMLfile('Your Profile', ['form.css'], ['expandList.js'], NULL, 1);
$settings = new ClassPerson($_SESSION['studentId']);
$HTML->outputHeader();
?>
<h1>Changing profile settings</h1>

<form action="submitSettings.php" method="POST">
    <fieldset>
        <!-- Showing the student the information that he can't change -->
        <legend>Immutable information</legend>
        Name:
	<?php
	$name = $settings->getName();
	echo $name[0] . ' ' . $name[1] . ' (' . $name[2] . ')';
	?>
        <br />
        Status:
	<?php
	echo $statusArray[$settings->getStatus()];
	if($settings->getStatus() == 's'){
	    echo ' in Grade ' . $settings->getGrade();
	}
	?>
    </fieldset>
    <!-- Allowing the student to change his password -->
    <fieldset>
        <legend>Change password:</legend>
        Old password: <input type="password" name="oldPsw" value="" />
        <br />
        New password: <input type="password" name="newPswOne" value="" />
        <br />
        re-type password: <input type="password" name="newPswTwo" value="" />
    </fieldset>
    <!-- Altering the look and feel of the page -->
    <fieldset>
        <legend>UI Settings</legend>
        <label>
            <input type="checkbox" name="names" <?php echo isChecked($_SESSION['ui']['markNames']) ? 'checked=""' : ''; ?> />
            Should the marks be displayed as words?
        </label>
        <br />
        <label>
            <input type="checkbox" name="dark" <?php echo isChecked($_SESSION['ui']['darkTheme']) ? 'checked=""' : ''; ?> />
            Enable the dark mode of the web-site
        </label>
    </fieldset>
    <button type="submit">Submit changes</button>
    <button type="reset">Reset changes</button>
</form>
<?php
$HTML->outputFooter();
?>

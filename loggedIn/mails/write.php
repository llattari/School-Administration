<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';

$HTML = new HTMLGenertator\HTMLfile('New Mail', ['form.css'], ['checkForm.js'], NULL, 1);
$HTML->outputHeader();
$selectId = (int) $_GET['receiver'];
?>
<h1>New Mail</h1>
<form action="writeMail.php" method="POST" onsubmit="return checkMail();">
    <fieldset>
        <legend>General information</legend>
        <p>
            Subject: <input tye="t ext" name="subject" maxlength="100" placeholder="Subject of the message" />
            <br />
            Recipients: <select type="text" name="receiver" title="Name of the receiver">
		<?php
		$result = safeQuery('SELECT id, UPPER(status), CONCAT(`name`," ",surname), username FROM user__overview WHERE id != ' . $_SESSION['studentId']);
		echo '<option value="0" ' . $selected . '>Please choose a receiver.</option>';
		while($row = mysql_fetch_row($result)){
		    $selected = ($row[0] == $selectId) ? 'selected="selected"' : '';
		    echo '<option value="' . $row[0] . '" ' . $selected . '>[' . $row[1] . '] ' . $row[2] . ' - ' . $row[3] . '</option>';
		}
		?>
            </select>
        </p>
    </fieldset>
    <textarea cols="50" rows="10" name="message">Enter your message here.</textarea>
    <fieldset>
        <legend>Send or discard</legend>
        <button type="reset">Reset</button>
        <button type="submit">Send</button>
    </fieldset>
</form>
<?php $HTML->outputFooter(); ?>
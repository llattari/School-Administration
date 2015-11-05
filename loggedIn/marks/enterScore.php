<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/Messages.php';

$HTML = new HTMLGenertator\HTMLfile('Enter score for a test', ['marks.css'], NULL, NULL, 1);
$HTML->outputHeader();
if (!isset($_GET['id'])) {
    Message::castMessage('Please select a test first.', false, 'setMarks.php');
}
$id = intval($_GET['id']);
if ($id == 0) {
    Message::castMessage('Invalid Id.', false, 'setMarks.php');
}
?>
<p>
    Changing the test settings will discard all the changes that are not saved on this sheet.
    <br />
    <a href="changeInformation.php?id=<?php echo $id; ?>">Change test information</a>
</p>
<form>
    <input type="hidden" value="<?php echo $id; ?>" />
    <table>
	<thead><th>Student</th><th>Task 1</th></thead>
    </table>
</form>
<?php
$HTML->outputFooter();
?>
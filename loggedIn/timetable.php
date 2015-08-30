<?php
require_once '../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../webdev/php/Generators/tableGenerator.php';
require_once '../webdev/php/table.php';

use HTMLGenertator;

$HTML = new HTMLGenertator\HTMLfile('Timetable', ['table.css'], ['dynDays.js']);
$HTML->outputHeader();
?>
<h1>Your Timetable</h1>
<table id="timetable">
    <?php echo generateTableHead(['#', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri']); ?>
    <tbody>
	<?php echo getTimetable() ?>
    </tbody>
</table>
<?php
$HTML->outputFooter();
?>
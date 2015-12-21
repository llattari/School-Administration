<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Generators/tableGenerator.php';
require_once '../../webdev/php/table.php';

$HTML = new HTMLGenertator\HTMLfile('Timetable', ['table.css'], ['dynDays.js']);
$timetable = getTimetable();
$HTML->outputHeader();
?>
<h1>Your Timetable</h1>
<table id="timetable">
    <?php echo generateTableHead(['#', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']); ?>
    <tbody>
	<?php
	for ($i = 0; $i < count($timetable); $i++) {
	    echo '<tr>';
	    for ($j = 0; $j < 6; $j++) {
		$value = $timetable[$i][$j];
		if (empty($value)) {
		    echo '<td class="free">Freetime</td>';
		} else {
		    echo '<td>' . $value . '</td>';
		}
	    }
	    echo '</tr>';
	}
	?>
    </tbody>
</table>
<?php
$HTML->outputFooter();
?>
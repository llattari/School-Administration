<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/ClassToDo.php';
require_once '../../webdev/php/Generators/tableGenerator.php';
require_once '../../webdev/php/Generators/optionGenerator.php';

$HTML = new HTMLGenertator\HTMLfile('To-Do', ['table.css', 'form.css', 'todo.css'], ['selectionToggle.js'], NULL, 1);
$HTML->outputHeader();

$currentStudent = new ClassToDo($_SESSION['studentId']);

$toggleId = (int) $_GET['toggleId'];
if($toggleId != 0){
    $suc = $currentStudent->toggle($toggleId);
    Header('Location: list.php');
}
?>
<div class="left" id="filter">
    <form onsubmit="return false;">
        <fieldset>
            <legend>Filter</legend>
	    <?php
	    echo
	    'Done: ' . generateOption(['Done', 'To-Do'], 'toDoFilter', True) . '<br />' .
	    'Type: ' . generateOption(uniquify($currentStudent->getTypes()), 'typeFilter', True) . '<br />' .
	    'Priority: ' . generateOption($priority, 'priortiyFilter', True) . '<br />' .
	    'Subject: ' . generateOption(uniquify($currentStudent->getSubjects()), 'subjectFilter', True);
	    ?>
            <br />
            <button onclick="filterTable()">Filter</button>
            <button type="reset" onclick="resetFilter()">Reset</button>
        </fieldset>
    </form>
</div>
<div class="right">
    <h1>Tasks</h1>
    <table>
        <!-- Generating the header of the table -->
	<?php echo generateTableHead(['Date', 'Topic', 'Type', 'Priority']); ?>
        <tbody>
            <!-- Generating the body of the table -->
	    <?php echo (string) $currentStudent ?>
        </tbody>
        <tfoot>
            <tr>
		<?php
		if(!$currentStudent->isEmpty()){
		    echo '<td colspan = "4">' . $currentStudent->getSummary() . '</td>';
		}
		?>
            </tr>
        </tfoot>
    </table>
</div>
<br style="clear: both" />
<script>
    saveState();
</script>
<?php $HTML->outputFooter(); ?>
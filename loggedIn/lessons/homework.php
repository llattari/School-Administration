<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';

$HTML = new HTMLGenertator\HTMLfile('Homework', ['form.css'], NULL, NULL, 1);
$HTML->outputHeader();

if ($_SESSION['teacher']) {
    ?>
    <h1>Create a new homework assignment</h1>
    <form method="post" action="createHomework.php">
        <fieldset>
    	<legend>Basic information</legend>
    	<input type="text" name="topic" placeholder="Topic of the homework"/>
    	<textarea name="description">What should they do?</textarea>
        </fieldset>
        <fieldset>
    	<legend>Material (optional)</legend>
    	<ul style="list-style: none;">
    	    <li>Books: <input type="text" name="book" placeholder="Enter the pages of the books seperated by a comma(,)"/></li>
    	    <li>Worksheets: <input type="text" name="worksheet" placeholder="Enter the names of the worksheets seperated by a comma(,)"/></li>
    	    <li>Link: <input type="text" name="link" placeholder="Enter a link"/></li>
    	</ul>
        </fieldset>
        <fieldset>
    	<legend>Submit or not</legend>
    	<button type="submit">Create homework</button>
    	<button type="reset">Discard</button>
        </fieldset>
    </form>
    <?php
} else {
    echo 'You are not a teacher.';
}
$HTML->outputFooter();
?>

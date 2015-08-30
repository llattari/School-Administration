<?php
# Includes
require_once '../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once toGlobalPath('php/Classes/Homework.php');

# Creating the webpage
$currentHW = new Homework($_GET['classID']);
$HTML = new HTMLGenertator\HTMLfile('Homework', ['table.css'], ['selectionToggle.js']);
?>

<h1>Homework</h1>
<?php echo $currentHW->getTopic(); ?>

<h2>Material</h2>
<?php
echo $currentHW->getMaterial();
$HTML->outputFooter();
# todo: mark homework as done
?>
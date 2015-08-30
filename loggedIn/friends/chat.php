<?php
require_once('../../webdev/php/Generators/HTMLGenerator/Generator.php');
$HTML = new HTMLGenertator\HTMLfile('Chat with your friends', ['chat.css'], NULL, NULL, 1);
$HTML->outputHeader();
?>
<h1>Here is the area to chat with your friends</h1>

<?php
$HTML->outputFooter();
?>

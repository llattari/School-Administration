<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
$HTML = new \HTMLGenertator\HTMLfile('Admin pannel', NULL, NULL, NULL, 1);
$HTML->changeMenuFile(__DIR__ . '/menu.php');
$HTML->outputHeader();
?>
<h1>Welcome to the admin pannel</h1>
<p>
    In this area you can change:
</p>
<ul>
    <li>User settings</li>
    <li>Course settings</li>
    <li>Content settings</li>
</ul>
<p>
    You may also check out the mailing functions.
</p>
<?php
$HTML->outputFooter();
?>
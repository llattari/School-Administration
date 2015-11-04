<?php

require_once '../../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../../webdev/php/Generators/tableGenerator.php';
$HTML = new \HTMLGenertator\HTMLfile('Admin pannel', ['table.css'], NULL, NULL, 2);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();

echo '<h1>List of all permissions</h1>';
$result = safeQuery('
	    SELECT
		user__permission.*,
		CONCAT(name, " ", surname, "<br />(", username, ")") AS "Name",
		CONCAT("<a href=\"change.php?id=", user__overview.id, "\">Edit</a>") AS "Edit"
	    FROM user__overview
	    JOIN user__permission
	    ON user__permission.id = user__overview.id
	    ORDER BY status;');
if (mysql_num_rows($result) != 0) {
    $row = mysql_fetch_assoc($result);
    echo '<table><tr>' . generateTableHead(array_keys($row)) . '</tr>';
    echo '<tr>' . generateTableRow(array_values($row)) . '</tr>';
    while ($row = mysql_fetch_assoc($result)) {
	echo '<tr>' . generateTableRow(array_values($row)) . '</tr>';
    }
    echo '</table>';
}

$HTML->outputFooter();
?>
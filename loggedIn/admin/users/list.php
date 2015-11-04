<?php

require_once '../../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../../webdev/php/Generators/tableGenerator.php';
$HTML = new \HTMLGenertator\HTMLfile('Admin pannel', ['table.css'], NULL, NULL, 2);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();

echo '<h1>List of all students and teachers</h1>';
$result = safeQuery('
	    SELECT
		id AS "ID",
		CONCAT(name,"<br />", surname) AS "Name",
		mail AS "E-Mail",
		CONCAT(phone,"<br />", COALESCE(mobile,"no mobile")) AS "Phone & Mobile",
		CONCAT(street,"<br />", postalcode, " ", region) AS "Adress",
		birthday AS "Birthday",
		CONCAT(status, "<br />", COALESCE(grade,"none")) AS "Status",
		username AS "Username",
		CONCAT("<a href=\"change.php?id=", id,"\">Edit</a>") AS "Edit"
	    FROM user__overview
	    ORDER BY status;');
$num = mysql_num_rows($result);
echo '<p>There are ' . $num . ' users registered.</p>';
if ($num != 0) {
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
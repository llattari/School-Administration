<?php

require_once '../../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../../webdev/php/Generators/tableGenerator.php';
require_once '../../../webdev/php/Classes/Messages.php';
$HTML = new \HTMLGenertator\HTMLfile('Admin pannel', ['table.css'], NULL, NULL, 2);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = safeQuery('DELETE FROM user__overview WHERE id=' . $id . ';');
    if ($result) {
	Message::castMessage('User sucessfully deleted', true);
    } else {
	Message::castMessage('User could not be deleted');
    }
}

echo '<h1>Click on the user you want to delete</h1>';
$result = safeQuery('
	    SELECT
		id AS "ID",
		CONCAT(name,"<br />", surname) AS "Name",
		mail AS "E-Mail",
		CONCAT(phone,"<br />", COALESCE(mobile,"no mobile")) AS "Phone & Mobile",
		CONCAT(street,"<br />", postalcode, " ", region) AS "Adress",
		UNIX_TIMESTAMP(birthday) AS "Birthday",
		CONCAT(status, "<br />", COALESCE(grade,"none")) AS "Status",
		username AS "Username",
		CONCAT("<a href=\"delete.php?id=", id,"\">Delete</a>") AS "Delete"
	    FROM user__overview
	    ORDER BY status;');
$num = mysql_num_rows($result);
echo '<p>There are ' . $num . ' users registered.</p>';
if ($num != 0) {
    $first = true;
    echo '<table>';
    while ($row = mysql_fetch_assoc($result)) {
	if ($first) {
	    echo '<tr>' . generateTableHead(array_keys($row)) . '</tr>';
	    $first = false;
	}
	if ($row['ID'] == $_SESSION['studentId']) {
	    $row['Delete'] = 'Can\'t<br />delete';
	}
	echo '<tr>' . generateTableRow(array_values($row)) . '</tr>';
    }
    echo '</table>';
}

$HTML->outputFooter();
?>
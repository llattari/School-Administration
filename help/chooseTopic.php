<!DOCTYPE html>
<?php
require_once '../webdev/php/essentials/databaseEssentials.php';
connectDB();

$result = safeQuery('SELECT id, topic FROM help__topics;');
?>
<html>
    <head>
	<title>Help page for the School administration software</title>
	<meta charset="UTF-8">
    </head>
    <body>
	<h1>Table of contents</h1>
	<ul>
	    <?php
	    while($row = mysql_fetch_row($result)){
		echo '<li><a href="help.php?topic=' . $row[0] . '">' . $row[1] . '</a></li>';
	    }
	    ?>
	</ul>
	<a href="../index.php">Back to the login page</a>
    </body>
</html>
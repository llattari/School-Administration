<html>
    <head>
	<!-- CSS formating -->
	<?php
	session_start();
	echo '<style>';
	if ($_SESSION['ui']['darkTheme']) {
	    echo '*{color: white;}';
	}
	echo '</style>';
	?>
    </head>
    <body>
	<?php
	include_once '../../webdev/php/essentials/databaseEssentials.php';
	connectDB();

	//Deleting inactive user
	$timeBeforeKick = 7200; // 2h
	safeQuery('DELETE FROM chat__online WHERE UNIX_TIMESTAMP(lastAction) <= ' . (time() - $timeBeforeKick) . ';');

	//Querrying active user
	$result = safeQuery(
		'SELECT
		    lastAction, username
		FROM chat__online
		LEFT JOIN user__overview user
		ON chat__online.userId = user.id
		LIMIT 50;');
	$rowCount = mysql_num_rows($result);
	echo '<ul>';
	while ($row = mysql_fetch_assoc($result)) {
	    $username = (is_null($row['username'])) ? 'anonymus' : $row['username'];
	    echo "<li>$username" . date(' [h:i A]', strtotime($row['lastAction'])) . '</li>';
	}
	echo '</ul>';
	?>
    </body>
</html>
<html>
    <head>
	<!-- CSS formating -->
	<?php
	session_start();
	echo '<style>';
	if ($_SESSION['ui']['darkTheme']) {?>
	*{
		color: white;
	}
	<?php
	}
	?>
	.daysPassed{
		width: 100%;
		border-top: 1px solid;
		border-bottom: 1px solid;
		text-align: center;
	}
	<?php
	echo '</style>';
	?>
    </head>
    <body>
	<?php
	include_once '../../webdev/php/essentials/databaseEssentials.php';
	connectDB();

	$lastMessage = 0;
	$result = safeQuery(
		'SELECT
		    message, user.username AS "sender", sendDate
		FROM chat__messages
		JOIN user__overview user
		ON chat__messages.sender = user.id
		LIMIT 100;');
	$rowCount = mysql_num_rows($result);
	while ($row = mysql_fetch_assoc($result)) {
	    $sendDate = strtotime($row['sendDate']);
	    $dif = abs($sendDate - $lastMessage);
	    if ($dif > 86400) {
		$lastMessage = $sendDate;
		echo '<div class="daysPassed">';
		$daysAgo = floor($dif / 86400);
		if ($daysAgo == 1) {
		    echo '1 day passed';
		} else {
		    echo $daysAgo . ' days passed';
		}
		echo '</div>';
	    }
	    $time = date('H:i', $sendDate);
	    echo '<span>[' . $time . '] ' . $row['sender'] . ': ' .
	    $row['message'] . '</span><br />';
	}
	?>
	<a name="end"></a>
    </body>
</html>
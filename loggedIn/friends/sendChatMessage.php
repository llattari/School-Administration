<?php

require_once '../../webdev/php/Classes/Messages.php';
require_once '../../webdev/php/Classes/debuging/Logger.php';
require_once '../../webdev/php/essentials/databaseEssentials.php';

connectDB();
session_start();

function getBadWords() {
    $list = [];
    $result = safeQuery('SELECT badWord FROM chat__badWords;');
    while ($row = mysql_fetch_row($result)) {
	array_push($list, strtolower($row[0]));
    }
    return $list;
}

$message = escapeStr($_POST['message']);
$sender = $_SESSION['studentId'];

if (strlen($message) != 0) {
    $badWords = getBadWords();
    $wordList = explode(" ", $message);
    if (count($wordList) == 0) {
	return;
    }
    for ($i = 0; $i < count($wordList); $i++) {
	$lowerVersion = strtolower($wordList[$i]);
	foreach ($badWords as $badWord) {
	    if (!(strpos($lowerVersion, strtolower($badWord)) === false)) {
		$wordList[$i] = '****';
	    }
	}
    }
    $replacedMessage = join(' ', $wordList);
    $suc = safeQuery("INSERT INTO chat__messages(message, sender) VALUES ('$replacedMessage', $sender);");
    safeQuery("INSERT INTO chat__online (lastAction, userId)VALUES(NOW(),$sender) ON DUPLICATE KEY UPDATE lastAction=NOW();");
    Logger::log('User send a chat message.', Logger::SOCIAL);
    Header('Location: chat.php');
} else {
    Message::castMessage('Message can\'t be empty', false, 'chat.php');
}
?>
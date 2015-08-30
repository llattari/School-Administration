<?php

include_once '../../webdev/php/Classes/Messages.php';
//include_once '../../webdev/php/Classes/debuging/Logger.php';
include_once '../../webdev/php/essentials/databaseEssentials.php';

connectDB();
session_start();

function getBadWords() {
    $list = array();
    $result = safeQuery('SELECT badWord FROM chat__badWords;');
    while($row = mysql_fetch_row($result)){
	array_push($list, strtolower($row[0]));
    }
    return $list;
}

$message = escapeStr($_POST['message']);
$sender = $_SESSION['studentId'];

if(strlen($message) != 0){
    $badWords = getBadWords();
    $wordList = explode(" ", $message);
    if(count($wordList) == 0){
	return;
    }
    for($i = 0; $i < count($wordList); $i++){
	$lowerVersion = strtolower($wordList[$i]);
	for($j = 0; $j < count($badWords); $j++){
	    if(!(strpos($lowerVersion, $badWords[$j]) === false)){
		$wordList[$i] = '****';
	    }
	}
    }
    $replacedMessage = join(' ', $wordList);
    echo $replacedMessage;
//    $suc = safeQuery("INSERT INTO chat__messages(message, sender) VALUES ('$message', $replacedMessage);");
//    Header('Location: chat.php');
}else{
//    Message::castMessage('Message can\'t be empty', false, 'chat.php');
}
?>
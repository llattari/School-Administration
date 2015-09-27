<?php

require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Classes/ClassMail.php';
connectDB();

use \MailManager;

$mid = $_GET['id'];
if(!isset($mid) || !intval($mid) || $mid < 0){
    Header('Location: read.php');
}
if(MailManager\Overview::userHas($_SESSION['studentId'], $mid)){
    $suc = safeQuery("UPDATE user__messages SET deleted = NOW() WHERE id = $mid");
    Logger::log('The message (id: ' . $mid . ') was deleted', Logger::SOCIAL);
    if(!$suc){
	echo 'Message couldn\'t be deleted';
    }else{
	Header('Location: read.php');
    }
}else{
    echo 'You have no permission to do that.';
}
echo '<br /><a href="read.php">Back to messages</a>';
?>
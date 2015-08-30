<!DOCTYPE html>
<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/Messages.php';

$HTML = new HTMLGenertator\HTMLfile('Send mail', NULL, NULL, NULL, 1);
$HTML->outputHeader();

// Getting the data from the previous form
$subject = escapeStr($_POST['subject']);
$reciever = $_POST['receiver'];
$sender = $_SESSION['studentId'];
$textarea = escapeStr($_POST['message'], 'text');
$destination = 'read.php';

if($reciever != 0){
    $suc = safeQuery("INSERT INTO user__messages(sender, reciver, subject, content)
				    VALUES($sender, $reciever, '$subject', '$textarea');");
    $messageId = mysql_insert_id();
    if($suc){
	Logger::log("A new message (id: $messageId) was send to user with the id $reciever.", Logger::SOCIAL);
	Message::castMessage('Your messsage was sucessfully send.', $suc, $destination);
    }
    Message::castMessage('Your message could not be send.', $suc, $destination);
}
Message::castMessage('You have not specified a receiver.', false, $destination);
echo '<br /><a href="read.php">Back to inbox</a>';
$HTML->outputFooter();
?>
<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Generators/tableGenerator.php';
require_once '../../webdev/php/Classes/ClassMail.php';
$HTML = new HTMLGenertator\HTMLfile('Mail inbox', ['table.css', 'mails.css'], NULL, NULL, 1);
$HTML->outputHeader();

use \MailManager;

$studentId = $_SESSION['studentId'];
$mailOverview = new MailManager\Overview($studentId);
?>

<h1>Inbox</h1>
<p>
    <a href="write.php">Write new message</a>
</p>

<?php
if($mailOverview->getTotal() == 0){
    echo '<p>You have no mails in your virtual letter box.</p>';
}else{
    if($mailOverview->getUnread() != 0){
	echo '<p>You have ' . $mailOverview->getUnread() . ' unread message(s)</p>';
    }
    echo '<table summary="The list of all the messages in your inbox">';
    echo generateTableHead(['Date', 'Sender', 'Subject', 'Delete']);
    echo '<tbody>' . (string) $mailOverview . '</tbody></table>';
}

$HTML->outputFooter();
?>

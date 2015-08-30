<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/ClassMail.php';
require_once '../../webdev/php/Generators/tableGenerator.php';

#Initing the objects
$HTML = new HTMLGenertator\HTMLfile('Mail inbox', ['table.css', 'mails.css'], NULL, NULL, 1);
$overview = new MailManager\Overview($_SESSION['studentId']);

$HTML->outputHeader();
$unreadCount = $overview->getUnread();
switch($unreadCount){
    case 0:
	$insertString = 'no unread mails';
	break;
    case 1:
	$insertString = '1 unread mail';
	break;
    default:
	$insertString = $unreadCount . ' unread mails';
}
?>
<a href="write.php">Create a new message</a>
<h2>Inbox</h2>
<h3>You have <?php echo $insertString; ?></h3>
<table>
    <?php
    echo generateTableHead(['Subject', 'Sender', 'X']);
    $idArray = $overview->getIds();
    for($i = 0; $i < count($idArray); $i++){
	$current = $overview->getMessage($idArray[$i]);
	$id = $idArray[$i];
	$unreadClass = ($current->isRead()) ? '' : ' class="unread"';
	echo "<tr $unreadClass>";
	echo '<td><a href="read.php?id=' . $id . '">' . $current->getSubject() . '</a></td>
	<td>' . $current->getSender() . '</td>
	<td><a href="delete.php?id=' . $id . '" class="delete">x</a></td>';
	echo '</tr>';
    }
    ?>
</table>
<?php
$HTML->outputFooter();
?>

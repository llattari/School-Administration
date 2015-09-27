<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/ClassMail.php';
require_once '../../webdev/php/Generators/tableGenerator.php';

#Initing the objects
$HTML = new HTMLGenertator\HTMLfile('Mail inbox', ['table.css', 'form.css', 'mails.css',], NULL, NULL, 1);
$overview = new MailManager\Overview($_SESSION['studentId']);

# Inting HTML file
$HTML->outputHeader();

# Initing mail vars
$unreadCount = $overview->getUnread();
$idArray = $overview->getIds();
$listedMails = count($idArray);
?>
<a href="write.php"><button>Create a new message</button></a>
<h2>Inbox (<?php echo $unreadCount; ?>)</h2>
<?php
# Listing the mails
if ($listedMails != 0) {
    echo '<table id="mailTable">';
    echo generateTableHead(['Subject', 'Sender', 'X']);
    for ($i = 0; $i < $listedMails; $i++) {
	$current = $overview->getMessage($idArray[$i]);
	$id = $idArray[$i];
	$unreadClass = ($current->isRead()) ? '' : ' class="unread"';
	echo "<tr $unreadClass>";
	echo '<td><a href="read.php?id=' . $id . '">' . $current->getSubject() . '</a></td>
	    <td>' . $current->getSender() . '</td>
	    <td><a href="delete.php?id=' . $id . '" class="delete">x</a></td>';
	echo '</tr>';
    }
    echo'</table>';
}else{
    echo '<p>The inbox is empty!</p>';
}
$HTML->outputFooter();
?>

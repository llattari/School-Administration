<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/ClassMail.php';
require_once '../../webdev/php/Generators/tableGenerator.php';

# Initing the HTML object
$HTML = new HTMLGenertator\HTMLfile('Mail inbox', ['table.css', 'form.css', 'mails.css'], NULL, NULL, 1);
# Emptying the trash can
if (isset($_GET['empty'])) {
    $studentId = $_SESSION['studentId'];
    safeQuery("DELETE FROM user__messages WHERE deleted IS NOT NULL AND reciver = $studentId;");
}

# Initing the mail object
$overview = new MailManager\Overview($_SESSION['studentId'], true);
$HTML->outputHeader();

# Initing mail vars
$unreadCount = $overview->getUnread();
$idArray = $overview->getIds();
$listedMails = count($idArray);
?>
<a href="index.php">Back to inbox</a>
<h2>Trash</h2>
<?php
# Listing the mails
if ($listedMails != 0) {
    echo '<br />
	<a href="?empty=true">Empty trash</a>
	<table id="mailTable">';
    echo generateTableHead(['Subject', 'Sender', 'Deleted']);
    for ($i = 0; $i < $listedMails; $i++) {
	$current = $overview->getMessage($idArray[$i]);
	$id = $idArray[$i];
	$unreadClass = ($current->isRead()) ? '' : ' class="unread"';
	echo "<tr $unreadClass>";
	echo '<td><a href="read.php?id=' . $id . '">' . $current->getSubject() . '</a></td>
	    <td>' . $current->getSender() . '</td>
	    <td>' . date('d.m.Y', $current->getDeleteDate()) . '</a></td>';
	echo '</tr>';
    }
    echo'</table>';
} else {
    echo '<p>The trash is empty!</p>';
}
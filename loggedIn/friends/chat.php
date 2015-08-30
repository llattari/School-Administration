<?php
require_once('../../webdev/php/Generators/HTMLGenerator/Generator.php');
$HTML = new HTMLGenertator\HTMLfile('Chat with your friends', ['chat.css', 'form.css'], NULL, NULL, 1);
$HTML->outputHeader();
?>
<!-- Receiving chat messages -->
<div class="left">
    <h2>Messages</h2>
    <div id="chatMessages">
	<?php
	$result = safeQuery(
		'SELECT
		    message, user.username AS "sender", sendDate
		FROM chat__messages
		JOIN user__overview user
		ON chat__messages.sender = user.id
		LIMIT 100;');
	$rowCount = mysql_num_rows($result);
	while($row = mysql_fetch_assoc($result)){
	    $time = date('H:i', strtotime($row['sendDate']));
	    echo'<span>[' . $time . '] ' . $row['sender'] . ': ' . $row['message'] . '</span><br />';
	}
	?>
    </div>
    <br />
    <!-- Sending chat messages -->
    <form action="sendChatMessage.php" method="post">
	<input type="text" placeholder="Enter your message here!" name="message" />
	<button type="submit">Send</button>
    </form>
</div>
<div class="right">
    <h4>Online</h4>
    <ul>
	<li>Mamazu</li>
    </ul>
</div>
<br style="clear:both; " />
<script type="text/javascript">
</script>
<?php
$HTML->outputFooter();
?>

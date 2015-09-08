<?php
require_once('../../webdev/php/Generators/HTMLGenerator/Generator.php');
$HTML = new HTMLGenertator\HTMLfile('Chat with your friends', ['chat.css', 'form.css'], ['refreshIFrame.js'], NULL, 1);
$HTML->outputHeader();
?>
<!-- Receiving chat messages -->
<div class="left">
    <h2>Messages</h2>
    <div id="chatMessages">
	<iframe src="chatMessages.php#end" width="100%" onload="startChat()">
	</iframe>
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

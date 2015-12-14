<?php

use HTMLGenertator;

require_once('../../webdev/php/Generators/HTMLGenerator/Generator.php');
$HTML = new HTMLGenertator\HTMLfile('Chat with your friends', ['chat.css', 'form.css'], ['refreshIFrame.js'], NULL, 1);
$HTML->outputHeader();
?>
<!-- Receiving chat messages -->
<div class="left">
    <h2>Messages</h2>
    <div id="chatMessages">
	<iframe src="chatMessages.php#end" width="99%">
	</iframe>
    </div>
    <br />
</div>
<div class="right">
    <h4>Online</h4>
    <iframe src="onlineUser.php" width="99%">
    </iframe>
</div>
<br style="clear:both; " />
<!-- Sending chat messages -->
<form action="sendChatMessage.php" method="post">
    <input type="text" placeholder="Enter your message here!" name="message" id="newMessage" />
    <button type="submit">Send</button>
</form>
<script type="text/javascript">
    startChat();
    var allIFrames = document.getElementsByTagName('iframe');
    var winHeight = window.innerHeight;
    for (var i = 0; i < allIFrames.length; i++) {
	allIFrames[i].style.height = Math.floor(winHeight * 0.7) + 'px';
    }
</script>
<?php
$HTML->outputFooter();
?>

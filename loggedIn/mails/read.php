<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/ClassMail.php';

# Initing the HTML object
$HTML = new HTMLGenertator\HTMLfile('Mail inbox', ['mails.css'], NULL, NULL, 1);

# Getting the mail id
$mailId = (int) $_GET['id'];
if($mailId == 0){
    Header('Location: index.php');
}

# Initing mail object
$mail = new MailManager\Mail($mailId);
$HTML->outputHeader();

if(MailManager\Overview::userHas($_SESSION['studentId'], $mailId)){
    $mail->setRead();
    ?>
    <!-- Outputing mail content -->
    <h2> <?php echo $mail->getSubject() ?> </h2>
    <div class="u">
        <span>Sender: <?php echo $mail->getSender(); ?></span>
        <div id="mDate"><?php echo MailManager\getTimePassed($mail->getSendDate()); ?></div>
    </div>
    <div id="messageContent">
        <p>
	    <?php echo $mail->getContent(); ?>
        </p>
    </div>
    <?php
}else{
    # Throwing an error if the user has insufficient permission
    Message::cast('You don\'t have the permission to read this message.', false, 'index.php');
}
echo '<a href="index.php">Return to overview</a>';

$HTML->outputFooter();
?>
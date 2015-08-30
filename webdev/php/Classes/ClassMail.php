<?php

namespace MailManager;

require_once 'ClassPerson.php';

class Overview {
    private $mailList = Array();
    private $unread = 0;

    /**
     * Constructor for the object
     * @param int $id
     */
    function __construct($id) {
	$result = safeQuery(
		'SELECT
		    user__messages.id, username,
		    subject, content, readStatus, sendDate
		FROM user__messages
		LEFT JOIN user__overview
		ON user__overview.id = user__messages.sender
		WHERE reciver = ' . (int) $id . '
		ORDER BY readStatus;');
	while($row = mysql_fetch_row($result)){
	    #See indicies from query above
	    $newMail = new Mail($row[0], $row[1], $row[2], $row[3], $row[4], $row[5]);
	    array_push($this->mailList, $newMail);
	    if(!$row[4]){
		$this->unread++;
	    }
	}
    }

    //getter and setter
    public function getMessage($messageId) {
	foreach($this->mailList as $mail){
	    if($mail->getId() == $messageId){
		return $mail;
	    }
	}
	return NULL;
    }

    public function getTotal() {
	return count($this->mailList);
    }

    public function getUnread() {
	return $this->unread;
    }

    public function getIds() {
	$result = Array();
	foreach($this->mailList as $mail){
	    array_push($result, $mail->getId());
	}
	return $result;
    }

    //END: getter and setter

    /**
     * __toString()
     * 	    Turns the object in a string
     * @return string
     */
    public function __toString() {
	foreach($this->mailList as $mail){
	    $result .= (string) $mail;
	}
	return $result;
    }

    public static function userHas($user, $mail) {
	$result = safeQuery("SELECT id FROM user__messages WHERE id = $mail AND reciver = $user;");
	return (mysql_num_rows($result) != 0);
    }

}

class Mail {
    private $id, $sender, $subject, $content, $sendDate, $read;

    public function __construct($id, $sender = NULL, $subject = NULL, $content = NULL, $read = false, $sendDate = NULL) {
	$this->id = (int) $id;
	if(func_num_args() == 1){
	    $this->queryData();
	}else{
	    $this->sender = ($sender == '') ? NULL : $sender;
	    $this->subject = $subject;
	    $this->content = $content;
	    $this->read = $read;
	}
	//Setting the senddate to the current time if not specified
	$this->sendDate = ($sendDate == NULL) ? time() : $sendDate;
    }

    private function queryData() {
	$result = safeQuery(
		'SELECT
		    username, subject, content, readStatus, sendDate
		FROM user__messages
		LEFT JOIN user__overview
		ON user__overview.id = user__messages.sender
		WHERE user__messages.id = ' . $this->id . ';');
	$row = mysql_fetch_row($result);
	$this->sender = $row[0];
	$this->subject = $row[1];
	$this->content = $row[2];
	$this->read = $row[3];
	$this->sendDate = $row[4];
    }

    // <editor-fold defaultstate="collapsed" desc="Getter of the private properties">
    public function getId() {
	return $this->id;
    }

    public function getSender() {
	return $this->sender;
    }

    public function getSubject() {
	return $this->subject;
    }

    public function getContent() {
	return nl2br($this->content);
    }

    public function getSendDate() {
	return $this->sendDate;
    }

    public function isRead() {
	return $this->read;
    }

    // </editor-fold>


    public function setRead() {
	$result = safeQuery('UPDATE user__messages SET readStatus = true WHERE id = ' . $this->id . ';');
	\Logger::log('Message (id: ' . $this->id . ') was marked as read.', \Logger::SOCIAL);
	if($result){
	    $this->read = true;
	}
	return $result;
    }

    public function __toString() {
	$style = ($this->read) ? 'read' : 'unread';
	$result = "<tr class=\"$style\">";
	//Showing the send date
	$result .= '<td>' . date('d.m.y', strtotime($this->sendDate)) . '</td>';
	//Showing the sender of the message
	$sender = $this->sender;
	$result.='<td>' . (is_null($sender) ? '(unkown)' : $sender) . '</td>';
	//Showing subject with read link
	$result.= '<td><a href="read.php?id=' . $this->id . '">' . $this->subject . '</a></td>';
	//The column for deleting
	$result.= '<td class="delete"><a href="delete.php?id=' . $this->id . '">x</a></td>';
	$result.= '</tr>';
	return $result;
    }

}

function getTimePassed($time) {
    $arrive = strtotime($time);
    $now = time();
    $descr = $now - $arrive;
    if($descr < 60){
	return 'less than a minute ago';
    }
    if($descr < 3600){
	return round($descr / 60) . ' minutes agop';
    }
    if($descr < 86400){
	return round($descr / 3600) . ' hours ago';
    }
    if($descr < 31536000){
	return round($descr / 86400) . ' days ago';
    }
    return round($descr / 31536000, 1) . ' years ago';
}

?>
<?php

class Post {

    private $id = 0;
    private $message = '';
    private $visibility = 'show';
    private $creator = 0;
    private $time = 0;

    public function __construct($id) {
	$this->id = (int) $id;
	$result = safeQuery('SELECT post, poster, postTime, visiblity FROM forum__post WHERE id = ' . $this->id . ';');
	if (mysql_num_rows($result) != 1) {
	    return;
	}
	$row = mysql_fetch_assoc($result);
	$this->message = $row['post'];
	$this->visibility = $row['visiblity'];
	$this->creator = $row['poster'];
	$this->time = strtotime($row['postTime']);
    }

    // <editor-fold defaultstate="collapsed" desc="Getter">

    /**
     * Returns the id of the post
     * @return int
     */
    public function getId() {
	return $this->id;
    }

    /**
     * Returns the content of the post (message)
     * @return string
     */
    public function getMessage() {
	if ($this->visibility == 'show') {
	    return $this->message;
	} else {
	    return 'This message has been ' . ($this->visibility == 'delete') ? 'deleted' : 'hidden';
	}
    }

    /**
     * Returns the id of the poster
     * @return int
     */
    public function getCreator() {
	return $this->creator;
    }

    /**
     * Returns the timestamp the post was sent
     * @return int (timestamp)
     */
    public function getTime() {
	return $this->time;
    }

    // </editor-fold>
    // <editor-fold defaultstate="collapsed" desc="Setter">
    /**
     * Changes the visbility of the current post.
     * @param string $newVisibility
     * 	    Acepted states are: hide, delete, show
     * @return boolean
     * 	    Returns if the visibility update is successfull
     */
    private function changeState($newVisibility) {
	$newState = escapeStr($newVisibility);
	$result = safeQuery('UPDATE forum__post SET visibility = "' . $newState . '" WHERE id=' . $this->id);
	if ($result) {
	    $this->visibility = $newState;
	}
	return (bool) $result;
    }

    /**
     * Deletes the current post.
     * @return boolean
     */
    public function delete() {
	return $this->changeState('delete');
    }

    /**
     * Hides the post in the history
     * @return boolean
     */
    public function hide() {
	return $this->changeState('hide');
    }

// </editor-fold>
}

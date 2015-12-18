<?php

class Post {

    private $id = 0;
    private $message = '';
    private $creator = 0;
    private $time = 0;

    public function __construct($id) {
	$this->id = (int) $id;
	$result = safeQuery('SELECT post, poster, postTime FROM forum__post WHERE id=' . $this->id . ';');
	if (mysql_num_rows($result) != 1) {
	    return;
	}
	$row = mysql_fetch_assoc($result);
	$this->message = $row['post'];
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
	return $this->message;
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

    /**
     * Deletes the current post.
     * @return boolean
     */
    public function delete() {
	$result = safeQuery('DELETE FROM forum__post WHERE id=' . $this->id);
	return (bool) $result;
    }

}

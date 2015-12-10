<?php

class Topic {

    private $id = 0;
    private $creatorId = 0;
    private $name = '';
    private $description = '';

    /**
     * Creates a new Topic object based on the id inputed. If the id was not found, the topic will not be initiliesed.
     * @param id $id
     * @return object
     */
    public function __construct($id) {
	$this->id = (int) $id;
	$result = safeQuery('SELECT topicName, topicDescription, creatorId FROM forum__topic WHERE id = ' . $this->id . ';');
	if (mysql_numrows($result) != 1) {
	    return;
	}
	while ($row = mysql_fetch_row($result)) {
	    $this->name = $row[0];
	    $this->description = $row[1];
	    $this->creatorId = $row[2];
	}
    }

    // <editor-fold defaultstate="collapsed" desc="Getter">

    /**
     * Returns the topic id
     * @return int
     */
    public function getId() {
	return $this->id;
    }

    /**
     * Returns the topic name
     * @return string
     */
    public function getName() {
	return $this->name;
    }

    /**
     * Returns the topic description
     * @return string
     */
    public function getDescription() {
	return $this->description;
    }

    /**
     * Returns the id of the creator
     * @return int
     */
    public function getCreatorId() {
	return $this->creatorId;
    }

    /**
     * Returns an array with the ids of the posts in this topic.
     * @return array
     */
    public function getPostlist() {
	$result = Array();
	$querry = safeQuery('SELECT id FROM forum__post WHERE topic = ' . $this->id . ';');
	while ($row = mysql_fetch_row($querry)) {
	    array_push($result, $row[0]);
	}
	return $result;
    }

    // </editor-fold>

    /**
     * Deltes the current topic
     * @return boolean
     */
    public function delete() {
	$result = safeQuery('DELETE FROM forum__topic WHERE id=' . $this->id . ';');
	return (bool) $result;
    }

    /**
     * Adds a new post to that topic. Returns true if successfull otherwise false
     * @param string $message
     * 	    The message that the user wants to post
     * @param int $postId
     * 	    The id of the poster
     * @return boolean
     */
    public function newPost($message, $postId) {
	$postContent = escapeStr($message);
	$id = (int) $postId;
	$result = safeQuery("INSERT INTO forum__post VALUES(NULL, $this->id, '$postContent', NOW(), $id);");
	return (bool) $result;
    }

}

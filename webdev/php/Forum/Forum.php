<?php

require_once __DIR__ . '/../essentials/essentials.php';

class Forum {

    private $id = 0;
    private $name, $description = '';

    /**
     * Creates a new Forum object based on the id inputed. If the id was not found the forum will be not initialised.
     * @param int $id
     * @return object
     */
    public function __construct($id) {
	$this->id = (int) $id;
	$result = safeQuery("SELECT forumName, forumDescription FROM forum__forums WHERE id=$this->id;");
	if (mysql_numrows($result) != 1) {
	    $this->createIt();
	} else {
	    $row = mysql_fetch_assoc($result);
	    $this->name = $row['forumName'];
	    $this->description = $row['description'];
	}
    }

    private function createIt() {
	$selectClassInformation = safeQuery('SELECT subject FROM course__overview WHERE id=' . $this->id . ';');
	if (mysql_num_rows($selectClassInformation) == 1) {
	    $row = mysql_fetch_assoc($selectClassInformation);
	    $subject = $row['subject'];
	    $this->name = "$subject's forum";
	    $this->description = "This is the default forum for this class.";
	    $insert = safeQuery("INSERT INTO forum__forums VALUES(NULL, '$this->name','$this->description', NULL);");
	    return (bool) $insert;
	}
	return false;
    }

    // <editor-fold defaultstate="collapsed" desc="Getter">

    /**
     * Returns the id of the forum
     * @return int
     */
    public function getId() {
	return $this->id;
    }

    /**
     * Returns the Name of the forum
     * @return string
     */
    public function getName() {
	return $this->name;
    }

    /**
     * Returns the description of the forum
     * @return string
     */
    public function getDescription() {
	return $this->description;
    }

    /**
     * Returns the id of the creator of the forum
     * @return int
     */
    public function getCreator() {
	$result = safeQuery("SELECT creatorId FROM forum__forums WHERE id = $this->id");
	if (mysql_num_rows($result) == 1) {
	    $row = mysql_fetch_row($result);
	    return $row[0];
	}
	return -1;
    }

    /**
     * Returns an array of all topic ids
     * @return array
     */
    public function getTopics() {
	$result = safeQuery('SELECT id FROM forum__topic WHERE forumId = ' . $this->id . ';');
	if (mysql_num_rows($result) == 0) {
	    return NULL;
	}
	$topicList = Array();
	while ($row = mysql_fetch_row($result)) {
	    array_push($topicList, $row[0]);
	}
	return $topicList;
    }

    // </editor-fold>

    /**
     * Deletes the current forum
     * @return boolean
     */
    public function delete() {
	$result = safeQuery("DELETE FROM forum__forums WHERE id = $this->id");
	return (bool) $result;
    }

    /**
     * Creates a new topic in the current forum.
     *
     * @param string $topic
     * 	    The name of the topic
     * @param string $topicDescription
     * 	    A short description of the topic
     * @param int $creatorId
     * 	    Id of the user that created that topic
     * @return boolean
     */
    public function createTopic($topic, $topicDescription, $creatorId) {
	$name = escapeStr($topic);
	$description = escapeStr($topicDescription);
	$creator = (int) $creatorId;
	if ($creatorId < 1) {
	    return false;
	}
	$result = safeQuery("INSERT INTO forum__topic VALUES(NULL, $this->id, '$name', '$description', $creator);");
	return (bool) $result;
    }

}

?>

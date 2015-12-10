<?php

require_once __DIR__ . '/../essentials/essentials.php';

class Forum {

    private $id = 0;
    private $name, $description = '';
    private $parent = 0;

    /**
     * Creates a new Forum object based on the id inputed. If the id was not found the forum will be not initialised.
     * @param int $id
     * @return object
     */
    function __construct($id) {
	$this->id = (int) $id;
	$result = safeQuery("SELECT forumName, forumDescription, parent FROM forum__forums WHERE id=$this->id;");
	if (mysql_numrows($result) != 1) {
	    return;
	}
	$row = mysql_fetch_assoc($result);
	$this->name = $row['forumName'];
	$this->description = $row['description'];
	$this->parent = $row['parent'];
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
     * Returns the id of the parent forum
     * @return int
     */
    public function getParent() {
	return $this->parent;
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

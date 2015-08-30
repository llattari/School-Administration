<?php

class Homework {
    private $id;
    private $material = Array();

    /**
     * Constructor
     * @param int $id
     */
    function __construct($id) {
	$this->id = $id;
	#Getting the content
	$query = 'SELECT
		    task__toDo.content AS "task",
		    hwMaterial.book AS "book",
		    hwMaterial.sheets AS "sheet",
		    hwMaterial.others AS "others",
		    hwMaterial.`link` AS "link"
		FROM task__toDo
		LEFT JOIN hwMaterial
		ON hwMaterial.hwID=toDo.id
		WHERE toDo.id=' . $this->id . ';';
	$result = safeQuery($query);
	echo mysql_error();
	while($row = mysql_fetch_assoc($result)){
	    foreach($row as $key => $value){
		if(is_null($value)){
		    continue;
		}
		if(!isset($this->material[$key])){
		    $this->material[$key] = $row[$key] . '; ';
		}else{
		    $this->material[$key].= $row[$key];
		}
	    }
	}
    }

    /**
     * getTopic()
     * 	    Returns the topic of the homework
     * @return string
     */
    function getTopic() {
	$semicolonPOS = strpos($this->material['task'], ';');
	$this->material['task'] = substr($this->material['task'], 0, $semicolonPOS);
	return $this->material['task'];
    }

    /**
     * getMaterial()
     * 	    Returns the HTML version of all materials
     * @return string
     */
    function getMaterial() {
	$returnString = '<ul>';
	foreach($this->material as $key => $value){
	    if(isset($value) && $key != 'task'){
		$returnString.='<li>' . ucfirst($key) . ': ' . $value . '</li>';
	    }
	}
	return $returnString . '</ul>';
    }

}

?>

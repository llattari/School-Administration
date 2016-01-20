<?php

class ClassHomework {
    private $id;
    private $material = [];

    //constuctor
    function __construct($id) {
	$this->id = (int) $id;
	#Getting the content
	$result = safeQuery(
		'SELECT content AS "task",
			book, sheets, others, `link`
		FROM task__toDo
		LEFT JOIN hwMaterial ON
			hwMaterial.hwID = task__toDo.id
		WHERE
			task__toDo.id=' . $this->id . ';'
	);
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

    //END: costructor
    //getter and setter
    function getTopic() {
	$semicolonPOS = strpos($this->material['task'], ';');
	$this->material['task'] = substr($this->material['task'], 0, $semicolonPOS);
	return $this->material['task'];
    }

    function getMaterial() {
	$returnString = '<ul>';
	foreach($this->material as $key => $value){
	    if(isset($value) && $key != 'task'){
		$returnString.='<li>' . ucfirst($key) . ': ' . $value . '</li>';
	    }
	}
	return $returnString . '</ul>';
    }

    //END: getter and setter
}

?>

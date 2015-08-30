<?php

require_once __DIR__ . '/ClassClass.php';

//Setting the class names for CSS
function doneCheck($data) {
    $done = (!is_null($data) && $data) ? 'done' : 'unDone';
    return $done;
}

//Getting the priorities
function getPriorities() {
    global $priority;
    $resultPrio = safeQuery('SELECT content FROM task__priority ORDER BY prioVal;');
    while($row = mysql_fetch_row($resultPrio)){
	global $priority;
	$priority[count($priority)] = $row[0];
    }
}

class ClassToDo {
    private $student;
    private $tasks = Array();
    private $prios = Array();

    //constructor
    function __construct($newStudent) {
	$this->student = $newStudent;
	$result = safeQuery(
		'SELECT
		    task__toDo.id AS "id",
		    deadline, done,
		    task__toDo.content AS "content",
		    task__type.content AS "task",
		    classID AS "classId",
		    prio AS "priority"
		FROM task__toDo
		JOIN task__type ON task__type.id = task__toDo.typeID
		WHERE studentID = ' . $this->student . ';');
	while($row = mysql_fetch_assoc($result)){
	    array_push($this->tasks, new Todo($row));
	}
	getPriorities();
    }

    public function toggle($id) {
	$suc = safeQuery("UPDATE task__toDo SET done = NOT done WHERE id = $id;");
	return (bool) $suc;
    }

    // <editor-fold defaultstate="collapsed" desc="Getter">
    /**
     * getTask()
     * 	    Get an array of todos
     * @return Array
     */
    public function getTasks() {
	return $this->tasks;
    }

    /**
     * getPrios()
     * @global type $priority
     * @return string
     */
    //todo: edit doc !
    public function getSummary() {
	$undone = 0;
	$done = 0;
	for($i = 0; $i < count($this->tasks); $i++){
	    if($this->tasks[$i]->getDone()){
		$done+=1;
	    }else{
		$undone+=1;
	    }
	}
	$total = $done + $undone;
	$rate = round($done / $total * 100, 2);
	if($rate == 100){
	    return 'You\'re done.';
	}
	return 'Progress: ' . $rate . '%';
    }

    public function getTypes() {
	$final = [];
	for($i = 0; $i < count($this->tasks); $i++){
	    array_push($final, $this->tasks[$i]->getTask());
	}
	return $final;
    }

    public function getSubjects() {
	$final = [];
	for($i = 0; $i < count($this->tasks); $i++){
	    $temp = new StudentClass($this->tasks[$i]->getClassId());
	    $subjectString = (string) $temp;
	    if($subjectString != '!none'){
		array_push($final, $subjectString);
	    }
	}
	return $final;
    }

    /**
     * isEmpty()
     * 	    Returns wheather the tasklist is empty.
     * @return boolean
     */
    public function isEmpty() {
	return count($this->tasks) == 0;
    }

    // </editor-fold>

    private function resultToTable() {
	$resultString = '';
	for($i = 0; $i < count($this->tasks); $i++){
	    // Generate a row for every entry
	    $entry = $this->tasks[$i];
	    $resultString .= (string) $entry;
	}
	return $resultString;
    }

    public function __toString() {
	# Create table out of it if it's not empty
	if(count($this->tasks) == 0){
	    return '<td colspan="4" class="done">You have nothing to do!</td>';
	}
	return $this->resultToTable();
    }

}

class Todo {
    private $id = 0, $deadline, $done, $content, $task, $classId, $priority;

    public function __construct($mysqlRow) {
	foreach($mysqlRow as $key => $value){
	    $this->$key = $value;
	}
    }

    // <editor-fold defaultstate="collapsed" desc="Getter">
    public function getId() {
	return $this->id;
    }

    public function getDeadline() {
	return $this->deadline;
    }

    public function getDone() {
	return $this->done;
    }

    public function getContent() {
	return $this->content;
    }

    public function getTask() {
	return $this->task;
    }

    public function getClassId() {
	return $this->classId;
    }

    public function getPriority() {
	global $priority;
	return $priority[$this->priority];
    }

// </editor-fold>

    public function __toString() {
	$result = '';
	$result.=
		'<tr class="' . doneCheck($this->done) . '" onclick="toggleCheck(this);" oncontextmenu="deleteEntry(this);return false;">
		<td class="dateCol">' . $this->id . date('d.m.Y', strtotime($this->deadline)) . '</td>' .
		'<td>' . $this->content . '</td>';
	if($this->task == 'Personal To-Do'){
	    $result .= '<td class="typeCol">Personal To-Do</td>';
	}else{
	    $result .= '<td class="typeCol">' . $this->task . ' (' . new StudentClass($this->classId) . ')</td>';
	}
	$result.= '<td class="prioCol">' . $this->getPriority() . '</tr> ';
	return $result;
    }

}

?>

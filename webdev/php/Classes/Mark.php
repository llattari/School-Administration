<?php

namespace MarkAdministration;

require_once 'ClassClass.php';

function selectDistinctFrom($column, $condition = '', $table = 'marks') {
    $sql = 'SELECT DISTINCT ' . $column . ' FROM ' . $table;
    if($condition != ''){
	$sql .= ' WHERE ' . $condition;
    }
    $sql .= ';';
    return $sql;
}

//Managing all the grades of a student at once
class MarkManager {
    private $current = null;
    private $archive = Array();
    private $studentId = -1;

    public function __construct($id, $onlyCurrent = true) {
	$this->studentId = (int) $id;
	$this->current = new Grade($this->studentId);
	if(!$onlyCurrent){
	    $this->initArchive();
	}
    }

    /**
     * initArchive()
     * 	    Inits the archive property of the object
     * @return boolean
     * 	    Returns true on success otherwise false
     */
    private function initArchive() {
	$result = safeQuery('SELECT grade FROM course__overview JOIN course__student ON studentId=' . $this->studentId);
	if(!$result){
	    return false;
	}
	while($row = mysql_fetch_row($result)){
	    $grade = $row[0];
	    $this->archive[$grade] = new Grade($this->studentId, $grade);
	}
	return true;
    }

    /**
     * getCurrent()
     * 	    Returns the current grade
     * @return Grade
     */
    public function getCurrent() {
	return $this->current;
    }

    /**
     * getArchive($grade)
     * @param int $grade
     * 	    The grade that you want to have
     * @return Grade
     * 	    Returns null if grade not exists
     */
    public function getArchive($grade) {
	try{
	    return $this->archive[$grade];
	}catch(Exception $e){
	    return $e;
	}
    }

    /**
     * getId()
     * 	    Returns the student id
     * @return int
     */
    public function getId() {
	return $this->studentId;
    }

}

// Managing the individual grades of a student
class Grade extends Basics {
    private $subjects = Array();
    private $grade = -1;

    /**
     * Constructor of the object
     * @param int $studentId
     * @param int $grade
     */
    public function __construct($studentId, $grade = -1) {
	$this->studentId = (int) $studentId;
	//If grade == -1 get current grade
	$this->grade = ($grade == -1) ? $this->getGrade($grade) : $grade;
	$this->initSubjects();
    }

    /**
     * getGrade()
     * 	    Returns the grade of the student
     * @return int
     */
    private function getGrade() {
	$result = safeQuery('SELECT grade FROM user__overview
			    WHERE id = ' . $this->studentId . ';');
	if(mysql_num_rows($result) != 1){
	    $row = mysql_fetch_row($result);
	    return $row[0];
	}
    }

    /**
     * initSubject()
     * 	    Inits the subject array with the subject ids
     * @return boolean
     * 	    Returns true on sucess otherwise false
     */
    private function initSubjects() {
	$result = safeQuery(
		'SELECT
		    course__overview.id
		FROM course__overview
		JOIN studentClass
		ON
		    studentId = ' . $this->studentId . '
		AND course__overview.grade = ' . $this->grade . ';'
	);
	if(!$result){
	    return false;
	}
	while($row = mysql_fetch_row($result)){
	    $this->subjects[$row[0]] = new Subject($this->studentId, $row[0]);
	}
	return true;
    }

    /**
     * getSubjectById($id)
     * 	    Returns the subject with the id
     * @param int $subjectId
     * 	    The id of the subject
     * @return Subject
     * 	    Returns null if the subject was not found.
     */
    public function getSubjectById($subjectId) {
	try{
	    return $this->subjects[$subjectId];
	}catch(Exception $e){
	    return $e;
	}
    }

    /**
     * getSubjectByName($subjectName)
     * 	    Returns the subject with the name
     * @param int $subjectName
     * 	    The name of the subject
     * @return Subject
     * 	    Returns null if the subject was not found.
     */
    public function getSubjectByName($subjectName) {
	for($i = 0; $i < count($this->subjects); $i++){
	    $className = ClassClass::getClassName($this->subjects[$i]);
	    if($className == $subjectName){
		return $this->subjects[$i];
	    }
	}
	return NULL;
    }

    /**
     * getAllSubjectIds()
     * 	    Returns the list of subject a student has taken
     * @return Array
     * 	    List of the ids
     */
    public function getAllClassIds() {
	return array_keys($this->subjects);
    }

    /**
     * getMark()
     * 	    Returns the mark that a student has scored in this year.
     * @return double
     */
    public function getMark() {
	$sum = 0;
	$counter = 0;
	foreach($this->subjects as $value){
	    $sum += $value->getMark();
	    $counter++;
	}
	return $sum / $counter;
    }

}

//Managing the subject of a student
class Subject extends Basics {
    private $classId = 0;
    private $name = '';
    private $quaters = Array();

    /**
     * Constructor for the object
     * @param int $studentId
     * @param int $classId
     */
    public function __construct($studentId, $classId) {
	$this->studentId = $studentId;
	$this->classId = $classId;
	$this->name = \StudentClass::getClassName($this->classId);
	$result = safeQuery(
		'SELECT DISTINCT half FROM marks
		WHERE studentId=' . $this->studentId . '
		AND classId=' . $this->classId . '
		ORDER BY half;');
	while($row = mysql_fetch_row($result)){
	    array_push($this->quaters, new Quarter($studentId, $classId, $row[0]));
	}
    }

    /**
     * getQuarter($i)
     * @param int $i
     * 	    The quarter that you want to get.
     * @return Quarter
     * 	    Returns the quarter if exists otherwise NULL.
     */
    public function getQuarter($i) {
	try{
	    $result = $this->quaters[$i];
	}catch(Exception $e){
	    $result = $e;
	}
	return ($result instanceof Exception) ? NULL : $result;
    }

    /**
     * getMark()
     * 	    Gets the mark weighting the quarters equally.
     * @return type
     */
    public function getMark() {
	$sum = 0;
	for($i = 0; $i < count($this->quaters); $i++){
	    $sum += $this->quaters[$i]->getMark();
	}
	return $sum / count($this->quaters);
    }

}

//Managing a quarter filled with marks
class Quarter extends Basics {
    private $marks, $ratio = Array();
    private $quarter;

    public function __construct($studentId, $classId, $quarter) {
	$this->studentId = $studentId;
	$this->classId = $classId;
	$this->quarter = $quarter;
	$this->initMarks();
    }

    /**
     * initMarks()
     * 	Fills the types with an empty list
     * @return boolean
     * 	    Returns true on succes and false otherwise
     */
    private function initMarks() {
	$query = selectDistinctFrom('markType, weight', 'mark = NULL AND studentId=' . $this->studentId .
		' AND classId=' . $this->classId . ' AND half = ' . $this->quarter);
	$result = safeQuery($query);
	if(mysql_num_rows($result) == 0){
	    return false;
	}
	while($row = mysql_fetch_assoc($result)){
	    //Creating an array for every type of mark and assigning it a ratio
	    $this->marks[$row['markType']] = Array();
	    $this->ratio[$row['markType']] = $row['ratio'];
	}
	$this->fillMarks();
	return true;
    }

    /**
     * fillMarks()
     * 	Fills the types with a list of marks
     * HINT: skips if the type is invalid
     */
    private function fillMarks() {
	foreach($this->marks as $key => $value){
	    $result = safeQuery('
		    SELECT value, ratio FROM marks
		    WHERE studentId=' . $this->studentId . '
		    AND value NOT NULL
		    AND classId=' . $this->classId . '
		    AND type = "' . $key . '
		    AND quarter = ' . $this->quarter . '";');
	    if(!$result){
		continue;
	    }
	    //Filling every type of mark with a list of marks
	    while($row = mysql_fetch_assoc($result)){
		array_push($value, new Mark($row['value'], $row['ratio']));
	    }
	}
    }

    /**
     * getTypes()
     * 	    Returns all the mark types
     * @return Array
     */
    public function getTypes() {
	return array_keys($this->marks);
    }

    /**
     * getMark()
     * 	    Returns the mark that is scored in this subject
     * @return double
     */
    public function getMark() {
	$overall = 0;
	foreach($this->marks as $key => $value){
	    //Calculating the total of type individually
	    $sum = 0;
	    for($i = 0; $i < count($value); $i++){
		$sum += $value->getMark();
	    }
	    //Multiplying with type ratio
	    $overall += $sum * $this->ratio[$key];
	}
	return $overall;
    }

}

//Managing the marks itself
class Mark {
    private $value = 0, $ratio = 0;

    /**
     * Constructor of the object
     * @param int $value
     * @param int $ratio
     */
    public function __construct($value, $ratio) {
	$this->setValue($value);
	$this->setRatio($ratio);
    }

    /**
     * getValue()
     * 	Returns the value of the mark.
     * @return int
     */
    public function getValue() {
	return $this->value;
    }

    /**
     * getRatio()
     * 	Returns the weight of the mark.
     * @return double
     */
    public function getRatio() {
	return $this->ratio;
    }

    /**
     * setValue($value)
     * 	    Sets the value of the mark
     * @param int $value
     * 	    Mark in points form 1 to 15
     * @return boolean
     * 	    Returns true on success otherwise false
     */
    public function setValue($value) {
	if($value > -1 && $value < 16){
	    $this->value = $value;
	    return true;
	}
	return false;
    }

    /**
     * setRatio($ratio)
     * 	    Sets the a new weight of the mark
     * @param double $ratio
     * 	    The weight that the mark has
     * @return boolean
     * 	    True on success otherwise false
     */
    public function setRatio($ratio) {
	if($ratio > 0 && $ratio <= 1){
	    $this->ratio = $ratio;
	    return true;
	}
	return false;
    }

    /**
     * getMark()
     * 	    Returns the mark accoriding to it's weight
     * @return double
     */
    public function getMark() {
	return $this->value * $this->ratio;
    }

}

abstract class Basics {
    private $studentId = 0;

    public abstract function getMark();
}
?>


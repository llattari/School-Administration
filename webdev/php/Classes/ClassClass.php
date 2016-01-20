<?php

class StudentClass {
    private $id = 0;
    private $teacher = null;
    private $subj = [];
    private $type = '', $abbr = '';

    /**
     * The constuctor of the object
     * @param int $classID
     * @return boolean
     */
    public function __construct($classId) {
	$this->id = (int) $classId;
	$result = safeQuery(
		'SELECT
		    id, teacherID, subject, abbr, `type`,
		    CONCAT(grade, abbr, "-",`type`) AS "full"
		FROM
		    course__overview
		WHERE
		    id = ' . $this->id . ';');
	if(mysql_num_rows($result) == 0){
	    $this->abbr = '!none';
	    return true;
	}
	while($row = mysql_fetch_assoc($result)){
	    $this->teacher = $row['teacherID'];
	    $this->subj = [$row['subject'], $row['abbr']];
	    $this->type = $row['type'];
	    $this->abbr = $row['full'];
	}
    }

    /**
     * setType()
     * 	    Sets the type of the course
     * @param String $newType
     * @return boolean
     * 	    Returns true on sucess, false otherwise
     */
    public function setType($newType) {
	if(strlen($newType) < 4){
	    $this->type = $newType;
	    $result = safeQuery("UPDATE class__overview SET type = '$newType' WHERE id = $this->id");
	    Logger::log('Changed the class type to ' . $newType . ' for the class with the id: ' . $this->id, Logger::CLASSMANAGEMENT);
	    return ($result) ? true : false;
	}else{
	    echo 'The entered string is too long.';
	    return false;
	}
    }

    /**
     * getType()
     * 	    Returns the type of the course
     * @return string
     */
    public function getType() {
	return $this->type;
    }

    /**
     * getTeacher()
     * 	    Returns the name of the teacher.
     * @return string
     */
    public function getTeacher() {
	$result = safeQuery('SELECT concat(name, " ", surname) FROM user__overview WHERE id=' . $this->teacher . ' AND status="t";');
	$row = mysql_fetch_row($result);
	return isset($row[0]) ? $row[0] : '';
    }

    /**
     * getMemberCount()
     * 	    Get the amount of students attending that class
     * @return int
     */
    public function getMemberCount() {
	$result = safeQuery('SELECT COUNT(*) FROM course__student WHERE classID=' . $this->id . ';');
	if(mysql_num_rows($result)){
	    $row = mysql_fetch_row($result);
	    return $row[0];
	}
    }

    /**
     * __toString()
     * 	    Returns the abbreviation of the subject
     * @return string
     */
    public function __toString() {
	return $this->abbr;
    }

    /**
     * isValid()
     * 	    Returns wheather the class was corect
     * @return boolean
     */
    public function isValid() {
	return ($this->abbr[0] != '!');
    }

    /**
     * @static getClassName($classId)
     * 	    Getting the name without creating an object
     * @param int $classId
     * @return string
     */
    public static function getClassName($classId) {
	$result = safeQuery("SELECT subject FROM course__overview WHERE id = $classId;");
	$row = mysql_fetch_row($result);
	if(mysql_num_rows($result) == 1){
	    return $row[0];
	}
    }

}

?>

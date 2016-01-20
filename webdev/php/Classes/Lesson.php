<?php

class Lesson {

    private $id, $class, $time, $location, $teacherId, $valid;

    /**
     * Constructor of the object
     * @param int $studenId
     * @param timestamp $curTime
     * @return object
     */
    function __construct($studenId) {
	$this->id = (int) $studenId;
	$result = safeQuery(
		'SELECT DISTINCT
		    course__overview.id AS "id",
		    course__overview.abbr AS "abbr",
		    course__overview.teacherID AS "teacherId",
		    timetable__standardTimes.`start` AS "start",
		    timetable__standardTimes.`end` AS "end",
		    timetable__overview.room AS "room"
		FROM timetable__overview
		JOIN course__overview ON course__overview.id = timetable__overview.classID
		JOIN course__student ON course__overview.id = course__student.classID
		JOIN timetable__standardTimes  ON timetable__standardTimes.id = timetable__overview.lesson
		WHERE
		    `day` = ' . date('N') . '
		AND
		    timetable__standardTimes.`start` >= "' . date('H:i:s') . '"
		AND
		    (course__overview.teacherID = ' . $this->id . ' OR course__student.studentID = ' . $this->id . ')
		LIMIT 1;'
	);
	if (mysql_num_rows($result) == 0) {
	    $this->valid = false;
	    return;
	}
	$row = mysql_fetch_assoc($result);
	$this->valid = true;
	$this->class = [$row['id'], $row['abbr']];
	$this->teacherId = $row['teacherId'];
	$this->time = [$row['start'], $row['end']];
	$this->location = $row['room'];
	$this->initId();
    }

    /**
     * initId()
     *      Inits the id if it doesn't exist in table
     */
    private function initId() {
	$started = date('Y-m-d, H:i:s', strtotime($this->time[0]));
	$classId = $this->class[0];
	$existsSELECT = safeQuery("SELECT id FROM lesson__overview WHERE classId = $classId AND started = \"$started\";");
	if (mysql_num_rows($existsSELECT) == 0) {
	    safeQuery("INSERT INTO lesson__overview(classId, started)
                                VALUES($classId, \"$started\");");
	    $this->id = mysql_insert_id();
	    Logger::log('Created a new lesson with the id: ' . $this->id, Logger::LESSONMANAGEMENT);
	} else {
	    $row = mysql_fetch_row($existsSELECT);
	    $this->id = $row[0];
	}
    }

    // <editor-fold defaultstate="collapsed" desc="Getter">

    /**
     * getId()
     *      Returns the id of the lesson
     * @return int
     */
    public function getId() {
	return $this->id;
    }

    /**
     * Returns the id of the class
     * @return int
     */
    public function getClassId() {
	return (int) $this->class[0];
    }

    /**
     * getClassName()
     * Returns the abbreviation of the class
     * @return string
     */
    public function getClassName() {
	return $this->class[1];
    }

    /**
     * Returns the id of the teacher
     * @return int
     */
    public function getTeacherId() {
	return $this->teacherId;
    }

    /**
     * Returns the time the class started
     * @return string
     */
    public function getStartingTime() {
	return substr($this->time[0], 0, -3);
    }

    /**
     * Return the time in seconds to the next lesson.
     * @return int
     */
    public function getTimeToStart() {
	$startUnix = strtotime($this->time[0]);
	return $startUnix - time();
    }

    /**
     * Returns the time the class ended
     * @return string
     */
    public function getEndingTime() {
	return substr($this->time[1], 0, -3);
    }

    /**
     * Returns the time the location where the lesson takes place
     * @return string
     */
    public function getLocation() {
	return $this->location;
    }

    /**
     * Returns wheater there is a lesson or not.
     * @return boolean
     */
    public function lessonToday() {
	return $this->valid;
    }

    /**
     * Returns wheater or not there is an active lesson ongoing.
     * @return boolean
     */
    public function takesPlace() {
	$now = time();
	$startUnix = strtotime($this->time[0]);
	$endUnix = strtotime($this->time[1]);
	return ($startUnix <= $now && $now <= $endUnix);
    }

    // </editor-fold>
}

?>

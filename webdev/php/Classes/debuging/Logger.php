<?php

class Logger extends LoggerConstants {
    private $allEvents = Array();

    public function __construct($id = NULL) {
	$sql = 'SELECT * FROM debug__logger';
	if($id = NULL){
	    $sql.=';';
	}else{
	    $sql.=' WHERE issuer=' . (int) $id . ';';
	}
	$result = safeQuery($sql);
	while($row = mysql_fetch_assoc($result)){
	    array_push($this->allEvents, new Event($row['event'], $row['issuer'], $row['ts']));
	}
    }

    /**
     * filterPeriod()
     * 	    Filters the events within a specific periode
     * @param int $start
     * 	    Beginning of the period (if not defined: yesterday)
     * @param int $end
     * 	    End of the period (if not definded: now)
     * @return array
     */
    public function filterPeriod($start = NULL, $end = NULL) {
	$filteredEvents = Array();
	//If start is null set it to be yesterday
	$startTime = ($start == NULL) ? time() - 3600 * 24 : $start;
	//If end is null set it to be today
	$endTime = ($end == NULL) ? time() : $end;
	for($i = 0; $i < count($this->allEvents); $i++){
	    $eventTime = $this->allEvents[$i]->getTimeStamp();
	    if($startTime <= $eventTime && $eventTime <= $endTime){
		array_push($filteredEvents, $this->allEvents[$i]);
	    }
	}
	return $filteredEvents;
    }

    /**
     * getEvent()
     * 	    Returns the event selected if not in array bounds it returns NULL
     * @param int $eventNumber
     * @return Event
     */
    public function getEvent($eventNumber) {
	if(-1 < $eventNumber && $eventNumber < count($this->allEvents)){
	    return $this->allEvents[$eventNumber];
	}else{
	    return NULL;
	}
    }

    /**
     * getEventCount()
     * 	    Returns the number of events that have happend
     * @return int
     */
    public function getEventCount() {
	return count($this->allEvents);
    }

// <editor-fold defaultstate="collapsed" desc="Static methods">
    public static function log($event, $eventType) {
	$topic = (int) $eventType;
	safeQuery("INSERT INTO debug__logger(event, topicId, issuer) VALUES ('$event', $topic, " . $_SESSION['studentId'] . ');');
    }

// </editor-fold>
}

class LoggerConstants {
    const OTHER = 0;
    const USERMANAGEMENT = 1;
    const TASKMANAGEMENT = 2;
    const CLASSMANAGEMENT = 3;
    const MARKMENAGEMENT = 4;
    const LESSONMANAGEMENT = 5;
    const CHATABUSE = 6;
    const SOCIAL = 7;

}

<?php

class Event {
    private $event, $issuer, $timeStamp;

    public function __construct($event, $issuer, $timeStamp) {
	$this->event = $event;
	$this->issuer = $issuer;
	$this->timeStamp = strtotime($timeStamp);
    }

    // <editor-fold defaultstate="collapsed" desc="Getter">
    /**
     * getEvent()
     * 	    Returns the name of the event
     * @return string
     */
    public function getEvent() {
	return $this->event;
    }

    /**
     * getIssuer()
     * 	    Returns the id of the initiator of that event
     * @return int
     */
    public function getIssuer() {
	return $this->issuer;
    }

    /**
     * getTimeStamp()
     * 	    Returns the timestamp the event has happend
     * @return int
     */
    public function getTimeStamp() {
	return $this->timeStamp;
    }

    // </editor-fold>
}

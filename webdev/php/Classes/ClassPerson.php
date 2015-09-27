<?php

require_once 'Mark.php';

class ClassPerson {

    private $name = Array();
    private $contacts = Array();
    private $bDate = 0;
    private $status = 's';
    private $grade = NULL;

    function __construct($id) {
	if (intval($id)) {
	    $result = safeQuery("SELECT * FROM user__overview WHERE id = $id;");
	    if (mysql_num_rows($result) == 1) {
		$row = mysql_fetch_assoc($result);
		$this->name = array($row['name'], $row['surname'], $row['username']);
		$this->contacts = array(
		    'email' => $row['mail'],
		    'phone' => $row['phone'],
		    'street' => $row['street'],
		    'zipCode' => $row['postalcode'],
		    'region' => $row['region']
		);
		$this->bDate = strtotime($row['birthday']);
		$this->status = $row['status'];
		if ($this->status != 't') {
		    $this->grade = (int) $row['grade'];
		}
	    } else {
		$this->name = NULL;
	    }
	}
    }

    // <editor-fold defaultstate="collapsed" desc="Getter">

    /**
     * Returns the possible namestates
     * @return array
     */
    public function getName() {
	return $this->name;
    }

    /**
     * Returns the contact data
     * @return array
     */
    public function getContacts() {
	return $this->contacts;
    }

    /**
     * Returns the birthday
     * @return string
     */
    public function getBDate() {
	return $this->bDate;
    }

    /**
     * Returns weather the person is a student or a teacher
     * @return string[1]
     */
    public function getStatus() {
	return $this->status;
    }

    /**
     * Returns the grade the student is in
     * @return int
     *      If it's a teacher it will return -1
     */
    public function getGrade() {
	return $this->grade == NULL ? -1 : $this->grade;
    }

    //Other Getter
    public function isValid() {
	return !is_null($this->name);
    }

    // </editor-fold>

    /**
     * staticGetName($id)
     * @static
     * Returns the name of a person without initing an object
     * @param int $id
     *	    The id of the person of whom you want the name
     * @return string
     *	    The Name as a string
     */
    public static function staticGetName($id) {
	if (intval($id) != 0) {
	    $result = safeQuery("SELECT CONCAT(`name`, '  ', surname) FROM user__overview WHERE id = $id;");
	    if (mysql_num_rows($result) == 1) {
		$row = mysql_fetch_row($result);
		return $row[0];
	    }
	}
	return '';
    }

}

?>

<?php

class ClassPerson {

    private $name = [];
    private $profilePic = NULL;
    private $contacts = [];
    private $bDate = 0;
    private $status = 's';
    private $grade = NULL;

    function __construct($id) {
	if (intval($id)) {
	    $result = safeQuery("SELECT * FROM user__overview WHERE id = $id;");
	    if (mysql_num_rows($result) == 1) {
		$row = mysql_fetch_assoc($result);
		$this->name = [$row['name'], $row['surname'], $row['username']];
		$this->profilePic = NULL;
		$this->contacts = [
		    'email' => $row['mail'],
		    'phone' => $row['phone'],
		    'street' => $row['street'],
		    'zipCode' => $row['postalcode'],
		    'region' => $row['region']
		];
		$this->bDate = strtotime($row['birthday']);
		$this->status = $row['status'];
		if ($this->status != 't') {
		    $this->grade = intval($row['grade']);
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
     * Returns the url of the profile picutre of the user.
     * @return string
     */
    public function getProfilePic() {
	return (is_null($this->profilePic)) ? '' : $this->profilePic;
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
     * 	    The id of the person of whom you want the name
     * @return string
     * 	    The Name as a string
     */
    public static function staticGetName($id, $nickname = true) {
	$intId = intval($id);
	$column = $nickname ? 'username' : 'CONCAT(`name`, " " , surname)';
	$result = safeQuery("SELECT $column FROM user__overview WHERE id = $intId;");
	if (mysql_num_rows($result) == 1) {
	    $row = mysql_fetch_row($result);
	    return $row[0];
	}
    }

}

?>

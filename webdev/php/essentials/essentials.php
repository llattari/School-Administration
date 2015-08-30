<?php

require_once 'databaseEssentials.php';

/**
 * isIn($search, $string)
 * @return boolean
 * 	Whether the search is a substring of string
 */
function isIn($search, $string) {
    return is_int(strpos($search, $string));
}

/**
 * check($mail, $tel, $zip)
 * 	Validates the mail, telephone and zipcode (not all arguments are required)
 * @param string $mail
 * @param string $tel
 * @param string $zip
 * @return boolean
 */
function check($mail = NULL, $tel = NULL, $zip = NULL) {
    if($mail != NULL){
	if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
	    return false;
	}
    }
    if($tel != NULL){
	if(preg_match('/(\d{4}[ -\/]?)?\d+/is', $tel) == 0){
	    return false;
	}
    }
    if($zip != NULL){
	if(preg_match('/\d+/is', $zip) == 0){
	    return false;
	}
    }
    return true;
}

?>
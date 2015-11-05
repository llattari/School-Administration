<?php

require_once 'databaseEssentials.php';

/**
 * isIn($search, $string)
 * @return boolean
 * 	Whether the search is a substring of string
 */
function isIn($search, $string) {
    return is_int(strpos($string, $search));
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
    if ($mail != NULL) {
	if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
	    return false;
	}
    }
    if ($tel != NULL) {
	if (preg_match('/(\d{4}[ -\/]?)?\d+/is', $tel) == 0) {
	    return false;
	}
    }
    if ($zip != NULL) {
	if (preg_match('/\d+/is', $zip) == 0) {
	    return false;
	}
    }
    return true;
}

/**
 * validId($var)
 * @param int $var
 * @return boolean
 */
function validId($var) {
    return $var > 0;
}

/**
 * roundUp($value, $decimalPlaces)
 *  Rounds a number up to a certain accuracy
 * @param float $value
 * 	The value that should be rounded
 * @param int $decimalPlaces
 * 	Amount of digits after the decimal point
 * @return float
 * 	Returns a float or an int if $decimalPlaces = 0
 */
function roundUp($value, $decimalPlaces = 0) {
    $digit = (int) $decimalPlaces;
    return ceil($value * pow(10, $digit)) / pow(10, $digit);
}

/**
 * roundDown($value, $decimalPlaces)
 *  Rounds a number down to a certain accuracy
 * @param float $value
 * 	The value that should be rounded
 * @param int $decimalPlaces
 * 	Amount of digits after the decimal point
 * @return float
 * 	Returns a float or an int if $decimalPlaces = 0
 */
function roundDown($value, $decimalPlaces = 0) {
    $digit = (int) $decimalPlaces;
    return floor($value * pow(10, $digit)) / pow(10, $digit);
}

/**
 * isDate($date)
 * 	Checks if a string is a valid date
 * @param string $date
 * @return boolean
 */
function isDate($date) {
    if (!isset($date) && strtotime($date) === false) {
	return false;
    }
    return true;
}

?>
<?php

/**
 * timeSelector($name, $mode)
 * @param string $name
 * 	The name of the select field
 * @param int $mode
 * 	Either 12 or 24 hours. If the value is neither 24 will be chosen by default
 * @return string
 * 	Returns a working HTML select tag
 */
function timeSelector($name = NULL, $mode = 24) {
    //Hours
    $nameAttribH = ($name == NULL) ? '' : ' name="' . $name . 'H"';
    $finalString = "<select$nameAttribH>";
    for ($i = 0; $i < 24; $i++) {
	$finalString.='<option value="' . $i . '">' . toTimeFormat($i, $mode) . '</option>';
    }
    $finalString.= '</select> : ';

    //Minutes
    $nameAttribM = ($name == NULL) ? '' : ' name="' . $name . 'M"';
    $finalString .= "<select$nameAttribM>";
    for ($i = 0; $i < 60; $i++) {
	$formated = ($i < 10) ? '0' . $i : $i;
	$finalString.='<option value="' . $i . '">' . $formated . '</option>';
    }
    $finalString.= '</select>';
    return $finalString;
}

/**
 * toTimeFormat($hours, $timeformat)
 * @param int $hours
 * 	The hours that you want to convert
 * @param int $timeFormat
 * 	The timeformat either 12 or 24 if the value is neither 24 will be used as default
 * @return int
 */
function toTimeFormat($hours, $timeFormat = 24) {
    $normalized = $hours % 24;
    if ($timeFormat != 12) {
	return ($normalized < 10) ? '0' . $normalized : $normalized;
    }
    switch ($normalized) {
	case 0:
	    return '12 am';
	case 12:
	    return '12 pm';
	default:
	    $modNormalized = $normalized % 12;
	    $formated = ($modNormalized < 10) ? '0' . $modNormalized : $modNormalized;
	    if ((int) ($normalized / 12) == 0) {
		return $formated . ' am';
	    }
	    return $formated . ' pm';
    }
}

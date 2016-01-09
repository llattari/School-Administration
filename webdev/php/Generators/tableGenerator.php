<?php

function generateSpecialRow($inTR, $arrayOfTDs) {
    $finalString = "<tr $inTR>";
    //for every element in the array gererate a td-Tag
    for ($i = 0; $i < count($arrayOfTDs); $i++) {
	$string = is_null($arrayOfTDs[$i]) ? '--' : $arrayOfTDs[$i];
	$finalString.="<td>$string</td>";
    }
    $finalString.='</tr>';
    //Return the final row
    return $finalString;
}

function generateTableRow($arrayOfTDs, $specials = '') {
    return generateSpecialRow($specials, $arrayOfTDs);
}

function generateTableHead($arrayOfHeadVals) {
    return '<thead>' . generateTableRow($arrayOfHeadVals) . '</thead>';
}

?>
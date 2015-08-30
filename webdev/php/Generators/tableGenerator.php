<?php

function generateSpecialRow($inTR, $arrayOfTDs) {
    $finalString = "<tr $inTR>";
    //for every element in the array gererate a td-Tag
    for($i = 0; $i < count($arrayOfTDs); $i++){
	$finalString.='<td>' . $arrayOfTDs[$i] . '</td>';
    }
    $finalString.='</tr>';
    //Return the final row
    return $finalString;
}

function generateTableRow($arrayOfTDs) {
    return generateSpecialRow('', $arrayOfTDs);
}

function generateTableHead($arrayOfHeadVals) {
    return '<thead>' . generateTableRow($arrayOfHeadVals) . '</thead>';
}

?>
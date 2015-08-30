<?php

function generateUserSelect($name = '', $excludeSelf = false) {
    $finalResult = '<select ';
    if($name != ''){
	$finalResult.='name="' . $name . '">';
    }
    $result = safeQuery('SELECT id, username FROM user__overview;');
    if(mysql_num_rows($result) != 0){
	while($row = mysql_fetch_row($result)){
	    if($row[0] == $_SESSION['studentId'] && $excludeSelf){
		$finalResult.='<option value="' . $row[0] . '">' . $row[1] . '</option>';
	    }
	}
    }
    $finalResult.='</select>';
    return $finalResult;
}

function uniquify($list) {
    $uniqueList = [];
    for($i = 0; $i < count($list); $i++){
	if(array_search($list[$i], $uniqueList) === False){
	    array_push($uniqueList, $list[$i]);
	}
    }
    return $uniqueList;
}

function generateOption($list, $name = Null, $noneDefault = false) {
    $final = '<select';
    $final.=($name == Null) ? '>' : ' name="' . $name . '">';
    if($noneDefault){
	$final.='<option selected="selected"> None </option>';
    }
    for($i = 0; $i < count($list); $i++){
	$final.='<option>' . $list[$i] . '</option>';
    }
    $final.='</select>';
    return $final;
}

?>
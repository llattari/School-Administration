<?php

function generateSpeicalList($specialItems, $items, $type = 'ordered') {
    $finalString = '';
    for($i = 0; $i < count($items); $i++){
	$finalString.='<li ' . $specialItems . '>' . $items[$i] . '</li>';
    }
    $FirstType = substr($type, 0, 1) . 'l';
    return "<$FirstType>$finalString</$FirstType>";
}

function generateList($items, $type = 'ordered') {
    return generateSpeicalList('', $items, $type);
}

?>
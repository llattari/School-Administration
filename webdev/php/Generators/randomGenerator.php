<?php

function randomNumber($start = 0, $end = 1) {
    return rand($start, $end);
}

function randomString($length = 10, $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!$%&/()=') {
    $counter = rand(1, $length);
    $string = '';
    for($i = 0; $i < $counter; $i++){
	$randChar = $alphabet[rand(1, strlen($alphabet))];
	$string.=$randChar;
    }
    return $string;
}

function randomBoolean($true = 0.5) {
    $number = rand(0, 1);
    return $number >= $true;
}

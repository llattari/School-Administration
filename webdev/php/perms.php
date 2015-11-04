<?php

function getPermission() {
    $result = safeQuery('SELECT * FROM user__permission WHERE id = ' . $_SESSION['studentId'] . ';');
    $perms = Array();
    if ($result) {
	$row = mysql_fetch_assoc($result);
	unset($row['id']);
	foreach ($row as $key => $value) {
	    if (is_null($value) || $value == 0) {
		continue;
	    }
	    array_push($perms, $key);
	}
    }
    return $perms;
}

function hasPermission($permKey) {
    $perms = getPermission();
    return in_array($permKey, $perms);
}

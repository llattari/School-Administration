<?php
include_once '../../webdev/php/essentials/essentials.php';
connectDB();

function generateSESSION($userId){
	$id = (int)$userId;
	$key = randomString(30);
	$result = safeQuery('INSERT INTO api__session VALUES(NULL, $userId, $key);');
	return $key;
}

function checkSESSION($userId, $sessionKey){
	$id = (int) $userId;
	$key = escapeStr($sessionKey);
	$result = safeQuery("SELECT id FROM api__session WHERE userId=$id AND key = '$key';");
	return (mysql_num_rows($result)!=0);
}

function deleteSESSION($userId, $sessionKey){
	$id = (int) $userId;
	$key = escapeStr($sessionKey);
	$result = safeQuery("DELETE FROM api__session WHERE userId = $id AND key = '$key';");
	return (mysql_affected_rows($result)==1);
}
?>
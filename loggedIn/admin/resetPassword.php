
<?php

require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Generators/randomGenerator.php';
require_once '../../webdev/php/Classes/debuging/Logger.php';

$result = safeQuery('SELECT id FROM user__overview;');
$error = false;
while($row = mysql_fetch_row($result)){
    $len = randomNumber(0, 50);
    $randString = randomString($len);
    $suc = safeQuery(
	    'UPDATE user__password SET passwordSuffix = "' . $randString . '",
	    password = MD5(CONCAT("scnhjndur4hf389ur4h3fbäjqdjsdncsjkvdnkvj", username, "' . $randString . '"))
	    WHERE id = "' . $row[0] . '";');
    if(!suc){
	$error = true;
    }else{
	Logger::log('Updating password for the user ' . $row[0], Logger::USERMANAGEMENT);
    }
}
if($error){
    Message::castMessage('Execution failed');
}else{
    Message::castMessage('Password reset successfully done.', true);
}
?>
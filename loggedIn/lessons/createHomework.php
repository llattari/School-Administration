<?php

require_once '../../webdev/php/essentials/databaseEssentials.php';
require_once '../../webdev/php/Classes/Messages.php';
connectDB();

//Getting the information form the previous page
$topic = escapeStr($_POST['topic']);
$description = escapeStr($_POST['description']);
if(strlen($topic) == 0 || strlen($description) == 0){
    Message::castMessage('Topic or description is empty', false, 'homework.php');
}
if(isset($_POST['books'])){
    $books = explode(',', $_POST['books']);
    for($i = 0; $i < count($books); $i++){
	$books[$i] = trim($books[$i]);
    }
}
if(isset($_POST['worksheet'])){
    $worksheets = explode(',', $_POST['worksheet']);
    for($i = 0; $i < count($worksheets); $i++){
	$worksheets[$i] = trim($worksheets[$i]);
    }
}
$link = $_POST['link'];
if(strlen($link) == 0){
    if(filter_var($link, FILTER_VALIDATE_URL) === false){
	unset($link);
    }
}
safeQuery("INSERT INTO homework__overview(topic, description) VALUES ('$topic', '$description');");
$id = mysql_insert_id();
if(!(count($books) == 0 && count($worksheets) == 0 && !isset($link))){
    $sql = 'INSERT INTO homework__material(hwID, book, sheets, `link`) VALUES ' . "\n";
    for($i = 0; $i < count($books); $i++){
	$sql.="($id, " . $book[$i] . ', NULL, NULL),\n';
    }
    for($i = 0; $i < count($worksheets); $i++){
	$sql.="($id, NULL, '" . $worksheets[$i] . '\', NULL),';
    }
    if(isset($link)){
	$sql.="($id, NULL, NULL, '$link');";
    }else{
	$sql = substr($sql, 0, strlen($sql) - 2) . ';';
    }
    print_r($books);
    echo $sql . '<br />';
    $suc = safeQuery($sql);
    if($suc){
	Message::castMessage('Successfully added Homework', true, 'homework.php');
	Logger::log('A new homework (id: ' . $id . ') was created.', Logger::TASKMANAGEMENT);
    }
}
?>

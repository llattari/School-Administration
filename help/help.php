<!DOCTYPE html>
<?php
require_once '../webdev/php/essentials/databaseEssentials.php';
connectDB();

//If the get variable is set
$topic = (int) $_GET['topic'];
if($topic == 0){
    Header('Location: chooseTopic.php');
}else{
    $topicResult = safeQuery('SELECT topic FROM help__topics WHERE id = ' . $topic . ';');
    if(mysql_num_rows($topicResult) == 1){
	$topicRow = mysql_fetch_row($topicResult);
	$topicName = $topicRow[0];
    }else{
	$topicName = 'Unkown';
    }
}

$questions = [];
$answers = [];
$result = safeQuery("SELECT question, answer FROM help__main WHERE topic = $topic ORDER BY id;");
while($row = mysql_fetch_assoc($result)){
    array_push($questions, $row['question']);
    array_push($answers, $row['answer']);
}
?>
<html>
    <head>
	<title>Help page for the School administration software</title>
	<meta charset="UTF-8">
    </head>
    <body>
	<h1>All questions regarding "<?php echo $topicName; ?>"</h1>
	<div id="questionList">
	    <ul>
		<?php
		for($i = 0; $i < count($questions); $i++){
		    echo '<li class="question"><a href="#' . $i . '">' . $questions[$i] . '</a></li>';
		}
		?>
	    </ul>
	</div>
	<div id="answerList">
	    <?php
	    for($i = 0; $i < count($answers); $i++){
		echo '<div id="question' . $i . '">
	    <a name="' . $i . '"></a>
	    <h4>Q: ' . $questions[$i] . '</h4>
	    <p> ' . nl2br($answers[$i]) . ' </p>
	    </div>';
	    }
	    ?>
	</div>
	<a href="chooseTopic.php">Get back to the content overview</a>
    </body>
</html>
<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Generators/optionGenerator.php';

# Initng the HTML File
$HTML = new HTMLGenertator\HTMLfile('Create new To-Do', ['form.css'], NULL, NULL, 1);
$HTML->outputHeader();

if(isset($_GET['deadline']) && isset($_GET['topic']) && isset($_GET['prio'])){
    $predeadline = str_replace('/', '-', $_GET['deadline']);
    $deadline = strtotime($predeadline);
    $topic = escapeStr($_GET['topic']);
    $prio = 0;
    $suc = safeQuery('INSERT INTO task__toDo(classID, studentID, deadline, content, typeID, prio) VALUES '
	    . '(0,' . $_SESSION['studentId'] . ',"' . $deadline . '","' . $topic . '", 5, ' . $prio . ');');
    $todoId = mysql_insert_id();
    if(!$suc){
	echo 'The todo entry could not be created.';
    }else{
	echo 'Entry created';
	Logger::log('A new todo was created with the id: ' . $todoId, Logger::TASKMANAGEMENT);
    }
    Header('Location: create.php');
}
?>
<h1>Create new Task</h1>
<form action="#" method="GET">
    Deadline: <input type="date" name="deadline" placeholder="DD/MM/YYYY" />
    <br />
    Topic: <input type="text" name="topic" placeholder="Topic of the todo" />
    <br />
    Priority:
    <select name="prio">
	<?php
	$result = safeQuery('SELECT prioVal, content FROM task__priority');
	while($row = mysql_fetch_row($result)){
	    echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
	}
	?>
    </select>
    <br />
    <button type="submit">Add</button>
    <button type="reset">Reset</button>
</form>
<?php
$HTML->outputFooter();
?>
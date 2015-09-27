<?php
require_once('../../webdev/php/Generators/HTMLGenerator/Generator.php');
require_once('../../webdev/php/Classes/Friends.php');
require_once('../../webdev/php/Classes/ClassPerson.php');

# Creating objects
$HTML = new HTMLGenertator\HTMLfile('See all your friends', ['form.css'], NULL, NULL, 1);
$friends = new Friends($_SESSION['studentId']);
$HTML->outputHeader();
?>
<h1>All your friends are listed here</h1>

<!-- Pending friends -->
<div id="pending">
    <?php
    $pending = $friends->getPending();
    if($pending == NULL){
	echo 'You have no pending requests';
    }else{
	echo '<h2> Pending Request </h2>
		<ul>';
	foreach($pending as $key => $val){
	    echo '<li>' . ClassPerson::staticGetName($key);
	    if(!$val){
		echo ' - <a href="#">Accept</a> - <a href="#">Deny</a>';
	    }
	    echo '</li>';
	}
	echo '</ul>';
    }
    ?>
</div>

<!-- Accepted friends -->
<p>
    <?php
    $accepted = $friends->getAccepted();
    if($accepted == NULL){
	echo "You have no friends";
    }else{
	echo '<h2> Friends </h2>
			<ul>';
	for($i = 0; $i < count($accepted); $i++){
	    echo '<li>' . ClassPerson::staticGetName($accepted[$i]) . '</li>';
	}
	echo '</ul>';
    }
    ?>
</p>
<a href="addFriend.php">Add a new friend</a>

<!-- Sending new friend requests -->
<form action="addFriend.php" method="POST">
    <select name="newFriends[]">
        <option value="0" selected="">Please select a person.</option>
	<?php
	$result = safeQuery(
		'SELECT
		    user__overview.id AS "id",
		    CONCAT(name," ",surname) AS "name",
		    status
		FROM user__overview
		LEFT JOIN user__friends friend
		    ON user__overview.id = friend.fOne AND friend.fTwo = ' . $_SESSION['studentId'] . '
		    OR user__overview.id = friend.fTwo AND friend.fOne = ' . $_SESSION['studentId'] . '
		WHERE
		    user__overview.id != ' . $_SESSION['studentId'] . '
		AND
		    friend.fOne IS NULL
		ORDER BY status;');
	$type = '';
	if(mysql_num_rows($result) > 0){
	    $row = mysql_fetch_assoc($result);
	    $type = $row['status'];
	    echo '<optgroup label="' . $typeOf[$type] . '">';
	    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
	}else{
	    echo '>';
	}
	while($row = mysql_fetch_assoc($result)){
	    if($row['status'] != $type){
		echo '</optgroup><optgroup label="' . $typeOf[$type] . '">';
	    }
	    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
	}
	echo '</optgroup>';
	?>
    </select>
    <br />
    <button type="submit">Send request</button>
</form>
<?php
//to-do implement friend listing
$HTML->outputFooter();
?>

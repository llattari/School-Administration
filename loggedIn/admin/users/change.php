<?php
require_once '../../../webdev/php/Generators/HTMLGenerator/Generator.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id == 0) {
    $id = $_SESSION['studentId'];
}

$HTML = new \HTMLGenertator\HTMLfile('Change personal information', ['form.css'], ['checkForm.js'], NULL, 2);
$HTML->changeMenuFile(__DIR__ . '/../menu.php');
$HTML->outputHeader();
?>

<h1>Changing the profile</h1>
<form action="changeInformation.php" method="POST">
    <?php
    echo '
    <input type="hidden" value="' . $id . '" name="id" />
    <fieldset>
	<legend>Main information</legend>';
    // Echoing the main information
    $result = safeQuery('
	    SELECT *
	    FROM user__overview
	    WHERE id = ' . $id . '
	    ORDER BY status;');
    if (mysql_num_rows($result) != 0) {
	$row = mysql_fetch_assoc($result);
	foreach ($row as $key => $value) {
	    $uKey = ucfirst($key);
	    if ($key == 'id' || $key == 'username') {
		echo $uKey . ': ' . $value . '<br />';
		continue;
	    }
	    echo '<label for="' . $uKey . '">' . $uKey . ': </label>
	    <input name="' . $key . '" value="' . $value . '" type="text" placeholder="' . $uKey . '" id="' . $uKey . '" onchange="changed(this);"/><br />';
	}
    }
    echo '</fieldset>';
//Echoing the permission
    $permResult = safeQuery("
	SELECT *
	FROM user__permission
	WHERE id = $id;");
    echo '<fieldset>
	<legend>Permissions</legend>';
    if (mysql_num_rows($permResult) == 1) {
	$row = mysql_fetch_assoc($permResult);
	$keys = array_keys($row);
	for ($i = 1; $i < count($keys); $i++) {
	    $key = $keys[$i];
	    $has = is_null($row[$key]) ? 'no' : 'yes';
	    echo "$key: $has <br />";
	}
    } else {
	echo '<p>This user has no permissions assigned yet.</p>';
    }
    ?>
</fieldset>
<fieldset>
    <legend>Delete</legend>
    <?php
    if ($id != $_SESSION['studentId']) {
	echo '<a href="delete.php?id=' . $id . '">Delete user</a>';
    } else {
	echo 'You can not delete your own profile.';
    }
    ?>
</fieldset>
<button type="reset">Reset</button>
<button type="submit">Submit</button>
</form>
<?php
$HTML->outputFooter();
?>
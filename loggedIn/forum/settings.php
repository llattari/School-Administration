<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';

$HTML = new HTMLGenertator\HTMLfile('Forum', ['form.css', 'forum.css'], NULL, NULL);

require_once '../../webdev/php/Classes/ClassPerson.php';
require_once '../../webdev/php/Forum/Section.php';

if (isset($_GET['forumId'])) {
    $forumId = (int) $_GET['forumId'];
    $object = new Section($forumId);
} else if (isset($_GET['topicId'])) {
    $topicId = (int) $_GET['topicId'];
    $object = new Section($topicId, 'topic');
} else {
    Message::castMessage('Not available', false, 'index.php');
}
$settingsName = $object->getType();

$HTML->outputHeader();

echo "<h1>Change the $settingsName's setting</h1>";
?>
<form action="newSettings.php" method="POST">
    <input type="hidden" name="type" value="<?php echo $settingName; ?>" />
    <input type="hidden" name="idVal" value="<?php echo $object->getId(); ?>" />
    <fieldset>
	<legend>Alterable</legend>
	Name: <input type="text" name="newName" value="<?php echo $object->getName(); ?>" placeholder="New name"/>
	<br />
	Description: <textarea name="newDescription" placeholder="New name"/> <?php echo $object->getDescription(); ?> </textarea>
    </fieldset>
    <fieldset>
	<legend>Immutable</legend>
	ID: <?php echo $object->getId(); ?>
	<br />
	Creator: <?php
	$creator = (int) $object->getCreator();
	echo ClassPerson::staticGetName($creator, $_SESSION['nickName']);
	?>
    </fieldset>
    <button type="submit">Save settings</button>
    <button type="reset">Revert changes</button>
</form>
<?php $HTML->outputFooter(); ?>
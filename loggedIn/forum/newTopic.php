<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Forum/Forum.php';
require_once '../../webdev/php/Classes/Messages.php';

//Setting up the HTML and the forum object
$HTML = new HTMLGenertator\HTMLfile('Classes', ['form.css', 'forum.css'], NULL, NULL);
if (!isset($_GET['forumId'])) {
    Message::castMessage('Please select a forum first.', false, 'index.php');
}
$id = (int) $_GET['forumId'];
$forum = new Forum($id);

//Beginning the HTML page
$HTML->outputHeader();
?>
<h1>Create a new topic in the "<?php echo $forum->getName(); ?>"</h1>
<form action="createTopic.php" method="POST">
    <input type="hidden" name="forumId" value="<?php echo $id; ?>" />
    Topic name: <input type="text" name="topicName" maxlength="300" placeholder="Topic Name" />
    <br />
    Description: <br />
    <textarea placeholder="Describe what you want to talk about in this topic." name="description"></textarea>
    <br />
    <button type="submit">Create topic</button>
    <button type="reset">Discard</button>
</form>
<?php
$HTML->outputFooter();
?>
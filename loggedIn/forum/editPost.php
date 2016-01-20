<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/ClassPerson.php';
require_once '../../webdev/php/Forum/Post.php';

$HTML = new HTMLGenertator\HTMLfile('Topic', ['form.css', 'forum.css'], NULL, NULL);
$HTML->outputHeader();

$id = (isset($_GET['id'])) ? intval($_GET['id']) : -1;
$topicId = isset($_GET['topicId']) ? intval($_GET['topicId']) : 0;
$destination = 'readForum.php?topicId=' . $topicId;
if ($id == 0 || $id == -1) {
    Message::castMessage('Invalid post id', false, $destination);
}
$post = new Post($id);
//todo: Check if user has the permission to do that
?>
<h1>Editing the post of <?php echo ClassPerson::staticGetName($post->getCreator()); ?></h1>
<div id="originalPost">
    <span style="font-style: italic;">Original text:</span>
    <p style="display:block;">
	<?php echo $post->getMessage(); ?>
    </p>
</div>
<br class="clear"/>
<form action="submitEdit.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $id; ?>" />
    <input type="hidden" name="topicId" value="<?php echo $topicId; ?>" />
    <fieldset>
	<legend>Submit your edits</legend>
	<textarea name="newMessageText" placeholder="Please enter the new post here."><?php echo $post->getMessage(); ?></textarea>
    </fieldset>
    <button type="submit">Submit</button>
    <button type="reset">Reset</button>
</form>
<?php
$HTML->outputFooter();
?>


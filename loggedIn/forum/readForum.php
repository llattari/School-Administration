<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/ClassPerson.php';
require_once '../../webdev/php/Forum/Section.php';
require_once '../../webdev/php/Forum/Post.php';

$HTML = new HTMLGenertator\HTMLfile('Topic', ['form.css', 'forum.css'], NULL, NULL);
$HTML->outputHeader();

$object = NULL;
if (isset($_GET['forumId'])) {
    $intId = (int) $_GET['forumId'];
    $object = new Section($intId);
} elseif (isset($_GET['topicId'])) {
    $intId = (int) $_GET['topicId'];
    $object = new Section($intId, 'topic');
}

//Return if neither is set.
if ($object == NULL) {
    Header('Location: index.php');
}
$subList = $object->getSubList();
?>

<!-- Outputting the forum head-->
<h1><?php echo $object->getName(); ?></h1>
<p id="forumDescription">
    <?php echo $object->getDescription(); ?>
</p>
<hr />
<br />
<!-- Outputting the topics-->
<div id="topicArea">
    <ul>
	<?php if (is_null($subList)) { ?>
    	<li>
    	    <h3>There are no things to display.</h3>
    	    <p>Post a new topic down below.</p>
    	</li>
	    <?php
	} else {
	    for ($i = 0; $i < count($subList); $i++) {
		if ($object->getType() == 'forum') {
		    $subItem = new Section($subList[$i], 'topic');
		    $heading = '<a href="readForum.php?topicId=' . $subList[$i] . '">' . $subItem->getName() . '</a>';
		    $message = substr($subItem->getDescription(), 0, 50);
		} else {
		    $subItem = new Post($subList[$i]);
		    $heading = ClassPerson::staticGetName((int) $subItem->getCreator()) . ' says: ';
		    $message = $subItem->getMessage();
		}
		echo
		"<li><h3>$heading</h3><p>$message</p></li>";
	    }
	}
	?>
    </ul>
</div>
<?php
if ($object->getType() == 'forum') {
    echo '<a href="newTopic.php?forumId=' . $intId . '">Create new topic</a>';
} else {
    ?>
    <form action="postNew.php" method="POST">
        <!-- Copying data from the previous page -->
        <input type="hidden" name="topicId" value="<?php echo $intId;
    ?>" />
        <!-- Textarea for the post message -->
        <fieldset>
    	<legend>New Post</legend>
    	<textarea name="postMessage" placeholder="What do you want to tell the world?"></textarea>
    	<br />
    	<button type="submit">Post</button>
    	<button type="reset">Discard</button>
        </fieldset>
    </form>
<?php } ?>
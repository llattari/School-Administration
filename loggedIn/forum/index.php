<?php
require_once '../../webdev/php/Generators/HTMLGenerator/Generator.php';
require_once '../../webdev/php/Classes/ClassPerson.php';
require_once '../../webdev/php/Forum/Forum.php';
require_once '../../webdev/php/Forum/Topic.php';

$HTML = new HTMLGenertator\HTMLfile('Classes', ['form.css', 'forum.css'], NULL, NULL);

$HTML->outputHeader();
if (!isset($_GET['forumId'])) {
    ?>
    <h1>Select the forum</h1>
    <form action="index.php" method="GET">
        Select a course for the forum:
        <select name="forumId">
	    <?php
	    $result = safeQuery(
		    'SELECT
		    classID, course__overview.subject
		FROM course__student
		JOIN course__overview
		ON course__overview.id = course__student.classID
		WHERE studentID = ' . $_SESSION['studentId'] . ';');
	    while ($row = mysql_fetch_row($result)) {
		echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
	    }
	    ?>
        </select>
        <br />
        <button type="submit">Select</button>
    </form>
    <?php
} else {
    $id = (int) $_GET['forumId'];

    $forum = new Forum($id);
    $allTopics = $forum->getTopics();
    ?>
    <!-- Outputting the forum head-->
    <h1><?php echo $forum->getName(); ?></h1>
    <p id="forumDescription">
	<?php echo $forum->getDescription(); ?>
    </p>
    <hr />
    <a href="newTopic.php?forumId=<?php echo $id; ?>">Create new topic</a>
    <br />
    <br />
    <!-- Outputting the topics-->
    <div id="topicArea">
        <ul>
	    <?php
	    if (is_null($allTopics)) {
		echo '<li><h3>There are no topics to display.</h3>
		    <p>Create one with the "Create new topic" button.</p></li>';
	    } else {
		for ($i = 0; $i < count($allTopics); $i++) {
		    $currentTopic = new Topic($allTopics[$i]);
		    $creator = $currentTopic->getCreatorId();
		    $createdUser = '<a href="../profile/profile.php?id=' . $creator . '">' . ClassPerson::staticGetName($creator) . '</a>';
		    echo '<li><h3><span class="underline">' . $currentTopic->getName() . '</span> [created by: ' . $createdUser . ']</h3>
		    <p>' . $currentTopic->getDescription() . '</p></li>';
		}
	    }
	    ?>
        </ul>
    </div>
    <?php
}

$HTML->outputFooter();
?>

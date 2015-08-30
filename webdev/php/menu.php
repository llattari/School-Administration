<?php
require_once 'Generators/Menugenerator/MenuGenerator.php';
?>

<div id="menu">
    <ul>
	<?php
	//Setting the Menu
	$menu = new MenuGenerator();
	$items = Array();

	//General overview
	$items[0] = new MenuEntry('index.php', 'Overview');

	//Lessons
	$items[1] = new MenuEntry('lessons/index.php', 'Lesson');
	if($_SESSION['teacher']){
	    $items[1]->addItem(new MenuEntry('lessons/homework.php', 'Homework'));
	}

	//Classes
	$items[2] = new MenuEntry('classOverview.php', 'Classes');
	$items[2]->addItem(new MenuEntry('timetable.php', 'Timetable'));
	//if (!$_SESSION['teacher']) {
	$items[2]->addItem(new MenuEntry('marks/showMarks.php', 'Marks'));
	//}
	//Mails
	$items[3] = new MenuEntry('mails/read.php', 'Mails');
	$items[3]->addItem(new MenuEntry('mails/write.php', 'Write message'));

	//Information
	$items[4] = new MenuEntry('information.php', 'Information');
	$items[4]->addItem(new MenuEntry('calenderManager.php', 'Calendar'));

	//Tools
	$items[4] = new MenuEntry('tasks/list.php', 'Tools');
	$items[4]->addItem(new MenuEntry('tasks/create.php', 'To-Do'));
	$items[4]->addItem(new MenuEntry('profile/settings.php', 'Settings'));

	//Friends
	$items[5] = new MenuEntry('friends/index.php', 'Friends');
	$items[5]->addItem(new MenuEntry('friends/chat.php', 'Chat'));

	//Logout
	$items[6] = new MenuEntry('logOut.php', 'Log out');

	$menu->setItem($items);
	echo (string) $menu;
	?>
    </ul>
</div>
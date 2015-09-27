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
	$items[0]->addItem(new MenuEntry('lessons/timetable.php', 'Timetable'));

	//Lessons
	$items[1] = new MenuEntry('lessons/index.php', 'Lesson');
	if ($_SESSION['teacher']) {
	    $items[1]->addItem(new MenuEntry('lessons/homework.php', 'Homework'));
	}

	//Classes
	$items[2] = new MenuEntry('classOverview.php', 'Classes');
	//List of all the classes
	//Marks
	$items[3] = new MenuEntry('marks/showMarks.php', 'Marks');

	//Mails
	$items[4] = new MenuEntry('mails/read.php', 'Mails');
	$items[4]->addItem(new MenuEntry('mails/trash.php', 'Trash'));
	$items[4]->addItem(new MenuEntry('mails/write.php', 'Write message'));

	//Tools
	$items[5] = new MenuEntry('tasks/list.php', 'Tools');
	//$items[5]->addItem(new MenuEntry('tasks/create.php', 'To-Do'));
	$submenu = new MenuEntry('calendar/index.php', 'Calendar');
	$submenu->addItem(new MenuEntry('calendar/create.php', 'Create Event'));
	$items[5]->addItem($submenu);
	$items[5]->addItem(new MenuEntry('information.php', 'Ticker'));
	$items[5]->addItem(new MenuEntry('profile/settings.php', 'Settings'));

	//Friends
	$items[6] = new MenuEntry('friends/index.php', 'Friends');
	$items[6]->addItem(new MenuEntry('friends/chat.php', 'Chat'));

	//Logout
	$items[7] = new MenuEntry('logOut.php', 'Log out');

	$menu->setItem($items);
	echo (string) $menu;
	?>
    </ul>
</div>
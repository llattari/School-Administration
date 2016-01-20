<?php
require_once 'Generators/Menugenerator/MenuGenerator.php';
?>

<div id="menu">
    <ul>
	<?php
	$perms = getPermission();
	//Setting the Menu
	$menu = new MenuGenerator();
	$items = [];

	//General overview
	$items[0] = new MenuEntry('index.php', 'Overview');
	$items[0]->addItem(new MenuEntry('lessons/timetable.php', 'Timetable'));
	if (in_array('admin', $perms)) {
	    $items[0]->addItem(new MenuEntry('admin/index.php', 'Admin pannel'));
	}

	//Lessons
	$items[1] = new MenuEntry('lessons/index.php', 'Lesson');
	if (in_array('teacher', $perms)) {
	    $items[1]->addItem(new MenuEntry('lessons/homework.php', 'Homework'));
	}

	//Classes
	$items[2] = new MenuEntry('classOverview.php', 'Classes');

	//Marks
	$items[3] = new MenuEntry('marks/showMarks.php', 'Marks');
	if (in_array('teacher', $perms)) {
	    $items[3]->addItem(new MenuEntry('marks/setMarks.php', 'Add marks'));
	}

	//Mails
	$items[4] = new MenuEntry('mails/read.php', 'Mails');
	$items[4]->addItem(new MenuEntry('mails/write.php', 'Write message'));
	$items[4]->addItem(new MenuEntry('mails/trash.php', 'Trash'));

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

	//Forum
	$items[7] = new MenuEntry('forum/index.php', 'Forum');

	//Logout
	$items[8] = new MenuEntry('logOut.php', 'Log out');

	$menu->setItem($items);
	echo (string) $menu;
	?>
    </ul>
</div>
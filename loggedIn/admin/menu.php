<?php
require_once __DIR__ . '/../../webdev/php/Generators/Menugenerator/MenuGenerator.php';

//Checking the permission
$perms = getPermission();
if (!in_array('admin', $perms)) {
    Header('Location: ../index.php');
}
?>

<div id="menu">
    <ul>
	<?php
	//Setting the Menu
	$menu = new MenuGenerator();
	$items = [];

	//User management
	$items[0] = new MenuEntry('admin/users/list.php', 'User administration');
	//$items[0]->addItem(new MenuEntry('admin/users/perms.php', 'List permissions'));
	$items[0]->addItem(new MenuEntry('admin/users/change.php', 'Change profile'));
	$items[0]->addItem(new MenuEntry('admin/users/create.php', 'Create User'));

	//Overview features
	$items[1] = new MenuEntry('admin/course/list.php', 'Course overview');
	$items[1]->addItem(new MenuEntry('admin/course/change.php', 'Change course'));
	$items[1]->addItem(new MenuEntry('admin/course/add.php', 'Add course'));

	//Content settings
	$items[2] = new MenuEntry('admin/contentSystem.php', 'Content settings');

	//Logout
	$items[3] = new MenuEntry('../index.php', 'Back');
	$items[3]->addItem(new MenuEntry('../logOut.php', 'Log out'));

	$menu->setItem($items);
	echo (string) $menu;
	?>
    </ul>
</div>

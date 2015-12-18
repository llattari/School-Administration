<?php

require_once '../../webdev/php/Classes/Messages.php';
require_once '../../webdev/php/Forum/Section.php';
require_once '../../webdev/php/essentials/databaseEssentials.php';

$type = $_POST['type'];
$intId = (int) $_POST['idVal'];

//Creating object based on the settings type.
$object = NULL;
if ($type == 'forum' || $type == 'topic') {
    $object = new Section($intId, $type);
} else {
    Message::castMessage('Invalid type. Don\'t mess with the sourcecode.', false, 'index.php');
}

//Changing the settings.
$sucName = $object->setName($_POST['newName']);
$sucDesc = $object->setDescription($_POST['newDescription']);

if ($sucName && $sucDesc) {
    Message::castMessage('Successfully changed the settings.', true, 'settings.php?' . $type . 'Id=' . $intId);
}else{
    Message::castMessage('Could not change settings.', false, 'settings.php?' . $type . 'Id=' . $intId);
}

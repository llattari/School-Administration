<?php
include_once '../webdev/php/essentials/essentials.php';
include_once 'helper/sessionGenerator.php';

connectDB();
$suc = false;
$id = 0;

$username = escapeStr($_POST['username']);
$password = escapeStr($_POST['password']);

$request = safeQuery("SELECT id FROM user__overview WHERE `username`= '$username';");
if(mysql_num_rows($request)!=0){
	$row = mysql_fetch_row($request);
	$id = $row[0];
	//Comparing password
	$suc = true;
}
if($suc){
?>
<?xml version="1.0" encoding="UTF-8"?>
<data>
	<error>
		<value>false</value>
	</error>
	<information>
		<session>
			<id><?php echo $id; ?></id>
			<loginDate><?php echo time(); ?></loginDate>
			<key><?php echo generateSESSION($id); ?></key>
		</session>
	</information>
</data>
<?php
}else{
?>
<data>
	<error>
		<value>true</value>
		<type>invalidLoginData</type>
		<message>You're login data was invalid. Please check the information entered.</message>
	</error>
	<information>
	</information>
</data>
<?php
}
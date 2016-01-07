<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>API Homepage</title>
	</head>
	<body>
		<div>
			<h1>Index of all API files</h1>
			<ul>
				<?php
					if($handle = opendir('.')){
						while (($entry = readdir($handle))!== false) {
							if(is_file($entry)){
								echo "<li>$entry</li>";
							}
						}
					}
				?>
			</ul>
			<h1>Index of the API Documentation</h1>
			<ul>
				<?php
					if($DOChandle = opendir('docs')){
						while (($entry = readdir($DOChandle))!== false) {
							if(is_file($entry)){
								echo "<li>$entry</li>";
							}
						}
					}
				?>
			</ul>
		</div>
	</body>
</html>
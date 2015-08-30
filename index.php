<html>
    <head>
        <title>Login to use the service</title>
        <meta charset="UTF-8">
    </head>
    <body>
	<?php
	require_once './webdev/php/Classes/Messages.php';
	Message::show();
	session_start();
	if(isset($_SESSION['studentId'])){
	    Header('Location: loggedIn/index.php');
	}
	?>
        <form action="loginFWD.php" method="POST">
            Username: <input type="text" maxlength="100" name="username" placeholder="Username" />
            <br />
            Password: <input type="password" name="psw" placeholder="Password"/>
            <br />
            <button type="reset">
                Reset
            </button>
            <button type="submit">
                Login
            </button>
        </form>
    </body>
</html>


<html>
    <head>
        <title>Login to use the service</title>
        <meta charset="UTF-8">
	<link rel="stylesheet" href="webdev/stylesheets/main/login.css" type="text/css" />
	<link rel="stylesheet" href="webdev/stylesheets/main/main.css" type="text/css" />
	<link rel="stylesheet" href="webdev/stylesheets/main/form.css" type="text/css" />
    </head>
    <body>
	<div id="loginField">
	    <?php
	    require_once './webdev/php/Classes/Messages.php';
	    Message::show();
	    session_start();
	    if (isset($_SESSION['studentId'])) {
		Header('Location: loggedIn/index.php');
	    }
	    ?>
	    <h1>Welcome to the school administration software.</h1>
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
	    <hr />
	    <h2>Do you need any help?</h2>
	    <br />
	    <a href="help/help.php">Click here</a>
	</div>
    </body>
</html>


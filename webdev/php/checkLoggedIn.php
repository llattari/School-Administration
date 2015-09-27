<?php

//Starting the session
session_start();
// Default check whether the user is logged in or not
if (!isset($_SESSION['studentId'])) {
    Header('Location: ../index.php');
}
?>
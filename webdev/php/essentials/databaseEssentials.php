<?php

/**
 * Connects to the database and therefore stores the information in this place.
 * @return boolean
 *      If the connection failed, it returns false
 */
function connectDB() {
    $connection = mysql_connect('localhost', 'headmasterLogin', '7YUCmLdQ8eByUu57');
    if ($connection) {
	$connection = mysql_select_db('schulverwaltung') or die('Database connection failed!');
    }
    return $connection;
}

/**
 * Escapes a string and makes it ready for database-insertion
 * @param String $input
 *      The String that should be escaped
 * @return String
 *      The escaped String
 */
function escapeStr($input) {
    $escaped = nl2br(htmlentities(mysql_real_escape_string($input)));
    return $escaped;
}

/**
 * Offers debugging information and saves it to the database
 * @param String $query
 * 	The query that you want to execute
 * @param boolean $debug
 * 	Should this querry be debugged (outputting errors)
 * @return SQLresult
 */
function safeQuery($query, $debug = true) {
    $success = mysql_query($query);
    $error = mysql_error();
    if (!$success) {
	$escapedQuery = trim(mysql_real_escape_string($query));
	$escapedError = mysql_real_escape_string($error);
	echo ($debug) ? $error : '';
	//Getting the script that executes that query.
	$url = explode('?', $_SERVER['REQUEST_URI']);
	$script = $url[0];
	//If there are parameters set pass it to the variable otherwise set it NULL
	$paramList = (strlen($_SERVER['QUERY_STRING']) == 0) ? NULL : mysql_real_escape_string($_SERVER['QUERY_STRING']);
	//Issue query
	mysql_query("INSERT INTO debug__debugger VALUES (NULL, '$escapedQuery','$script', '$paramList', '$escapedError');");
    }
    return $success;
}

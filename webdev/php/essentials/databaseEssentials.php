<?php

//----------------------- DATABASE TOOLS -----------------------

/**
 * connectDB()
 *       Connects to the database and therefore stores the information in this place.
 * @return boolean
 *      If the connection failed, it returns false
 */
function connectDB() {
    $connection = mysql_connect('localhost', 'headmasterLogin', '7YUCmLdQ8eByUu57');
    if($connection){
	$connection = mysql_select_db('schulverwaltung') or die('Database connection failed!');
    }
    return $connection == true;
}

/**
 * escapeStr($input)
 *      Escapes a string and makes it ready for database-insertion
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
 * safeQuery()
 * 	Offers debugging information and saves it to the database
 * @param String $query
 * 	The query that you want to execute
 * @param String $script
 * 	The name of the script that is executing this
 * @return SQLresult
 */
function safeQuery($query) {
    $success = mysql_query($query);
    if(!$success){
	$escapedQuery = mysql_real_escape_string($query);
	//Getting the script that executes that query.
	$url = explode('?', $_SERVER['REQUEST_URI']);
	$script = $url[0];
	//If there are parameters set pass it to the variable otherwise set it NULL
	$paramList = (strlen($_SERVER['QUERY_STRING']) == 0) ? NULL : mysql_real_escape_string($_SERVER['QUERY_STRING']);
	//Issue query
	mysql_query('INSERT INTO debug__debugger
	    VALUES(NULL, "' . $escapedQuery . '","' . $script . '", "' . $paramList . '", "' . escapeStr(mysql_error()) . '");');
    }
    return $success;
}

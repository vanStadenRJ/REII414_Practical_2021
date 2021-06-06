<?php
	$mysqli = NEW MySQLi("127.0.0.1", "root", "WubieMariaDB1234#", "php_forum");
	
	// IF CONNECTION FAILS - STOP!
	if ($mysqli->connect_error) {
		die('Database Error: ' . $mysqli->connect_error);
	}
	
?>
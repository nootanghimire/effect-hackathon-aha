<?php 

	//MySQLi Database connection stuff here
	
	//Require Configuration
	require_once 'config.php';

	$db = $config['database']; 

	$db_link = mysqli_connect($db['hostname'], $db['username'],
	 $db['password'], $db['database_name']);
	
	if (!$db_link) {
		$err_string = "Could not connect to MySQL database ";
		$err_string .= "because of the following error: \n";
		$err_string .= mysqli_error($db_link);
		die(json_encode(array('error' => true, 'desc' => $err_string)));
	}
?>
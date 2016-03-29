<?php 
	
	//Don't bother about Auth for now. Assume people are auth'ed. 
	//
	//
	//POST Params:
	//	user_id: User ID
	//	post_id: ID of the post
	//	USE TIMESTAMP (CURRENT)
	//	
	//	
	//Return: JSON
	//	{error: false}
	//	OR
	//	{error: true, desc:"Error Description"}

	require_once 'database.php';

	function check_required_params($array_of_params){
		$return['error'] = false;
		foreach ($array_of_params as $key => $value) {
			if(!isset($_POST[$value])){
				$return['error'] = true;
				$return['desc'] = "Missing Required Param: " . $value;
				break;
			}
		}
		return $return;
	}

	function p($index){
		return $_POST[$index];
	}

	$are_requirements_met = check_required_params(['user_id', 'post_id']);

	if($are_requirements_met['error']) {
		die(json_encode($are_requirements_met));
	}

	$query_to_check = "SELECT removed FROM posts WHERE id=". p('post_id'). ";";
	$result_one = mysqli_query($db_link, $query_to_check);

	if(mysqli_num_rows($result_one) < 1) {
		# Oops
		die(json_encode(array('error' => true, 'desc' => 'Post does not exist! Refresh?')));
	}

	#Good to go;
	#
	
	$is_removed = mysqli_fetch_assoc($result_one)['removed'];

	if($is_removed == 1) {
		#Post Removed
		die(json_encode(array('error' => true, 'desc' => 'Post was removed! Refresh?')));
	}

	$query_check_already_flagged = "SELECT * FROM flags WHERE user_id = " . p('user_id') . " AND post_id = " . p('post_id') .");" ; 

	$result_two = mysqli_query($db_link, $query_check_already_flagged);

	if(mysqli_num_rows($result_two) > 0) {
		# Oops
		die(json_encode(array('error' => true, 'desc' => 'Already Flagged?')));
	}

	$base_query = "INSERT INTO flags VALUES(NULL, " . p('post_id') . ", " . p('user_id') . ", " . time() . ");";

	$result = mysqli_query($base_query);

	if(!$result){
		$error_string = "Something Weird Happened:: ". mysqli_error($db_link);
		die(json_encode(array('error' => true , 'desc' =>  $error_string)));
	}

	die(json_encode(array('error' => false)));

 ?>
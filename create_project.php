<?php 
	
	//Don't bother about Auth for now. Assume people are auth'ed. 
	//
	//
	//POST Params:
	//	user_id: User ID
	//	org_name: Organization Name
	//	project_name: Project Name
	//	country_name: Name of Country Where the Project is Being Held
	//	lat_long: Comma Seperated Latitude and Longitude of Project
	//	relief_type: Type of Relief
	//	status: Assesment, Response, Recovery, Accomplished
	//	date_started: timestamp of Starting Date
	//	date_ends:	timestamp of date ending, (optional)
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

	$are_requirements_met = check_required_params(['user_id', 'org_name', 'country_name', 'lat_long', 'relief_type', 'date_started']);
	
	if($are_requirements_met['error']) {
		die(json_encode($are_requirements_met));
	}

	//Start inserting stuff
	//
	


	$base_query = "INSERT INTO projects VALUES(NULL, '" . p('org_name') . "', '" . p('project_name') . "', '" . p('country_name') . "', '" . p('lat_long') . "', '" . p('relief_type') . "', '" . p('status') . "', " . p('date_started') . "";
	$add_to_query = ");";

	if(isset($_POST['date_ends'])) {
		$add_to_query = ", " . p('date_ends') . ");"; 
	}	

	$final_query = $base_query . $add_to_query ;

	$result = mysqli_query($db_link, $final_query);

	if(!$result){
		$error_string = "Something Weird Happened:: ". mysqli_error($db_link);
		die(json_encode(array('error' => true , 'desc' =>  $error_string)));
	}

	die(json_encode(array('error' => false)));

 ?>
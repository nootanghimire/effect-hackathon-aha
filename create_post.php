<?php 
	
	//Don't bother about Auth for now. Assume people are auth'ed. 
	//
	//
	//POST Params:
	//	user_id: User ID
	//	project_id: ID of the Project with which the post is associated
	//	text_blob: The Actual Deal
	//	lat_long: Latitude and Longitude, Comma Seperated
	//	keywords: Self Explainatory
	//	video_path: Path of Video, Returned by upload_video.php ; will  be json
	//	images_path: JSON path of images, Returned by upload_images.php :: Format: images = ["path", "path"]
	//	is_text_from_video: 1 if the text is transcribed, 0 if not
	//	category: Post Category:: Social, Cultural, etc...
	//	access_control: staff, if only staff can see it, all if everyone can see it
	//	general: If the post is not project specific, general=1, otherwise this is not present or any other value
	//	USE TIMESTAMP (CURRENT)
	//	
	//	
	//Return: JSON
	//	{error: false, post_id:post_id}
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

	$are_requirements_met = check_required_params(['user_id', 'project_id', 'category', 'text_blob', 'is_text_from_video', 'lat_long', 'keywords', 'access_control']);
	
	if($are_requirements_met['error']) {
		die(json_encode($are_requirements_met));
	}

	//Start inserting stuff
	//
	

	$images_path = "";
	if(isset($_POST['images_path'])){
		$images_path = $_POST['images_path'];
	}

	$video_path = "";
	if(isset($_POST['video_path'])) {
		$video_path = $_POST['video_path'];
	}

	$general = 0;
	if(isset($_POST['general'])){
		if($_POST['general'] == 1){
			$general=1;
		}
	}


	$base_query = "INSERT INTO projects  VALUES(NULL, " . p('user_id') . ", " . p('project_id') . ", '" . p('category') . "', '" . p('text_blob') . "', " . p('is_text_from_video') . ", '" . $images_path . "', " . p('lat_long') . ", '" . p('keywords') . "', '" . $video_path . "', '" . p('access_control') . "', " . time() . ", " . $general . ", 0);";


	$final_query = $base_query; #No additional stuff . $add_to_query ;

	$result = mysqli_query($db_link, $final_query);

	if(!$result){
		$error_string = "Something Weird Happened:: ". mysqli_error($db_link);
		die(json_encode(array('error' => true , 'desc' =>  $error_string)));
	}

	die(json_encode(array('error' => false)));
 ?>
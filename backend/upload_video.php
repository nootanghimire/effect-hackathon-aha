<?php 


	// RETURN JSON of video path, maybe a single string
	// 
	// 
	// Need POST: video
	

	$upload_dir = "videos/";

	if(!isset($_FILES['video'])) {
		$return['error'] = true;
		$return['desc'] = "Missing Required Param: video";
		die(json_encode($return));
	}

	$uploaded_file_path = "";
	$new_file_name = bin2hex(openssl_random_pseudo_bytes(10));
	if(move_uploaded_file($_FILES['video']['tmp_name'], $upload_dir . $new_file_name . "." . pathinfo($_FILES['video']['name'],PATHINFO_EXTENSION) )) {
		$uploaded_file_path = $new_file_name;
	}

	die(json_encode($uploaded_file_path));

	
 ?>
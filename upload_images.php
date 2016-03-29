<?php 


	// RETURN paths of images in the format specified in create_post.php
	// 
	// POST: 
	// 		images (array)
	// 
	
	$upload_dir = "images/";

	if(!isset($_FILES['images'])) {
		$return['error'] = true;
		$return['desc'] = "Missing Required Param: images";
		die(json_encode($return));
	}

	$length_of_images = sizeof($_FILES['images']['name']); 

	$uploaded_files_path = [];

	for ($i=0; $i < $length_of_images; $i++) { 
		$new_file_name = bin2hex(openssl_random_pseudo_bytes(10));
		if(move_uploaded_file($_FILES['images']['tmp_name'][$i], $upload_dir . $new_file_name . "." . pathinfo($_FILES['images']['name'][$i],PATHINFO_EXTENSION) )) {
			$uploaded_files_path[] = $new_file_name;
		}
	}

	die(json_encode($uploaded_files_path));
 ?>

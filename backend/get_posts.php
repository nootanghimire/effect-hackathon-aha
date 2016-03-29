<?php 


	// The real deal. Querying 
	// 
	// Lots of optional filtering params
	// 
	// 		Params::
	// 					category
	// 					lat_long
	// 					keywords (comma seperated)
	// 					search_term
	// 					specific_project
	// 					specific_user


	require_once 'database.php';
	
	$base_query = "SELECT * FROM posts WHERE";

	#$where_clause = [];
	if(isset($_POST['category'])) {
		$where_clause[] = "category = '" . $_POST['category'] . "'";
	}

	if(isset($_POST['lat_long'])) {
		//Find out code to calculate proximity 
	}

	
	if(isset($_POST['keywords'])) {
		//FULLTEXT keywords and search
		$where_clause[] = "MATCH(keywords) AGAINST ('" . $_POST['keywords'] . "')";
	}

	if(isset($_POST['search_term'])) {
		//FULLTEXT text_blob and search
		$where_clause[] = "MATCH(text_blob) AGAINST (' " . $_POST['search_term'] . "')";
	}	

	if(isset($_POST['specific_project'])) {
		$where_clause[] = "project_id = " . $_POST['specific_project'];
	}

	if(isset($_POST['specific_user'])) {
		$where_clause[] = "user_id = " . $_POST['specific_user'];
	}

	if(isset($where_clause)) {
		$expanded_where_clause = implode(' AND ', $where_clause);
	} else {
		$expanded_where_clause = " 1" ;
	}

	$final_query = $base_query . $expanded_where_clause . " ;";

	$result = mysqli_query($db_link, $final_query);

	if(!$result){
		$error_string = "Something Weird Happened:: ". mysqli_error($db_link);
		die(json_encode(array('error' => true , 'desc' =>  $error_string)));
	}


	while ($row = mysqli_fetch_assoc($result)) {
		$all[] = $row;	
	}
echo json_encode($all);
	if(isset($all)){
		//echo "a";	
		//echo json_encode($all);
		die(var_dump($all));
	} else {
		die(json_encode(array('error' => true, 'desc'=>'No results found')));
	}

 ?>
<?php
	
	//Display error message
	ini_set('display_errors','0');

	//Database connection
	$conn = new mysqli('localhost','root','','vuecrud');

	if($conn->connect_errno){
		die("Database connection not establish");
	}

	$action = 'read';
	$response = [
		'error' => false
	];

	if(isset($_GET['action'])){
		$action = $_GET['action'];
	}

	switch ($action) {
		case 'read':
			$users = array();
			$result = $conn->query("SELECT * FROM `user`");
	
			if($result){
				while ( $row = $result->fetch_assoc()) {
					array_push($users, $row);
				}
				$response['users'] = $users;
			}else{
				$response['error'] = true;
				$response['message'] = "Data not load. Something Wrong!!";
			}

			break;

		case 'create':
			$name     = $_POST['name'];
			$username = $_POST['username'];
			$email    = $_POST['email'];

			$result = $conn->query("INSERT INTO `user`(`name`, `username`, `email`) VALUES ('$name','$username','$email')");

			if($result){
				$response['message'] = "Data save successfully";
			}else{
				$response['error'] = true;
				$response['message'] = "Data not save";
			}
			
			break;

		case 'update':
			$id       = $_POST['id'];
			$name     = $_POST['name'];
			$username = $_POST['username'];
			$email    = $_POST['email'];

			$result = $conn->query("
				UPDATE `user` SET 
				`name`     = '$name', 
				`username` = '$username',
				`email`    = '$email' 
				WHERE `id` = '$id' 
				");
			
			if($result){
				$response['message'] = "Data update successfully";
			}else{
				$response['error'] = true;
				$response['message'] = "Data not update";
			}
			break;

		case 'delete':
			$id       = $_POST['id'];

			$result = $conn->query("DELETE FROM `user` WHERE `id` = '$id'");
			
			if($result){
				$response['message'] = "Data deleted successfully";
			}else{
				$response['error'] = true;
				$response['message'] = "Data not deleted";
			}
			break;
		
		default:
			die("Invalid action!");
			break;
	}


	header("content-type: application/json");
	echo json_encode($response);
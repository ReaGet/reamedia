<?php

	require_once('config.php');

	$response = array();

	$roomNumber = filter_var($_POST['roomNumber'], 
				FILTER_SANITIZE_STRING);

	$conn = new mysqli($host, $username, $password, $db);

	if ($conn->connect_error) {
		die("Ошибка подключения: " . $conn->connect_error);
	}

	$sql = "SELECT * FROM rooms WHERE number=$roomNumber";

	if($result = $conn->query($sql))
	{
		$rows = $result->num_rows;

		if ($rows > 0) {

			$row = $result->fetch_assoc();

			$response['status'] = 'ok';
			$response['id'] = $row['id'];
			$response['number'] = $row['number'];
			$response['status'] = $row['status'];
			$response['name'] = $row['name'];
			$response['password'] = $row['password'];
		}
		else
			$response['error'] = 'Нет такой комнаты';
	} 

	echo json_encode($response);
?>
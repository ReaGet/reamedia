<?php

	if ($_POST['roomname'] && $_POST['userId']) {
		require_once('config.php');

		$response = array();

		$roomname = filter_var($_POST['roomname'], FILTER_SANITIZE_STRING);
		$userId = filter_var($_POST['userId'], FILTER_SANITIZE_STRING);

		$conn = new mysqli($host, $username, $password, $db);

		if ($conn->connect_error) {
			die("Ошибка подключения: " . $conn->connect_error);
		}

		$number = getNumber();

		$sql = "INSERT INTO rooms (number, status, name, admin) VALUES ('$number', '1', '$roomname', $userId)";

		

		if ($conn->query($sql) === TRUE) {
			$response['number'] = $number;
			$response['roomname'] = $roomname;
		   	echo json_encode($response);
		} else {
		   	echo "Ошибка: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();

	}

	exit();

	function getNumber() {
		return rand(10000, 99999);
	}
?>
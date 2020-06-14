<?php
	session_start();

	if ($_POST['roomNumber'] && $_POST['username']) {
		require_once('config.php');

		$response = array();

		$roomNumber = filter_var($_POST['roomNumber'], FILTER_SANITIZE_STRING);
		$user = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
		$pass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

		$conn = new mysqli($host, $username, $password, $db);

		if ($conn->connect_error) {
			die("Ошибка подключения: " . $conn->connect_error);
		}

		$sql = "SELECT * FROM rooms WHERE number=$roomNumber";

		if($result = $conn->query($sql))
		{
			$rows = $result->num_rows;

			if ($rows > 0) {
				$response['status'] = 'ok';
				$_SESSION['room_number'] = $roomNumber;
				$_SESSION['user_name'] = $user;

				$row = $result->fetch_assoc();

				if ($pass == $row['password']) {
					$_SESSION['user_upload_access'] = "accepted";
				}
			}
			else
				$response['error'] = 'Нет такой комнаты';
		} 

		echo json_encode($response);

		$conn->close();

	}

?>
<?php
	

	if (isset($_POST['roomNumber']) && isset($_POST['roomName']) && isset($_POST['roomStatus']) && isset($_POST['roomPassword'])) {
		require_once('config.php');

		$response = array();

		$roomNumber = $_POST['roomNumber'];
		$roomName = mysql_real_escape_string($_POST['roomName']);
		$roomStatus = $_POST['roomStatus'];
		$roomPassword = $_POST['roomPassword'];

		$conn = new mysqli($host, $username, $password, $db);

		if ($conn->connect_error) {
			die("Ошибка подключения: " . $conn->connect_error);
		}

		$sql = "UPDATE rooms SET status='$roomStatus', name='$roomName', password='$roomPassword' WHERE number=$roomNumber";

		if ($conn->query($sql) === TRUE) {
			$response['status'] = 'ok';
		} else {
			$response['status'] = 'error';
		}

		echo json_encode($response);
	}
	
?>
<?php
	
	require_once('config.php');
	session_start();

	$message = array();

	if (isset($_POST['username']) && isset($_POST['password'])) {

		$user = $_POST['username'];
		$pass = $_POST['password'];

		$conn = new mysqli($host, $username, $password, $db);

		if ($conn->connect_error) {
			die("Ошибка подключения: " . $conn->connect_error);
		}

		$sql = "SELECT * FROM users WHERE username = '$user'";

		if($result = $conn->query($sql))
		{
			$row = $result->fetch_assoc();

			if (md5($pass) == $row['password']) {
				$message[] = "loggedin";
				$_SESSION['user_id'] = $row['id'];
			} else {
				$message[] = "Пароль неверный";
			}

		}

		echo json_encode($message);
		
		$conn->close();
	
	}
?>
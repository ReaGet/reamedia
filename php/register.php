<?php
	require_once('config.php');
	session_start();

	$message = array();

	if (isset($_POST['username']) && isset($_POST['password1']) && isset($_POST['password2'])) {

		$user = $_POST['username'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];

		$conn = new mysqli($host, $username, $password, $db);

		if ($conn->connect_error) {
			die("Ошибка подключения: " . $conn->connect_error);
		}

		$sql = "SELECT * FROM users WHERE username='" . $_POST['username'] . "'";

		if($result = $conn->query($sql))
		{
			$rows = $result->num_rows;

			if ($rows > 0) {
				// $row = $result->fetch_assoc();
				$message[] = "Имя пользователя занято";
			} else {

				if ($password1 != $password2)
					$message[] = "Пароли не совпадают";
				else {

					$sql = "INSERT INTO users (username, password) VALUES ('$user', '" . md5($password1) . "')";

					if ($conn->query($sql) === TRUE) {

						$sql = "SELECT * FROM users WHERE username='" . $user . "'";
						$result = $conn->query($sql);
						$row = $result->fetch_assoc();

						$_SESSION['user_id'] = $row['id'];
						$message[] = "Пользователь создан";
					}

				}

			}

		}

		var_dump($message);

		$conn->close();

	}
	
?>
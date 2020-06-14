<?php

	if (isset($_POST['roomNumber'])) {
		require_once('config.php');

		$roomNumber = filter_var($_POST['roomNumber'], FILTER_SANITIZE_STRING);
		$count = filter_var($_POST['count'], FILTER_SANITIZE_STRING);

		$conn = new mysqli($host, $username, $password, $db);

		$sql = "SELECT * FROM images WHERE room=$roomNumber LIMIT 15 OFFSET $count";
		$result = $conn->query($sql);

		$images = array();

		if($result->num_rows > 0)
		{

			while($row = $result->fetch_assoc()) {
				$images[] = $row['name'];
			}

			echo json_encode($images);
		} else {
			echo json_encode('zero');
		}

		$conn->close();

	}

?>
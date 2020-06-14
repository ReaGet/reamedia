<?php

	if (isset($_POST['roomNumber'])) {
		require_once('config.php');

		$path = "../uploads/";

		$response = array();

		$roomNumber = $_POST['roomNumber'];

		$conn = new mysqli($host, $username, $password, $db);

		if ($conn->connect_error) {
			die("Ошибка подключения: " . $conn->connect_error);
		}

		$sql = "DELETE FROM rooms WHERE number=$roomNumber";

		if ($conn->query($sql) === TRUE) {

			$sql = "DELETE FROM images WHERE room=$roomNumber";
			if ($conn->query($sql) === TRUE) {

				delete_directory($path . $roomNumber);
				$response['status'] = 'ok';
			} else {
				$response['status'] = 'error';
				$response['message'] = 'Фотографии не смогли удалиться из базы';
			}
		} else {
			$response['status'] = 'error';
			$response['message'] = 'Комната не удалена';
		}

		echo json_encode($response);
	}


	function delete_directory($dirname) {
         if (is_dir($dirname))
           $dir_handle = opendir($dirname);
     if (!$dir_handle)
          return false;
     while($file = readdir($dir_handle)) {
           if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                     unlink($dirname."/".$file);
                else
                     delete_directory($dirname.'/'.$file);
           }
     }
     closedir($dir_handle);
     rmdir($dirname);
     return true;
}
?>
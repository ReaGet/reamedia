<?php
	
	$input_name = 'file';
	$path = "../uploads/";

	$deny = array(
		'phtml', 'php', 'php3', 'php4', 'php5', 'php6', 'php7', 'phps', 'cgi', 'pl', 'asp', 
		'aspx', 'shtml', 'shtm', 'htaccess', 'htpasswd', 'ini', 'log', 'sh', 'js', 'html', 
		'htm', 'css', 'sql', 'spl', 'scgi', 'fcgi'
	);


	if (isset($_POST['number'])) {
		require_once('config.php');

		$roomNumber = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
		$user = filter_var($_POST['user'], FILTER_SANITIZE_STRING);

		$conn = new mysqli($host, $username, $password, $db);

		if ($conn->connect_error) {
			die("Ошибка подключения: " . $conn->connect_error);
		}

		$sql = "SELECT id FROM rooms WHERE number=$roomNumber";

		if($result = $conn->query($sql))
		{
			$rows = $result->num_rows;

			if ($rows > 0) {

				$path .= $roomNumber . "/";

				if (!file_exists($path))
					mkdir($path, 0700);

				$files = array();
				$diff = count($_FILES[$input_name]) - count($_FILES[$input_name], COUNT_RECURSIVE);
				if ($diff == 0) {
					$files = array($_FILES[$input_name]);
				} else {
					foreach($_FILES[$input_name] as $k => $l) {
						foreach($l as $i => $v) {
							$files[$i][$k] = $v;
						}
					}		
				}

				foreach ($files as $file) {
					$pattern = "[^a-zа-яё0-9,~!@#%^-_\$\?\(\)\{\}\[\]\.]";
					$name = mb_eregi_replace($pattern, '-', $file['name']);
					$name = mb_ereg_replace('[-]+', '-', $name);

					$converter = array(
						'а' => 'a',   'б' => 'b',   'в' => 'v',    'г' => 'g',   'д' => 'd',   'е' => 'e',
						'ё' => 'e',   'ж' => 'zh',  'з' => 'z',    'и' => 'i',   'й' => 'y',   'к' => 'k',
						'л' => 'l',   'м' => 'm',   'н' => 'n',    'о' => 'o',   'п' => 'p',   'р' => 'r',
						'с' => 's',   'т' => 't',   'у' => 'u',    'ф' => 'f',   'х' => 'h',   'ц' => 'c',
						'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',  'ь' => '',    'ы' => 'y',   'ъ' => '',
						'э' => 'e',   'ю' => 'yu',  'я' => 'ya', 
					
						'А' => 'A',   'Б' => 'B',   'В' => 'V',    'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
						'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',    'И' => 'I',   'Й' => 'Y',   'К' => 'K',
						'Л' => 'L',   'М' => 'M',   'Н' => 'N',    'О' => 'O',   'П' => 'P',   'Р' => 'R',
						'С' => 'S',   'Т' => 'T',   'У' => 'U',    'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
						'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',  'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
						'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
					);

					$name = strtr($name, $converter);
					$parts = pathinfo($name);
					$success = array();

					$i = 0;
					$prefix = '';
					while (is_file($path . $parts['filename'] . $prefix . '.' . $parts['extension'])) {
			  			$prefix = '(' . ++$i . ')';
					}
					$name = $parts['filename'] . $prefix . '.' . $parts['extension'];

					if (move_uploaded_file($file['tmp_name'], $path . $name)) {

						$sql = "INSERT INTO images (room, name, user) VALUES ($roomNumber, '" . $path . $name . "', '$user')";
						if ($conn->query($sql) === TRUE) {
							$success = 'Файл «' . $name . '» успешно загружен.';
							// array_push($success, $path . $name);
							// $success[] = $path . $name;
						}
					}

					if (!empty($success)) {
						// echo '<p>' . $success . '</p>';
						echo json_encode($success);
					}
				}
				
				// if (move_uploaded_file($tmp_file, $path . $filename)) {

				// }
			}
			else {

			}
		}

		$conn->close();
	}

	// $tmp_file = $_FILES['image']['tmp_name'];
	// $filename = $_FILES['image']['name'];

	// move_uploaded_file($tmp_file, '../uploads/' . $filename);

?>
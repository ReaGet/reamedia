<?php
	
	session_start();
	unset($_SESSION['user_id']);
	unset($_SESSION['user_name']);
	unset($_SESSION['room_number']);
	unset($_SESSION['user_upload_access']);
	session_destroy();

?>
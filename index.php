<?php
	session_start();
	if (isset($_GET['room'])) {
		require_once('php/config.php');

		$conn = new mysqli($host, $username, $password, $db);
				
		$roomNumber = $_GET['room'];

		$sql = "SELECT * FROM rooms WHERE number=$roomNumber";
		$result = $conn->query($sql);

		$row = $result->fetch_assoc();

		if ($_SESSION['user_id'] != $row['admin'])
			header("Location: /");
	}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Reamedia</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/lsb.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<div class="container">
		<nav class="navbar navbar-light bg-light mb-5">
		  	<a class="navbar-brand" href="/">Reamedia</a>

		  	<?php if (empty($_SESSION['user_id']) && empty($_SESSION['room_number']) && empty($_SESSION['user_name'])): ?>
		    <div class="nav-item ml-auto">
		        <button onclick="$('#loginForm').modal('toggle');" class="btn btn-outline-warning my-2 my-sm-0" type="submit">Войти</button>
		    </div>
			<?php 

			endif;

			if (!empty($_SESSION['user_id']) || !empty($_SESSION['room_number']) || !empty($_SESSION['user_name'])): 
			?>
			<div class="nav-item ml-auto">
		        <button v-on:click="logout" class="btn btn-outline-warning my-2 my-sm-0" type="submit">Выйти</button>
		    </div>
			<?php 
			endif;
			?>
		</nav>
		
		<?php if (empty($_SESSION['user_id']) && empty($_SESSION['room_number']) && empty($_SESSION['user_name'])): ?>

		<div id="loginForm" class="modal" tabindex="-1" role="dialog">
		  	<div class="modal-dialog" role="document">
		    	<div class="modal-content">
		    		<ul class="nav nav-tabs" id="myTab" role="tablist">
					  	<li class="nav-item">
					    	<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Вход</a>
					  	</li>
					  	<li class="nav-item">
					    	<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Регистрация</a>
					  	</li>
					  	<button type="button" class="close ml-auto mr-3" data-dismiss="modal" aria-label="Close">
					        <span aria-hidden="true">&times;</span>
					    </button>
					</ul>
					<div class="tab-content" id="myTabContent">
					  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
				      	<div class="modal-body">
				        	<div class="form-group">
					            <label for="signInName" class="col-form-label">Ваше имя:</label>
					            <input v-model="signin.username" type="text" class="form-control" id="signInName">
				        	</div>
				        	<div class="form-group">
					            <label for="signInPassword" class="col-form-label">Пароль:</label>
					            <input v-model="signin.password" type="password" class="form-control" id="signInPassword">
				        	</div>
				        	<div class="form-group">
					            <input v-on:click="login" class="btn btn-warning" type="submit" name="" value="Войти">
				        	</div>
			      		</div>
					  </div>
					  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
				      	<div class="modal-body">
				        	<div class="form-group">
					            <label for="signUpName" class="col-form-label">Ваше имя:</label>
					            <input v-model="signup.username" type="text" class="form-control" id="signUpName">
				        	</div>
				        	<div class="form-group">
					            <label for="signUpPassword" class="col-form-label">Пароль:</label>
					            <input v-model="signup.password1" type="password" class="form-control" id="signUpPassword">
				        	</div>
				        	<div class="form-group">
					            <label for="signUpPassword2" class="col-form-label">Повторите Пароль:</label>
					            <input v-model="signup.password2" type="password" class="form-control" id="signUpPassword2">
				        	</div>
				        	<div class="form-group">
					            <input v-on:click="register" class="btn btn-warning" type="submit" name="" value="Регистрация">
				        	</div>
			      		</div>
					  </div>
					</div>
		    	</div>
		  	</div>
		</div>


		<div class="enter-form">
			<form>
			  	<div class="form-group">
				    <!-- <label for="room-number">Номер комнаты</label> -->
				    <input v-model="enter.roomNumber" type="text" class="form-control" id="room-number" placeholder="Номер комнаты">
				</div>
			  	<div class="form-group">
			    	<!-- <label for="username">Ваше имя</label> -->
			    	<input v-model="enter.username" type="text" class="form-control" id="username" placeholder="Ваше имя">
			  	</div>
			  	<div class="form-group">
			    	<!-- <label for="username">Ваше имя</label> -->
			    	<input v-model="enter.password" type="password" class="form-control" id="password" placeholder="Пароль (необязательно)">
			  	</div>
			  	<div class="form-group">
			  	<input v-on:click="enterRoom" class="btn btn-outline-warning" type="submit" name="" value="Войти в комнату">
			  	</div>
			</form>
		</div>
		<?php 

		endif;

		if (!empty($_SESSION['user_id'])) {


			if (!isset($_GET['room'])) {

				require_once('templates/roomTable.php');

			} else {

				require_once('templates/roomGallery.php');

			}

		}
		
		if (!empty($_SESSION['room_number']) || !empty($_SESSION['user_name']))
			require_once('templates/tempGallery.php');
		
		?>

	</div>

	<script type="text/javascript" src="js/jquery-3.5.1.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<!-- <script type="text/javascript" src="js/lightbox.js"></script> -->
	<script type="text/javascript" src="js/lsb.min.js"></script>
	<script type="text/javascript" src="js/vue.js"></script>
	<script type="text/javascript" src="js/app.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
		  	$.fn.lightspeedBox();
		});
	</script>


</body>
</html>
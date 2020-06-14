
	<!-- <div class="row">
		<div class="col">
			<input v-model="roomName" type="" name="" placeholder="Room name">
			<div v-on:click="createRoom(<?php echo $_SESSION['user_id']; ?>)" href="/" class="btn btn-outline-info">Создать комнату</div>
		</div>
	</div> -->

	<div class="card-columns">

<?php
	require_once('/php/config.php');

	$conn = new mysqli($host, $username, $password, $db);
	$sql = "SELECT * FROM rooms WHERE admin='" . $_SESSION['user_id'] . "'";
	$result = $conn->query($sql);
	
	if($result->num_rows > 0)
	{
	?>

		<?php
		while($row = $result->fetch_assoc()) {

			$sql = "SELECT * FROM images WHERE room='" . $row['number'] . "'";
			if($imagesResult = $conn->query($sql))
				$imagesCount = $imagesResult->num_rows;
		?>

		<div class="card" id="room-<?php echo $row['number']; ?>">
		    <div class="card-body">
		      	<h5 class="card-title"><span class="room-name"><?php echo $row['name'];?></span> - <small><?php echo $row['number']; ?></small></h5>
		      	<!-- <h6 class="card-subtitle mb-2 text-muted"><?php echo $row['number']; ?></h6> -->
		      	<p class="card-text"><small class="text-muted">Фотографий: <?php echo $imagesCount; ?></small></p>
		      	<p class="card-text">
		      		<small class="text-muted room-status">Статус: <?php echo $row['status'] == 1 ?  "активен" :  "неактивен"; ?></small>
		      	</p>
		    </div>
		    <div class="card-footer">
				<a href="?room=<?php echo $row['number']; ?>" class="card-link"><small class="text-muted">Открыть</small></a>
				<small v-on:click="editRoom(<?php echo $row['number']; ?>)" class="card-link btn-edit">Редактировать</small>
			</div>
		 </div>
		    <?php
			}
	}

?>
	
	</div>

	<div id="createRoomBtn" onclick="$('#createModal').modal('toggle');">+</div>

	<div id="editModal" class="modal" tabindex="-1" role="dialog">
	  	<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h5 class="modal-title">Modal title</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          	<span aria-hidden="true">&times;</span>
			        </button>
	      		</div>
		      	<div class="modal-body">
		        	<div class="form-group">
			            <label for="room-name" class="col-form-label">Имя комнаты:</label>
			            <input type="text" class="form-control" id="room-name">
		        	</div>
		        	<div class="form-group">
			            <label for="room-password" class="col-form-label">Пароль:</label>
			            <input type="password" class="form-control" id="room-password">
		        	</div>
		        	<div class="form-group">
			            <div class="col-form-label">Статус комнаты:</div>
			            <div class="form-check form-check-inline">
						  	<input class="form-check-input" type="checkbox" id="room-status">
						  	<label class="form-check-label" for="room-status">Активировать</label>
						</div>
		        	</div>
	      		</div>
	      		<div class="modal-footer">
	        		<button v-on:click="deleteRoom" type="button" class="btn btn-danger" data-dismiss="modal">Удалить</button>
	        		<button v-on:click="saveRoom" type="button" class="btn btn-primary">Сохранить</button>
	      		</div>
	    	</div>
	  	</div>
	</div>

	<div id="createModal" class="modal" tabindex="-1" role="dialog">
	  	<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h5 class="modal-title">Создать комнату</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          	<span aria-hidden="true">&times;</span>
			        </button>
	      		</div>
		      	<div class="modal-body">
		        	<div class="form-group">
			            <label for="roomName" class="col-form-label">Имя комнаты:</label>
			            <input type="text" v-model="roomName" class="form-control" id="roomName">
		        	</div>
	      		</div>
	      		<div class="modal-footer">
	        		<button v-on:click="createRoom(<?php echo $_SESSION['user_id']; ?>)" type="button" class="btn btn-primary">Создать</button>
	      		</div>
	    	</div>
	  	</div>
	</div>

		<!-- </tbody>
	</table> -->
<?php
	require_once('/php/config.php');

	$conn = new mysqli($host, $username, $password, $db);
			
	$roomNumber = $_SESSION['room_number'];

	$sql = "SELECT * FROM rooms WHERE number=$roomNumber";
	$result = $conn->query($sql);

	$room = $result->fetch_assoc();
	
	if ($room['status'] == 1):
		if ($_SESSION['user_upload_access'] == 'accepted'):
?>
	<div class="row" id="uploadImageWrapper">
		<form id="uploadImage" enctype="multipart/form-data">
			<input type="file" accept="image/*" @change="uploadData" multiple name="file[]">
			<!-- <button class="btn btn-outline-info" v-on:click="uploadData">Upload</button> -->
			<!-- <div class="progress progress-stripped active">
				<div class="progress-bar" style="width: 0%;"></div>
			</div> -->
		</form>
	</div>

	<?php
		endif;
	endif;

	?>

	<div class="gallery">
		
	<?php

	$sql = "SELECT * FROM images WHERE room=$roomNumber LIMIT 15";
	$result = $conn->query($sql);

	if($result->num_rows > 0)
	{

		while($row = $result->fetch_assoc()) {
?>

		<div class="gallery_product">
	    	<a data-lsb-group="group1" class="lsb-preview" href="<?php echo $row['name']; ?>">
	    		<img src="<?php echo $row['name']; ?>" class="img-responsive">
	    	</a>
	    </div>

<?php					
		}
	}

?>
	</div>

	<div class="row mt-5 mb-5">
		<div class="btn btn-warning m-auto <?php if($result->num_rows == 0) echo 'btn-hide' ?>" v-on:click="loadImages(<?php echo $_SESSION['room_number']; ?>)">Загрузить еще</div>
	</div>
	<div id="uploadProgress"></div>
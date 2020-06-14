	<!-- <div class="row">
		<a href="/" class="btn btn-outline-warning">Назад</a>
	</div> -->
	<div class="row" id="uploadImageWrapper">
		<form id="uploadImage" enctype="multipart/form-data">
			<input type="file" accept="image/*" @change="uploadData" multiple name="file[]">
			<!-- <button class="btn btn-outline-info" v-on:click="uploadData">Upload</button> -->
			<!-- <div class="progress progress-stripped active">
				<div class="progress-bar" style="width: 0%;"></div>
			</div> -->
		</form>
	</div>

	<div class="gallery">
<?php
	require_once('/php/config.php');

	$conn = new mysqli($host, $username, $password, $db);
			
	$roomNumber = $_GET['room'];
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

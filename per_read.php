<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_per_read.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program displays one volunteer's details (table: fr_persons)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["persons_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}

require 'database.php';
require 'functions.php';

$id = $_GET['id'];

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT * FROM persons where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id));
$data = $q->fetch(PDO::FETCH_ASSOC);
Database::disconnect();

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link   href="css/bootstrap.min.css" rel="stylesheet">
		<script src="js/bootstrap.min.js"></script>
		<link rel="icon" href="cardinal_logo.png" type="image/png" />
	</head>

	<body>
		<div class="container">
			<?php
				Functions::logoDisplay2();
			?>
			<div class="row">
				<h3>View Details</h3>
			</div>
			 
			<div class="form-horizontal" >
				
				<div class="control-group col-md-6">
				
					<label class="control-label">First Name</label>
					<div class="controls ">
						<label class="checkbox">
							<?php echo $data['fname'];?> 
						</label>
					</div>
					
					<label class="control-label">Last Name</label>
					<div class="controls ">
						<label class="checkbox">
							<?php echo $data['lname'];?> 
						</label>
					</div>
					
					<label class="control-label">Email</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['email'];?>
						</label>
					</div>
					
					<label class="control-label">Phone</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['phone'];?>
						</label>
					</div>     
					
					<label class="control-label">Title</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['title'];?>
						</label>
					</div>   
					
					<!-- password omitted on Read/View -->
					
					<div class="form-actions">
						<a class="btn" href="persons.php">Back</a>
					</div>
					
				</div>
				
				<!-- Display photo, if any --> 

				<div class='control-group col-md-6'>
					<div class="controls ">
					<?php 
					if ($data['filesize'] > 0) 
						echo '<img  height=5%; width=15%; src="data:image/jpeg;base64,' . 
							base64_encode( $data['filecontent'] ) . '" />'; 
					else 
						echo 'No photo on file.';
					?><!-- converts to base 64 due to the need to read the binary files code and display img -->
					</div>
				</div>
				
				
				
			</div>  <!-- end div: class="form-horizontal" -->

		</div> <!-- end div: class="container" -->
		
	</body> 
	
</html>
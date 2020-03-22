<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_per_delete.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program deletes one volunteer's details (table: fr_persons)
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

if ( !empty($_POST)) { // if user clicks "yes" (sure to delete), delete record

	$id = $_POST['id'];
	
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE FROM persons  WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
	header("Location: persons.php");
	
} 
else { // otherwise, pre-populate fields to show data to be deleted
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM persons where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	Database::disconnect();
}
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

			Functions::logoDisplay();
		?>
		<div class="row">
			<h3>Delete Volunteer</h3>
		</div>
		
		<form class="form-horizontal" action="per_delete.php" method="post">
			<input type="hidden" name="id" value="<?php echo $id;?>"/>
			<p class="alert alert-error">Are you sure you want to delete ?</p>
			<div class="form-actions">
				<button type="submit" class="btn btn-danger">Yes</button>
				<a class="btn" href="persons.php">No</a>
			</div>
		</form>
		
		<!-- Display same information as in file: fr_per_read.php -->
		
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
			
				<div class="row">
					<h4>Jobs for which this Employee Applied</h4>
				</div>
				
				<?php
					$pdo = Database::connect();
					$sql = "SELECT * FROM applications, jobs WHERE appli_job_id = jobs.id AND appli_per_id = " . $id . " ORDER BY jobs_title ASC";
					foreach ($pdo->query($sql) as $row) {
						echo $row['jobs_title'] . ': ' .$row['jobs_requirement'] . ' - ' . $row['jobs_description'] . ' - ' . $row['jobs_payment'] . '<br />';
					}
				?>
				
		</div>  <!-- end div: class="form-horizontal" -->

    </div> <!-- end div: class="container" -->
	
</body>
</html>
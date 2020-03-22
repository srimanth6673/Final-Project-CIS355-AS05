<?php 

session_start();
if(!isset($_SESSION["persons_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
require 'database.php';
require 'functions.php';

$id = $_GET['id'];

if ( !empty($_POST)) { // if $_POST filled then process the form
	
	# same as create

	// initialize user input validation variables
	$personsError = null;
	$jobsError = null;
	
	// initialize $_POST variables
	$persons = $_POST['persons_id'];    // same as HTML name= attribute in put box
	$jobs = $_POST['jobs_id'];
	
	// validate user input
	$valid = true;
	if (empty($persons)) {
		$personsError = 'Please choose a volunteer';
		$valid = false;
	}
	if (empty($jobs)) {
		$jobsError = 'Please choose an event';
		$valid = false;
	} 
		
	if ($valid) { // if valid user input update the database
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE applications set appli_per_id = ?, appli_job_id = ? WHERE id = ?";
		$q = $pdo->prepare($sql);
		$q->execute(array($person,$jobs,$id));
		Database::disconnect();
		header("Location: applications.php");
	}
} else { // if $_POST NOT filled then pre-populate the form
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT * FROM applications where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	$person = $data['appli_per_id'];
	$jobs = $data['appli_job_id'];
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
			//gets logo
			functions::logoDisplay();
		?>
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Update Application</h3>
			</div>
	
			<form class="form-horizontal" action="appli_update.php?id=<?php echo $id?>" method="post">
		
				<div class="control-group">
					<label class="control-label">Employee</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM persons ORDER BY lname ASC, fname ASC';
							echo "<select class='form-control' name='persons_id' id='persons_id'>";
							foreach ($pdo->query($sql) as $row) {
								if($row['id']==$person)
									echo "<option selected value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
								else
									echo "<option value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
							}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->
			  
				<div class="control-group">
					<label class="control-label">jobs</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM jobs ORDER BY jobs_title';
							echo "<select class='form-control' name='jobs_id' id='jobs_id'>";
							foreach ($pdo->query($sql) as $row) {
								if($row['id']==$jobs) {
									echo "<option selected value='" . $row['id'] . " '> " . $row['jobs_title'] . " (" . $row['jobs_requirements'] . ") - " . trim($row['event_description']) . " (" . trim($row['jobs_payment']) . ") " . "</option>";
								}
								else {
									echo "<option value='" . $row['id'] . " '> " . $row['jobs_title'] . " (" . $row['jobs_requirements'] . ") - " . trim($row['event_description']) . " (" . trim($row['jobs_payment']) . ") " . "</option>";
								}
							}
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Update</button>
					<a class="btn" href="applications.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->

  </body>
</html>
<?php 

 
session_start();
if(!isset($_SESSION["persons_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
$personsid = $_SESSION["persons_id"];
$jobsid = $_GET['jobs_id'];

require 'database.php';
require 'functions.php';

if ( !empty($_POST)) {

	// initialize user input validation variables
	$personsError = null;
	$jobsError = null;
	
	// initialize $_POST variables
	$persons = $_POST['persons'];    // same as HTML name= attribute in put box
	$jobs = $_POST['jobs'];
	
	// validate user input
	$valid = true;
	if (empty($persons)) {
		$personsError = 'Please choose a Persons';
		$valid = false;
	}
	if (empty($jobs)) {
		$jobsError = 'Please choose an jobs';
		$valid = false;
	} 
		
	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO applications 
			(appli_per_id,appli_job_id) 
			values(?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($persons,$jobs));
		Database::disconnect();
		header("Location: applications.php");
	}
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
    
		<div class="span10 offset1">
			<div class="row">
				<h3>Hire Employee</h3>
			</div>
	
			<form class="form-horizontal" action="appli_create.php" method="post">
		
				<div class="control-group">
					<label class="control-label">Employee</label>
					<div class="controls">
						<?php
							$pdo = Database::connect();
							$sql = 'SELECT * FROM persons ORDER BY lname ASC, fname ASC';
							echo "<select class='form-control' name='persons' id='persons_id'>";
							if($jobsid) // if $_GET exists restrict person options to logged in user
								foreach ($pdo->query($sql) as $row) {
									if($personsid==$row['id'])
										echo "<option value='" . $row['id'] . " '> " . $row['lname'] . ', ' .$row['fname'] . "</option>";
								}
							else
								foreach ($pdo->query($sql) as $row) {
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
							$sql = 'SELECT * FROM jobs ORDER BY jobs_title ASC';
							echo "<select class='form-control' name='jobs' id='jobs_id'>";
							if($jobsid) // if $_GET exists restrict event options to selected event (from $_GET)
								foreach ($pdo->query($sql) as $row) {
									if($jobsid==$row['id'])
									echo "<option value='" . $row['id'] . " '> " . $row['jobs_title'] . " (" . $row['jobs_requirements'] . ") - " .
									trim($row['jobs_description']) . " (" . 
									trim($row['jobs_payment']) . ") " .
									"</option>";
								}
							else
								foreach ($pdo->query($sql) as $row) {
									echo "<option value='" . $row['id'] . " '> " . $row['jobs_title'] . " (" . $row['jobs_requirements'] . ") - " .
									trim($row['jobs_description']) . " (" . 
									trim($row['jobs_payment']) . ") " .
									"</option>";
								}
								
							echo "</select>";
							Database::disconnect();
						?>
					</div>	<!-- end div: class="controls" -->
				</div> <!-- end div class="control-group" -->

				<div class="form-actions">
					<button type="submit" class="btn btn-success">Confirm</button>
						<a class="btn" href="applications.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
		<?php 
			//gets logo
			functions::logoDisplay();
		?>	
    </div> <!-- end div: class="container" -->

  </body>
</html>
<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_event_read.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program displays one event's details (table: fr_events)
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
$sql = "SELECT * FROM jobs where id = ?";
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
    
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Job Details</h3>
			</div>
			
			<div class="form-horizontal" >
			
				<div class="control-group">
					<label class="control-label">Title</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['jobs_title'];?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Requirements</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['jobs_requirements'];?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Description</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['jobs_description'];?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Payment</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $data['jobs_payment'];?>
						</label>
					</div>
				</div>
				
				
				<div class="form-actions">
					<a class="btn btn-primary" href="appli_create.php?jobs_id=<?php echo $id; ?>">Apply for this Job</a>
					<a class="btn" href="jobs.php">Back</a>
				</div>
				
			<div class="row">
				<h4>Employees Who Applied For This Job For This Job</h4>
			</div>
			
			<?php
				$pdo = Database::connect();
				$sql = "SELECT * FROM applications, persons WHERE appli_per_id = persons.id AND appli_job_id = " . $data['id'] . ' ORDER BY lname ASC, fname ASC';
				$countrows = 0;
				if($_SESSION['persons_title']=='Employer') {
					foreach ($pdo->query($sql) as $row) {
						echo $row['lname'] . ', ' . $row['fname'] . ' - ' . $row['phone'] . '<br />';
					$countrows++;
					}
				}
				else {
					foreach ($pdo->query($sql) as $row) {
						echo $row['lname'] . ', ' . $row['fname'] . ' - ' . '<br />';
					$countrows++;
					}
				}
				if ($countrows == 0) echo 'none.';
			?>
			
			</div> <!-- end div: class="form-horizontal" -->
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
	
</body>
</html>
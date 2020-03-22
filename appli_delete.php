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

if ( !empty($_POST)) { // if user clicks "yes" (sure to delete), delete record

	$id = $_POST['id'];
	
	// delete data
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "DELETE FROM applications  WHERE id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	Database::disconnect();
	header("Location: applications.php");
} 
else { // otherwise, pre-populate fields to show data to be deleted

	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	# get applications details
	$sql = "SELECT * FROM applications where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($id));
	$data = $q->fetch(PDO::FETCH_ASSOC);
	
	# get person details
	$sql = "SELECT * FROM persons where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($data['appli_per_id']));
	$perdata = $q->fetch(PDO::FETCH_ASSOC);
	
	# get jobs details
	$sql = "SELECT * FROM jobs where id = ?";
	$q = $pdo->prepare($sql);
	$q->execute(array($data['appli_job_id']));
	$jobsdata = $q->fetch(PDO::FETCH_ASSOC);
	
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
				<h3>Delete Applications</h3>
			</div>
			
			<form class="form-horizontal" action="appli_delete.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id;?>"/>
				<p class="alert alert-error">Are you sure you want to delete ?</p>
				<div class="form-actions">
					<button type="submit" class="btn btn-danger">Yes</button>
					<a class="btn" href="applications.php">No</a>
				</div>
			</form>
			
			<!-- Display same information as in file: appli_read.php -->
			
			<div class="form-horizontal" >
			
				<div class="control-group">
					<label class="control-label">Employee :</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo $perdata['fname'] . ', ' . $perdata['lname'] ;?>
						</label>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Job Applied For :</label>
					<div class="controls">
						<label class="checkbox">
							<?php echo trim($jobsdata['jobs_title']);?>
						</label>
					</div>
				</div>
				
				
			
			</div> <!-- end div: class="form-horizontal" -->
			
		</div> <!-- end div: class="span10 offset1" -->
		
    </div> <!-- end div: class="container" -->
	
</body>
</html>
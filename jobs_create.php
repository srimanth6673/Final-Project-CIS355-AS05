<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_event_create.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program adds/inserts a new event (table: fr_events)
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

if ( !empty($_POST)) { // if not first time through

	// initialize user input validation variables
	$titleError = null;
	$requirementsError = null;
	$descriptionError = null;
	$paymentError = null;
	
	
	// initialize $_POST variables
	$title = $_POST['jobs_title'];
	$requirements = $_POST['jobs_requirements'];
	$description = $_POST['jobs_description'];
	$payment = $_POST['jobs_payment'];		
	
	// validate user input
	$valid = true;
	if (empty($title)) {
		$titleError = 'Please enter Title';
		$valid = false;
	}
	if (empty($requirements)) {
		$requirementsError = 'Please enter Requirements';
		$valid = false;
	} 		
	if (empty($description)) {
		$descriptionError = 'Please enter Description';
		$valid = false;
	}		
	if (empty($payment)) {
		$paymentError = 'Please enter Payment';
		$valid = false;
	}

	// insert data
	if ($valid) {
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO jobs (jobs_title, jobs_requirements, jobs_description, jobs_payment) values(?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($title,$requirements,$description,$payment));
		Database::disconnect();
		header("Location: jobs.php");
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
		<?php 
			//gets logo
			functions::logoDisplay();
		?>	
		<div class="span10 offset1">
		
			<div class="row">
				<h3>Add New Job</h3>
			</div>
	
			<form class="form-horizontal" action="jobs_create.php" method="post">
			
				<div class="control-group <?php echo !empty($titleError)?'error':'';?>">
					<label class="control-label">Title</label>
					<div class="controls">
						<input name="jobs_title" type="title"  placeholder="Title" value="<?php echo !empty($title)?$title:'';?>">
						<?php if (!empty($dateError)): ?>
							<span class="help-inline"><?php echo $dateError;?></span>
						<?php endif; ?>
					</div>
				</div>
			  
				<div class="control-group <?php echo !empty($requirementsError)?'error':'';?>">
					<label class="control-label">requirements</label>
					<div class="controls">
						<input name="jobs_requirements" type="requirements" placeholder="Requirements" value="<?php echo !empty($requirements)?$requirements:'';?>">
						<?php if (!empty($requirementsError)): ?>
							<span class="help-inline"><?php echo $requirementsError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
					<label class="control-label">Description</label>
					<div class="controls">
						<input name="jobs_description" type="description" placeholder="Description" value="<?php echo !empty($description)?$description:'';?>">
						<?php if (!empty($descriptionError)): ?>
							<span class="help-inline"><?php echo $descriptionError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($paymentError)?'error':'';?>">
					<label class="control-label">Payment</label>
					<div class="controls">
						<input name="jobs_payment" type="text" placeholder="Payment" value="<?php echo !empty($payment)?$payment:'';?>">
						<?php if (!empty($paymentError)): ?>
							<span class="help-inline"><?php echo $paymentError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Create</button>
					<a class="btn" href="jobs.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- div: class="container" -->
				
    </div> <!-- div: class="container" -->
	
</body>
</html>
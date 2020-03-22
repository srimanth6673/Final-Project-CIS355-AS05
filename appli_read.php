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

$pdo = Database::connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

# get applications details
$sql = "SELECT * FROM applications where id = ?";
$q = $pdo->prepare($sql);
$q->execute(array($id));
$data = $q->fetch(PDO::FETCH_ASSOC);

# get Employee details
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
				<h3>Applications Details</h3>
			</div>
			
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
				
				
				
				<div class="form-actions">
					<a class="btn" href="applications.php">Back</a>
				</div>
			
			</div> <!-- end div: class="form-horizontal" -->
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
	
</body>
</html>
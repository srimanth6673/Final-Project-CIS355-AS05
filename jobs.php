<?php
/* ---------------------------------------------------------------------------
 * filename    : fr_events.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program displays a list of events (table: fr_events)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["persons_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
$sessionid = $_SESSION['persons_id'];
include 'functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
	<link rel="icon" href="cardinal_logo.png" type="image/png" />
</head>

<body style="background-color: lightblue !important";>
    <div class="container">
		  <?php 
			//gets logo
			functions::logoDisplay2();
		?>
		<div class="row">
			<h3>Jobs</h3>
		</div>
		
		<div class="row">
			
			<p>
				<?php if($_SESSION['persons_title']=='Employer')
					echo '<a href="jobs_create.php" class="btn btn-primary">Add jobs</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<?php if($_SESSION['persons_title']=='Employer')
					echo '<a href="persons.php">Employee</a> &nbsp;';
				?>
				<a href="jobs.php">Jobs</a> &nbsp;
				<?php if($_SESSION['persons_title']=='Employer')
					echo '<a href="applications.php">Alljobs</a>&nbsp;';
				?>
				<a href="applications.php?id=<?php echo $sessionid; ?>">My Applications</a>&nbsp;
			</p>
			
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Title</th>
						<th>Requirements</th>
						<th>Description</th>
						<th>Payment</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'database.php';
						$pdo = Database::connect();
						$sql = 'SELECT * FROM jobs ORDER BY jobs_title ASC';
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							echo '<td>'. $row['jobs_title'] . '</td>';
							echo '<td>'. $row['jobs_requirements'] . '</td>';
							echo '<td>'. $row['jobs_description'] . '</td>';
						    echo '<td>'. '$' . $row['jobs_payment'] . ' / Hour' . '</td>';
							//echo '<td width=250>';
							echo '<td>';
							echo '<a class="btn" href="jobs_read.php?id='.$row['id'].'">Details</a> &nbsp;';
							if ($_SESSION['persons_title']=='Employee' )
								echo '<a class="btn btn-primary" href="jobs_read.php?id='.$row['id'].'">Apply</a> &nbsp;';
							if ($_SESSION['persons_title']=='Employer' )
								echo '<a class="btn btn-success" href="jobs_update.php?id='.$row['id'].'">Update</a>&nbsp;';
							if ($_SESSION['persons_title']=='Employer' 
								&& $row['countAssigns']==0)
								echo '<a class="btn btn-danger" href="jobs_delete.php?id='.$row['id'].'">Delete</a>';
							if($row['sumAssigns']==1) 
								echo " &nbsp;&nbsp;Me";
							echo '</td>';
							echo '</tr>';
						}
						Database::disconnect();
					?>
				</tbody>
			</table>
    	</div>
	
    </div> <!-- end div: class="container" -->
	
  </body>
  
</html>
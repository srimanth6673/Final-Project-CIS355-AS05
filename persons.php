<?php
/* ---------------------------------------------------------------------------
 * filename    : fr_persons.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program displays a list of volunteers (table: fr_persons)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["persons_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
$sessionid = $_SESSION['persons_id'];
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
			require 'functions.php';
			functions::logoDisplay2();
		?>
		<div class="row">
			<h3>People</h3>
		</div>
		<div class="row">
			
			<p>
				<?php if($_SESSION['person_title']=='Employer')
					echo '<a href="per_create.php" class="btn btn-primary">Add Employee</a>';
				?>
				<a href="logout.php" class="btn btn-warning">Logout</a> &nbsp;&nbsp;&nbsp;
				<a href="persons.php">Employee</a> &nbsp;
				<a href="jobs.php">Jobs</a> &nbsp;
				<a href="applications.php">AllApplications</a>&nbsp;
				<a href="applications.php?id=<?php echo $sessionid; ?>">MyApplications</a>&nbsp;
			</p>
				
			<table class="table table-striped table-bordered" style="background-color: lightgrey !important">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Phone</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						include 'database.php';
						$pdo = Database::connect();
						$sql = 'SELECT `persons`.*, COUNT(`applications`.appli_per_id) AS countAssigns FROM `persons` LEFT OUTER JOIN `applications` ON (`persons`.id=`applications`.appli_per_id) GROUP BY `persons`.id ORDER BY `persons`.lname ASC, `persons`.fname ASC';
						//$sql = 'SELECT * FROM fr_persons ORDER BY `fr_persons`.lname ASC, `fr_persons`.fname ASC';
						foreach ($pdo->query($sql) as $row) {
							echo '<tr>';
							if ($row['countAssigns'] == 0)
								echo '<td>'. trim($row['lname']) . ', ' . trim($row['fname']) . ' (' . substr($row['title'], 0, 1) . ') '.' - UNASSIGNED</td>';
							else
								echo '<td>'. trim($row['lname']) . ', ' . trim($row['fname']) . ' (' . substr($row['title'], 0, 1) . ') - '.$row['countAssigns']. ' jobs</td>';
							echo '<td>'. $row['email'] . '</td>';
							echo '<td>'. $row['phone'] . '</td>';
							echo '<td width=250>';
							# always allow read
							echo '<a class="btn" href="per_read.php?id='.$row['id'].'">Details</a>&nbsp;';
							# person can update own record
							if ($_SESSION['persons_title']=='Administrator'
								|| $_SESSION['persons_id']==$row['id'])
								echo '<a class="btn btn-success" href="per_update.php?id='.$row['id'].'">Update</a>&nbsp;';
							# only admins can delete
							if ($_SESSION['persons_title']=='Administrator' 
								&& $row['countAssigns']==0)
								echo '<a class="btn btn-danger" href="per_delete.php?id='.$row['id'].'">Delete</a>';
							if($_SESSION["persons_id"] == $row['id']) 
								echo " &nbsp;&nbsp;Me";
							echo '</td>';
							echo '</tr>';
						}
						Database::disconnect();
					?>
				</tbody>
			</table>
			
    	</div>
    </div> <!-- /container -->
  </body>
</html>
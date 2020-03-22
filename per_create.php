<?php 
/* ---------------------------------------------------------------------------
 * filename    : fr_per_create.php
 * author      : George Corser, gcorser@gmail.com
 * description : This program adds/inserts a new volunteer (table: fr_persons)
 * ---------------------------------------------------------------------------
 */
session_start();
if(!isset($_SESSION["persons_id"])){ // if "user" not set,
	session_destroy();
	header('Location: login.php');     // go to login page
	exit;
}
	
require 'database.php';

if ( !empty($_POST)) { // if not first time through

	// initialize user input validation variables
	$fnameError = null;
	$lnameError = null;
	$emailError = null;
	$phoneError = null;
	$passwordError = null;
	$titleError = null;
	$pictureError = null; // not used
	
	// initialize $_POST variables
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$password = $_POST['password'];
	$passwordhash = MD5($password);
	$title =  $_POST['title'];
	$picture = $_POST['picture']; // not used
	
	// initialize $_FILES variables
	
	$content = file_get_contents($tmpName);

	// validate user input
	$valid = true;
	if (empty($fname)) {
		$fnameError = 'Please enter First Name';
		$valid = false;
	}
	if (empty($lname)) {
		$lnameError = 'Please enter Last Name';
		$valid = false;
	}
	// do not allow 2 records with same email address!
	if (empty($email)) {
		$emailError = 'Please enter valid Email Address (REQUIRED)';
		$valid = false;
	} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
		$emailError = 'Please enter a valid Email Address';
		$valid = false;
	} 
	
	$pdo = Database::connect();
	$sql = "SELECT * FROM persons";
	foreach($pdo->query($sql) as $row) {

		if($email == $row['email']) {
			$emailError = 'Email has already been registered!';
			$valid = false;
		}
	}
	Database::disconnect();

	// email must contain only lower case letters
	if (strcmp(strtolower($email),$email)!=0) {
		$emailError = 'email address can contain only lower case letters';
		$valid = false;
	}

	if (empty($phone)) {
		$phoneError = 'Please enter Phone Number (or "none")';
		$valid = false;
	}
	if(!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $phone)) {
		$phoneError = 'Please write Phone Number in form 000-000-0000';
		$valid = false;
	}
	if (empty($password)) {
		$passwordError = 'Please enter valid Password';
		$valid = false;
	}
	if (empty($title)) {
		$titleError = 'Please enter valid Title';
		$valid = false;
	}
	// restrict file types for upload
	$types = array('image/jpeg','image/gif','image/png');
	if($filesize > 0) {
		if(in_array($_FILES['userfile']['type'], $types)) {
		}
		else {
			$filename = null;
			$filetype = null;
			$filesize = null;
			$filecontent = null;
			$pictureError = 'improper file type';
			$valid=false;
			
		}
	}
	// insert data
	if ($valid) 
	{
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO persons (fname,lname,email,phone,password,title
		) values(?, ?, ?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($fname,$lname,$email,$phone,$passwordhash,$title));
		Database::disconnect();
		header("Location: persons.php");
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
				<h3>Add New Volunteer</h3>
			</div>
	
			<form class="form-horizontal" action="per_create.php" method="post" enctype="multipart/form-data">

				<div class="control-group <?php echo !empty($fnameError)?'error':'';?>">
					<label class="control-label">First Name</label>
					<div class="controls">
						<input name="fname" type="text"  placeholder="First Name" value="<?php echo !empty($fname)?$fname:'';?>">
						<?php if (!empty($fnameError)): ?>
							<span class="help-inline"><?php echo $fnameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($lnameError)?'error':'';?>">
					<label class="control-label">Last Name</label>
					<div class="controls">
						<input name="lname" type="text"  placeholder="Last Name" value="<?php echo !empty($lname)?$lname:'';?>">
						<?php if (!empty($lnameError)): ?>
							<span class="help-inline"><?php echo $lnameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($emailError)?'error':'';?>">
					<label class="control-label">Email</label>
					<div class="controls">
						<input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
						<?php if (!empty($emailError)): ?>
							<span class="help-inline"><?php echo $emailError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($phoneError)?'error':'';?>">
					<label class="control-label">Phone Number</label>
					<div class="controls">
						<input name="phone" type="text"  placeholder="Phone Number" value="<?php echo !empty($phone)?$phone:'';?>">
						<?php if (!empty($phoneError)): ?>
							<span class="help-inline"><?php echo $phoneError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($passwordError)?'error':'';?>">
					<label class="control-label">Password</label>
					<div class="controls">
						<input id="password" name="password" type="text"  placeholder="Password" value="<?php echo !empty($password)?$password:'';?>">
						<?php if (!empty($passwordError)): ?>
							<span class="help-inline"><?php echo $passwordError;?></span>
						<?php endif;?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Title</label>
					<div class="controls">
						<select class="form-control" name="title">
							<option value="Employee" selected>Employee</option>
							<option value="Employer" >Employer</option>
						</select>
					</div>
				</div>
			  
				<div class="control-group <?php echo !empty($pictureError)?'error':'';?>">
					<label class="control-label">Picture</label>
					<div class="controls">
						<input type="hidden" name="MAX_FILE_SIZE" value="16000000">
						<input name="userfile" type="file" id="userfile">
					</div>
				</div>
			  
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Create</button>
					<a class="btn" href="persons.php">Back</a>
				</div>
				
			</form>
			
		</div> <!-- end div: class="span10 offset1" -->
				
    </div> <!-- end div: class="container" -->
  </body>
</html>
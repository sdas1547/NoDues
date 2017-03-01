<?php
	session_start();
	error_reporting(0);
?>
<html>
	<script>
      function preventBack(){window.history.forward();}
      setTimeout("preventBack()", 0);
      window.onunload=function(){null};
    </script>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>No Dues Portal</title>
		<link href="new_nodues/css/bootstrap.min.css" rel="stylesheet">
		<link href="new_nodues/mystyle.css" rel="stylesheet">		
	</head>
	<?php
		include ("new_nodues/header.php");
		include ("new_nodues/dbinfo.inc");
		if (isset($_SESSION["login_id"])){
	        header ("Location: ./new_nodues");
	        die;
	    }
	    else{
	        
	    }
	?>

	<body>
		<div class="container">
			<div class="row">	
				<div class="col-sm-6">			
					<h2>IIT Delhi No-Dues System</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-3">				
					<a href="./login.php"> 
						<button style="width:200px" class='btn btn-success'>Login with Kerebros</button>
					</a>
				</div>
			</div>
			<br>
			<div class="row">				
				<form method="POST" action="hod_login.php">
					<div class="col-sm-3">
						<input class="form-control" type="text" name="id" required>
					</div>
					<div>
						<button class="btn btn-success" style="width:200px" type="submit" name="submit">HoD Login</button>
					</div>
				</form>
			</div>

			<div class="row">				
				<form method="POST" action="lab_login.php">
					<div class="col-sm-3">
						<input class="form-control" type="text" name="lab_id" required>
					</div>
					<div>
						<button class="btn btn-success" style="width:200px" type="submit" name="submitlab">Lab Instructor Login</button>
					</div>
				</form>
			</div>	

			<div class="row">				
				<div class="col-sm-3">
					<a href="./new_nodues/pgs_index.php">
						<button class="btn btn-success" style="width:200px" type="submit" name="submit">UGS Login</button>
					</a>
				</div>
			</div>
			<br>
			<div class="row">				
				<div class="col-sm-3">
					<a href="./new_nodues/ugs_index.php">
						<button class="btn btn-success" style="width:200px" type="submit" name="submit">PGS Login</button>
					</a>
				</div>
			</div>			
		</div>	
			<div class="footer">
			</div>
		
		<hr>
		<script src="script/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
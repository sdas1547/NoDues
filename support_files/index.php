<?php
session_start();
include("dbinfo.inc");
//Home page
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Room Management System | IIT Delhi</title>
		<meta charset="utf-8"/>
		<base href="http://<?php echo $_SERVER['SERVER_NAME']?>/">
		<link rel="stylesheet" href="css/bootstrap.min.css" />
		<link rel="stylesheet" href="css/custom.css" />
		<link rel="icon" href="favicon.ico" type="image/x-icon"/>
		<script src="js/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
<?php
include("header.php");
?>
	<div class="container">
	<h2 class="panel-heading">Room Management System</h2>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-5" style="float:left">
			<?php
			if (!isset($_SESSION["login_id"])){
				//If a user is not logged in, include the login page
				echo "<h3>&nbsp;</h3>";
				//echo "<strong class='has-error'>System under maintenance</span>";
				include("login.php");
			}
			else{
				//If a user is logged in, include the menu
				include("menu.php");
			}
			?>
			</div>
			<div class="col-md-7" style="float:left">
				
			</div>
		</div>
	</div>
	</div>
<?php
include("footer.php");
$con->close();
?>
		<script src="js/function.js"></script>
		<script src="js/jquery.js"></script>
	</body>
</html>

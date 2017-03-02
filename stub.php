<html>
	<head>
		<meta charset="utf-8">
		<script src="script/jquery.js"></script>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Header</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="./mystyle.css" rel="stylesheet">
	</head>

<body>

<?php 
session_start();
		
		 include("./dbinfo.inc");
		 include("./header.php");
		if(!(isset($_SESSION["admin_id"]) && isset($_SESSION["department"]))){
			echo "You are not logged in.";
			die;
		}

$entries= $_POST['total'];
$pieces = explode(",", $entries);
foreach ($pieces as &$entry_n) {
   //echo $entry_n;

$query="UPDATE  likely_grad set hod_approval=1 where entry_num='$entry_n'";
	$response=$con->query($query);
	//$location="graduating_student.php";
	//header("Location:$location");


}


echo "Approved Students : ";
foreach ($pieces as &$entry_n) {
   echo $entry_n;
   echo "\n";
}





 ?>


</body>
</html>

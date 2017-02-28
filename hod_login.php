<?php

session_start();
include'new_nodues/dbinfo.inc';
if($_SERVER['REQUEST_METHOD']=='POST')
{
	$id=$_POST['id'];
	$query="Select * from headofdept where hod_id='".$id."'";
	$result=$con->query($query);
	$present = mysqli_num_rows($result);
	if($present>0)
	{
		while($hod_data = $result->fetch_assoc())
		{
			$dept=$hod_data["department"];
			$hod_id=$hod_data["hod_id"];
		}
	
	
	$_SESSION["admin_id"]=$hod_id;
	$_SESSION["department"]=$dept;

	header("Location: ./new_nodues/hod_index.php");


	}
	
	else
	{
		header('Location: ./index.php');
	}
	
}



?>
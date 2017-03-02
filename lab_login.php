<?php

session_start();
include'new_nodues/dbinfo.inc';
if($_SERVER['REQUEST_METHOD']=='POST')
{
	$id=$_POST['lab_id'];
	echo $id;
	$query="Select * from incharge where incharge_uID='".$id."'";
	$result=$con->query($query);
	$present = mysqli_num_rows($result);
	if($present>0)
	{
		while($lab_data = $result->fetch_assoc())
		{
			$lab=$lab_data["lab_code"];
			$ins_id=$lab_data["incharge_uID"];
		}
	
	
	$_SESSION["emp_no"]=$ins_id;
	
	echo $query;

	//header("Location: ./new_nodues/ins_index.php");


	}
	
	else
	{
		//header('Location: ./index.php');
	}
	
}



?>
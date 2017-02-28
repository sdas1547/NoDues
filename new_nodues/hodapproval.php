<?php

session_start();
		
		include("./dbinfo.inc");
		if(!(isset($_SESSION["admin_id"]) && isset($_SESSION["department"]))){
			echo "You are not logged in.";
			die;
		}
if($_SERVER['REQUEST_METHOD']=='POST')
{
	$prog=$_POST['programme'];
	$entry_n=$_POST['entry'];
	$query="UPDATE  likely_grad set hod_approval=1 where entry_num='$entry_n'";
	$response=$con->query($query);
	$location="graduating_student.php?programme=".$prog;
	header("Location:$location");

}










?>
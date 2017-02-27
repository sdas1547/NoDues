<?php
	$due_id = $_GET["due_id"];
	$request_sql = "UPDATE dues SET current_status = 'C' WHERE due_id =\'$due_id'";
	echo "hello";
	$request_result = $con->query($request_sql);
	header("Location: http://localhost/new_nodues/lab_index.php" );
	die;	
?>
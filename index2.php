<?php
	session_start();
	if (isset($_SESSION["login_id"])){
		header ("Location: ./new_nodues");
		die;

	}
	else{
		echo "<a href=\"./login.php\">Click here to login</a>";
	}
?>

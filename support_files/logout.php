<?php
session_start();
include("dbinfo.inc");
include("functions.php");
if (isset($_SESSION['login_id'])){
	//Take the login id, ip and session id to logout
	$login_id = $_SESSION['login_id'];
	$login_ip = $_SERVER['REMOTE_ADDR'];
	$session_id = session_id();
	// Drop the table which might have been created for csv upload by user
	// Delete user from sessions table
	$logout_query = "DELETE FROM sessions WHERE session_id = '$session_id' AND login_id = '$login_id' AND login_ip = '$login_ip'";
	put_log($logout_query);
	$con->query($logout_query);
	$temp_query = "DELETE FROM users_temp WHERE user_id = '$login_id'";
	$con->query($temp_query);
}
//If an error has been set, like on invalid session, save it
if (isset($_SESSION["login_status"])){
	$login_status = $_SESSION["login_status"];
}
//destroy session
session_destroy();
session_start();
//Restore the error message to show on login page
if (isset($login_status)){
	$_SESSION["login_status"] = $login_status;
}
//Redirect to home page
header("Location: ../index");
die;
?>
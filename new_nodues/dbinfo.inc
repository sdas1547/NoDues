<?php
$db_username="root";
$db_password="";
$db_database="n_nodues";
$db_server="localhost";

$con=new mysqli($db_server,$db_username,$db_password,$db_database);
//Check Connection
if ($con->connect_error){
die ("Failed to connect to MySQL: ".$con->connect_error);
}
?>
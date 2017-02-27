<?php
session_start();
//Included in index.php
include("./new_nodues/dbinfo.inc");
include("./new_nodues/functions.php");
session_destroy();
session_start();
header("Location: ./index");
die;
?>
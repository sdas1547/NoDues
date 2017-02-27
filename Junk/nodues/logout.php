<?php
session_start();
 //if(isset($_SESSION['id']) && $_SESSION['id'] != "")
  //   {

//$_SESSION['logged_in'] = "false";
unset($_SESSION['id']);
session_destroy();
session_unset();
//header('Location: /admn/after_logout.php'); //redirect URL
header('Location: /nodues/'); //redirect URL

exit();  
//} 
?>
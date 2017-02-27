<?php

require "dbconnection.php";



if($_SERVER['REQUEST_METHOD']=="POST")
{
    session_start();
    
    $req=$_POST['req'];
    
   //echo $req; 

  
    
    $sql_query="UPDATE requests SET status=1 where req_id='$req'";
    //echo $sql_query;
    $result=mysqli_query($connection,$sql_query);
    
        header('Location: approve.php');
    
    
    
    
    
    
    
}

else{
    header('Location: approve.php');
}




?>
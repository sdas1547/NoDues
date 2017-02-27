<?php

require "dbconnection.php";



if($_SERVER['REQUEST_METHOD']=="POST")
{
    session_start();
    $adder=$_SESSION['id'];
    $dep_code=$_POST['dept_code'];
    $place_code=$_POST['place_code'];
    $uid=$_POST['st_id'];
    $value=$_POST['value'];
    
    $description=$_POST['description'];
    
    $status="0";

  
    
    $sql_query="INSERT INTO dues(dept_id,place_id,userid,adder,value,description,status) 
    VALUES('$dep_code','$place_code','$uid','$adder','$value','$description','$status')";
    
    $result=mysqli_query($connection,$sql_query);
    if(!$result)
    {
        header('Location: add_new.php');    
    }
    else
    {
        header('Location: confirm.php');
    }
    
    
    
    
    
    
}

else{
    header('Location: add_new.php');
}




?>
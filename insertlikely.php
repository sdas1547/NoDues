<?php

session_start();
       

        include("./dbinfo.inc");


if (isset($_FILES["file_to_upload"]["name"])) {

    $name = $_FILES["file_to_upload"]["name"];
    $tmp_name = $_FILES['file_to_upload']['tmp_name'];
    $error = $_FILES['file_to_upload']['error'];

    if (!empty($name)) {
        $location = 'uploads/';

        if  (move_uploaded_file($tmp_name, $location.$name)){
           
        }

    } else {
        echo 'please choose a file';
    }
}
else
{
    echo "n";
}

$qi="DROP table newtable";
$res=$con->query($qi);

$q="CREATE TABLE newtable(entrynum text, grad_year int, hod_approval int,category text , dept text)";
$res=$con->query($q);

if($res)
{
    echo "create table success\n";
}




$quer="LOAD DATA INFILE ".$location.$name." INTO TABLE newtable FIELDS TERMINATED BY ','  LINES TERMINATED BY '\n'";



echo $quer;






$newres=$con->query($quer);


if($newres)
{
    echo "Insert success";
}

else 
{
    echo "bingo";
}



?>
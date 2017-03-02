<?php

     include("./dbinfo.inc");
     include("./header.php");
if(isset($_POST['submit'])){



$prg=$_POST['prg'];

$query = "SELECT entry_num FROM likely_grad";
$result = mysqli_query($con, $query);

if(empty($result)) {
                $query = "CREATE TABLE likely_grad (
                          entry_num text,
                          grad_year int,
                          hod_approval int,
                          category text,
                          department text
                          )";
                $result = mysqli_query($con, $query);
}


    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
    if(!empty($_FILES['file_to_upload']['name']) && in_array($_FILES['file_to_upload']['type'],$csvMimes)){
        if(is_uploaded_file($_FILES['file_to_upload']['tmp_name'])){
            
            //open uploaded csv file with read only mode
            $csvFile = fopen($_FILES['file_to_upload']['tmp_name'], 'r');
            
            //skip first line
            fgetcsv($csvFile);
            
            //parse data from csv file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                
               {

               		$query1="SELECT * from likely_grad where entry_num='".$line[0]."'";
               		$prevResult = $con->query($query1);
                if($prevResult->num_rows > 0){
                    
                    $con->query("UPDATE likely_grad SET  grad_year = ".$line[1].", hod_approval = ".$line[2].", category = '".$line[3]."', department = '".$line[4]."' WHERE entry_num = '".$line[0]."'");
                }else
                    {
                    $con->query("INSERT INTO likely_grad (entry_num, grad_year, hod_approval, category, department) VALUES ('".$line[0]."','".$line[1]."','".$line[2]."','".$line[3]."','".$line[4]."')");
                }
            }
            }
            
            //close opened csv file
            fclose($csvFile);

            $qstring = '?status=succ';

            ?>
            <html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Home HOD | No Dues IIT Delhi</title>
			<script src="script/jquery.js"></script>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="./mystyle.css" rel="stylesheet">
	</head>
            <body>
            	<div class="row">
            		<div class="col-sm-10 col-xs-12 col-sm-offset-4">
            <label>List of Graduating Student Uploaded Successfully</label>
        </div>




        <div class="row">
            		<div class="col-sm-10 col-xs-12 col-sm-offset-4">
           <button class="btn btn-primary" onclick="history.go(-1)">GO BACK</button>
        </div>







            </body>
           

<?php


        }else{
            $qstring = '?status=err';
        }
    }else{
        $qstring = '?status=invalid_file';
    }
}
?>



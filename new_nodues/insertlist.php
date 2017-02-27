<?php

include("./dbinfo.inc");


$deleterecords = "TRUNCATE TABLE graduating"; 
 mysql_query($deleterecords);

if (isset($_POST['submit'])) {

	
if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
    echo "<h1>" . "File ". $_FILES['filename']['name'] ." uploaded successfully." . "</h1>";
    echo "<h2>Displaying contents:</h2>";
    readfile($_FILES['filename']['tmp_name']);
}
//Import uploaded file to Database
$handle = fopen($_FILES['filename']['tmp_name'], "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
  $import="INSERT into contracts(uploaded) values('$data[0],$data[1]')";
    mysql_query($import) or die(mysql_error());
}
fclose($handle);
echo "Import done";
//view upload form
}



?>
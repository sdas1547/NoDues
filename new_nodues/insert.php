<?php 


include("./dbinfo.inc");



for($i=1;$i<10;$i++){
	$var="2014CS1020".(string)($i);
	echo $var;
$pending_sql = "INSERT INTO graduating VALUES('$i','$var')";

$pending_result = $con->query($pending_sql);
if(!$pending_result)
{
	echo "No";

}
else
{
	echo "Yes";
}


}




?>
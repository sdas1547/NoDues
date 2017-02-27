<?php
session_start();
//Used in many pages to show the faculty suggestions based on the entered kerberos id
include("dbinfo.inc");
if (isset($_SESSION["login_id"]) && isset($_POST["name"])){
	//If a user is logged in and the script has been called with some login id
	$name = mysqli_real_escape_string($con,$_POST["name"]);
	//Search LDAP IIT Delhi
	$ldaphost = "ldap1.iitd.ernet.in";
	$ldapconn = ldap_connect($ldaphost)
				or die("Could not connect to {$ldaphost}");
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
	$l_search = ldap_search($ldapconn,"dc=iitd,dc=ernet,dc=in","(&(|(username=*$name*)(uid=$name*)) (|(category=faculty) (category=vfaculty) (category=exfaculty) (category=adjunct) (category=emeritus) (category=retfaculty)(category=hod)(category=head)))",array("uid","username","category"));
	$l_result = ldap_get_entries($ldapconn,$l_search);
	if ($l_result["count"] == 0){
		echo "<a class='list-group-item'>No Match Found for faculty name '$name'</a>";
	}
	else {
		for ($i=0; $i<$l_result["count"] && $i < 10; $i++){
			//Each page which calls this script, mentions a function name also
			?>
			<a onclick="<?php echo $_POST["function_name"]."('".$l_result[$i]["uid"][0]."','".$l_result[$i]["username"][0]."')"?>" class="list-group-item"><?php echo $l_result[$i]["username"][0]." (".$l_result[$i]["uid"][0].")" ?></a>
			<?php
		}
	}
}
$con->close();
?>
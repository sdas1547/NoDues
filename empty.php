<?php

$uid=$_GET["uid"];


function get_name($uid){ 
	$ldaphost = "ldap1.iitd.ernet.in"; 
	$ldapconn = ldap_connect($ldaphost) 
					or die("Could not connect to {$ldaphost}"); 
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3); 

	$l_search = ldap_search($ldapconn,"dc=iitd,dc=ernet,dc=in","uid=$uid"); 

	$l_result = ldap_get_entries($ldapconn,$l_search); 
	header('Content-type: text/javascript');
		
		

	echo json_encode($l_result);
} 


get_name($uid);


?>
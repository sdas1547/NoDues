<?php
session_start();
//Included in index.php
include("./new_nodues/dbinfo.inc");
include("./new_nodues/functions.php");
	//If a session with this id is stored, remove it from the database (session has expired)
	//Also, remove all the sessions which are older than one day
	$session_id = session_id();
	$query = "DELETE FROM sessions WHERE `session_id` = '$session_id' OR timestamp < '$oneday'";
	$session_clear = $con->query($query);
	$login_err = " ";
	if (isset($_SESSION["login_status"])){
		$login_err = $_SESSION["login_status"];
		unset($_SESSION["login_status"]);
	}
	$login_id = "";
	$client_id = "qW63F5ykX3H7Egr96lzCcRV4TkFxYBTp";
	$client_secret = "xZsd51x34fbJwCSCYsfakbedwULvwFU3";
	if (isset($_GET["code"])){
		//$_GET["code"] is set when the user logs in with kerberos thrugh Oauth
		//Get the access token from ouath
		/*if (strpos($_SERVER['HTTP_REFERER'],"https://oauth.iitd.ac.in") === False || strpos($_SERVER['HTTP_REFERER'],"https://oauth.iitd.ac.in")!=0){
			$_SESSION["login_status"] = "Only oauth login is allowed";
			header("Location: ../logout");
			die;
		}*/
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://oauth.iitd.ac.in/token.php");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "client_id=$client_id&client_secret=$client_secret&grant_type=authorization_code&code=". $_GET['code']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = json_decode(curl_exec($ch));
		curl_close($ch);
		if (isset($result->access_token)){
			//If we get access token, use the access token to get kerberos login id
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://oauth.iitd.ac.in/resource.php");
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "access_token=".$result->access_token);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			$result1 = json_decode(curl_exec($ch));
			curl_close($ch);
			//var_dump($result1);
			//die;
			$login_id = $result1->user_id;
			//Validate the kerberos login id to validate the user through ldap - Only faculty and ug students are allowed
			    $_SESSION["login_id"] = $result1->user_id;
				$_SESSION["mail"] = $result1->mail;
				$_SESSION["name"] = $result1->name;
				$_SESSION["entry_no"] = $result1->uniqueiitdid;
				$_SESSION["category"] = $result1->category;
				$_SESSION["department"] = $result1->department;
				$faculty_categ = array("faculty", "vfaculty", "exfaculty", "adjunct", "emeritus", "retfaculty", "hod", "head");
				$hod_categ = array("hod", "head");
				if(in_array($_SESSION["category"],$faculty_categ)){
					$_SESSION["faculty"] = "Yes";
				}
				if(in_array($_SESSION["category"],$hod_categ)){
					$_SESSION["hod"] = "Yes";
				}
				$login_err="";
				$login_id = $_SESSION["login_id"];
				$login_ip = $_SERVER['REMOTE_ADDR'];
				$session_id = session_id();
				//Save session id, login id and ip address in database - each session id is mapped to an ip address
				$query = "INSERT INTO sessions (`session_id`,`login_id`, `login_ip`, `timestamp`) VALUES ('$session_id','$login_id', '$login_ip', '$timestamp')";
				put_log($query);
				$login_update = $con->query($query);
		}
		else{
			//If oauth doesn't give access token, login failed
			$login_err = "Login Failure";
		}
}
	if (!empty($login_err)){
	//Login error empty means that user hasn't logged in
		header("Location: https://oauth.iitd.ac.in/authorize.php?response_type=code&client_id=".$client_id."&state=xyz");
	
		//<div class="form-horizontal">
		//	<div class="form-group has-error"> 
		//		<div class="col-sm-offset-3 col-sm-7 text-center">
		//			<label class="control-label" style="text-align:left;"><?php echo $login_err; ?-></label>
		//		</div>
		//	</div>
			// <div class="form-group">
				// <div class="col-sm-offset-3 col-sm-7" style="text-align:center">
					// <button type="submit" class="btn btn-success btn-block" onclick="window.location.assign('https://oauth.iitd.ac.in/authorize.php?response_type=code&client_id=<?php echo $client_id ?->&state=xyz')">Login with Kerberos</button><br>
				// </div>
			// </div>
		// </div>
	}
	else{
		//When user is logged in
		echo "Login Successful. Redirecting...";
		// $a_query = $con->query("SELECT count(login_id) FROM `admin` WHERE `login_id` = '".$login_id."' LIMIT 1")->fetch_assoc();
		// if ($a_query["count(login_id)"] != 0){
		// 	$_SESSION["rms_admin"] = "Yes";
		// }
		// $a_query = $con->query("SELECT count(login_id) FROM `admin_tti` WHERE `login_id` = '".$login_id."' LIMIT 1")->fetch_assoc();
		// if ($a_query["count(login_id)"] != 0){
		// 	$_SESSION["tti_admin"] = "Yes";
		// }
		// $a_query = $con->query("SELECT count(location_id) FROM `locations` WHERE `authority1_id` = '".$login_id."' LIMIT 1")->fetch_assoc();
		// if ($a_query["count(location_id)"] != 0){
		// 	$_SESSION["authority1"] = "Yes";
		// }
		// $a_query = $con->query("SELECT count(location_id) FROM `locations` WHERE `authority2_id` = '".$login_id."' LIMIT 1")->fetch_assoc();
		// if ($a_query["count(location_id)"] != 0){
		// 	$_SESSION["authority2"] = "Yes";
		// }
		header("Location: ./index");
		die;
	}
	?>
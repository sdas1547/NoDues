<?php
//Included in almost all pages
?>
<div id="header" class="hidden-print"><!--Hidden print because we don't want the default header to be printed-->
	<a href="../"><img src="../images/logo-old.png"></a> 
	<?php
		$server = "helpline.iitd.ac.in";
		//$server = "localhost";
		$domain = "http://".$server;
		//Make sure that the user is using ac.in domain and not ernet.in
		if ($_SERVER["SERVER_NAME"] != $server){
			header("Location: $domain".$_SERVER["REQUEST_URI"]);
			die;
		}
		
		if ($_SERVER["REQUEST_METHOD"] == "POST"){	//If a form has been submitted, it must be on this domain only - to prevent bypassing of checks
			if (strpos($_SERVER['HTTP_REFERER'],$domain) === False || strpos($_SERVER['HTTP_REFERER'],$domain)!=0){
				$_SESSION["login_status"] = "Session Expired";
				header("Location: ../logout");
				die;
			}
		}
		
		include ("functions.php");
//		clear_queue();
		if (!isset($_SESSION['login_id'])) {
			//If user is not logged in, redirect to home page, but save the current url to redirect after login
			if (strpos($_SERVER['REQUEST_URI'],'index') == false){ //No redirection if user at home page - to prevent redirect loop
				$_SESSION["continue_url"] = $_SERVER["REQUEST_URI"];
				$_SESSION["login_status"] = "Please login to continue";
				header("Location: ../index");
				die;
			}
			?>
			</div>
			<?php
		}
		else if (!validate_session()){
			//If a user is logged in, session must be validated against ip address and login id - To prevent hacks where hacker changes his/her session id to avoid login
			$_SESSION["login_status"] = "Session Expired";
			header("Location: ../logout");
			die;
		}
		else
		{
			//If a valid user is logged in, continue
			if (isset($_SESSION["continue_url"])){
				//If some continue_url was saved before login, take the user there, but unset the session variable before that to prevent redirect loop
				$continue_url = $_SESSION["continue_url"];
				unset($_SESSION["continue_url"]);
				header("Location: $continue_url");
				die;
			}
			$entry_no = $_SESSION['entry_no'];
			$name = $_SESSION['name'];
			$login_id = $_SESSION['login_id'];
			unset($_SESSION["rms_admin"]);
			unset($_SESSION["tti_admin"]);
			unset($_SESSION["authority1"]);
			unset($_SESSION["authority2"]);
			//Check for RMS Admin
			$a_query = $con->query("SELECT count(login_id) FROM `admin` WHERE `login_id` = '".$login_id."' LIMIT 1")->fetch_assoc();
			if ($a_query["count(login_id)"] != 0){
				$_SESSION["rms_admin"] = "Yes";
			}
			$a_query = $con->query("SELECT count(login_id) FROM `admin_tti` WHERE `login_id` = '".$login_id."' LIMIT 1")->fetch_assoc();
			if ($a_query["count(login_id)"] != 0){
				$_SESSION["tti_admin"] = "Yes";
			}
			$a_query = $con->query("SELECT count(location_id) FROM `locations` WHERE `authority1_id` = '".$login_id."' LIMIT 1")->fetch_assoc();
			if ($a_query["count(location_id)"] != 0){
				$_SESSION["authority1"] = "Yes";
			}
			$a_query = $con->query("SELECT count(location_id) FROM `locations` WHERE `authority2_id` = '".$login_id."' LIMIT 1")->fetch_assoc();
			if ($a_query["count(location_id)"] != 0){
				$_SESSION["authority2"] = "Yes";
			}
			?>
			<div class="logout_button">
			<button class="btn btn-primary" type="button" onclick="window.location.assign('../logout')">Logout (<?php echo $_SESSION["login_id"] ?>)</button>
			</div>
			<div class="home_button">
			<button class="btn btn-primary" type="button" onclick="window.location.assign('../index')">Home</button>
			</div>
			</div>
			<?php
		}
		?>
<div class="hidden-print"><br><br><br><br><br></div><!--Hidden print because we don't want the default header to be printed-->
<!--Visible print because we want this header to be printed, but not displayed-->
<div class="visible-print" style="border-bottom:1px solid #888; text-align:center"><img src="../images/logo1.png" class="print-logo"><div class="print-header"><h3>Indian Institute of Technology, Delhi</h3><h4>Room Management System</h4></div></div>
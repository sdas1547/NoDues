<?php
include('dbinfo.inc');
//Some standards time variables to be used in various pages
date_default_timezone_set("Asia/Calcutta");
$date=date_create();
$timestamp = date_format($date,"Y-m-d H:i:s");
$download_date = date_format($date,"Y-m-d-H-i-s");
$upload_date = date_format($date,"Y-m-d-H-i-s");
$curr_date = date_format($date,"Y-m-d");
$curr_time = date_format($date,"H:i");
$min_booking_date = date("Y-m-d",strtotime(date("Y-m-d H:i:s")." +12 hour"));
$min_booking_time = date("H:i",strtotime(date("Y-m-d H:i:s")." +12 hour"));
$oneday = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." -1 day"));	//Used to delete old sessions
$oneweek = date("Y-m-d",strtotime(date("Y-m-d H:i:s")." +7 day"));	//Used to delete old sessions
$username = "ngucoord";
//Comented by Sahil for Student Helpline
// $working_hours = $con->query("SELECT * FROM working_hours LIMIT 1")->fetch_assoc();
// $working_hour_start = $working_hours["start_time"];
// $working_hour_end = $working_hours["end_time"];

if (!function_exists('validate_login')){
	//Define the validate_login function, used to login a user in login.php
	function validate_login($login_id){
		$ldaphost = "ldap1.iitd.ernet.in";
		$ldapconn = ldap_connect($ldaphost)
					or die("Could not connect to {$ldaphost}");
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		$l_search = ldap_search($ldapconn,"dc=iitd,dc=ernet,dc=in","(&(uid=$login_id)(category=*))",array("uid","username","category","uniqueiitdid","department"));
		$l_result = ldap_get_entries($ldapconn,$l_search);
		$faculty_categ = array("faculty", "vfaculty", "exfaculty", "adjunct", "emeritus", "retfaculty", "hod", "head");
		$hod_categ = array("hod", "head");
		if ($l_result["count"] == 1){
			//If id is valid, set the session variables
			$_SESSION["login_id"] = $l_result[0]["uid"][0];
			$_SESSION["mail"] = $l_result[0]["uid"][0]."@iitd.ac.in";
			$_SESSION["name"] = $l_result[0]["username"][0];
			$_SESSION["entry_no"] = $l_result[0]["uniqueiitdid"][0];
			$_SESSION["category"] = $l_result[0]["category"][0];
			$_SESSION["department"] = $l_result[0]["department"][0];
			if(in_array($_SESSION["category"],$faculty_categ)){
				$_SESSION["faculty"] = "Yes";
			}
			if(in_array($_SESSION["category"],$hod_categ)){
				$_SESSION["hod"] = "Yes";
			}
			return True;
		}
		else{
			//The login error to show on login failure
			$_SESSION["login_status"] = "Access Denied.";
			return False;
		}
	}
}

if (!function_exists('validate_session')){
	//Validate a session against the IP address and login id, to prevent hacks
	function validate_session(){
		include('dbinfo.inc');
		$login_id = $_SESSION["login_id"];
		$login_ip = $_SERVER['REMOTE_ADDR'];
		$session_id = session_id();
		$check_id = $con->query("SELECT * FROM sessions WHERE session_id = '$session_id' AND login_id = '$login_id' AND login_ip = '$login_ip'");
		if ($check_id->num_rows!=0)
			return True;
		else{
			$check_id = $con->query("DELETE FROM sessions WHERE session_id = '$session_id'");
			return False;
		}
	}
}

if (!function_exists('validate_hod')){
	function validate_hod($login_id){
		$ldaphost = "ldap1.iitd.ernet.in";
		$ldapconn = ldap_connect($ldaphost)
					or die("Could not connect to {$ldaphost}");
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		$l_search = ldap_search($ldapconn,"dc=iitd,dc=ernet,dc=in","(&(uid=$login_id) (|(category=head) (category=hod)))",array("uid","username","category"));
		$l_result = ldap_get_entries($ldapconn,$l_search);
		if ($l_result["count"] == 1){
			ldap_close($ldapconn);
			return True;
		}
		else {
			ldap_close($ldapconn);
			return False;
		}
	}
}

if (!function_exists('validate_user')){		//Admin and TTI update
	//Validate a user id against LDAP, used whenever a user submits a form with some user id
	function validate_user($login_id){
		$ldaphost = "ldap1.iitd.ernet.in";
		$ldapconn = ldap_connect($ldaphost)
					or die("Could not connect to {$ldaphost}");
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		$l_search = ldap_search($ldapconn,"dc=iitd,dc=ernet,dc=in","(&(uid=$login_id) (|(category=faculty) (category=vfaculty) (category=exfaculty) (category=adjunct) (category=emeritus) (category=retfaculty)(category=hod)(category=head)))",array("uid","username","category"));
		$l_result = ldap_get_entries($ldapconn,$l_search);
		if ($l_result["count"] == 1){
			return True;
		}
		else {
			return False;
		}
	}
}

if (!function_exists('get_name')){
	//Get the student name using student entry number from LDAP. Used while uploading CSV. If user is not found, return False
	function get_name($entry_no){
		$ldaphost = "ldap1.iitd.ernet.in";
		$ldapconn = ldap_connect($ldaphost)
					or die("Could not connect to {$ldaphost}");
		ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
		$l_search = ldap_search($ldapconn,"dc=iitd,dc=ernet,dc=in","(&(uniqueiitdid=$entry_no) (|(category=faculty) (category=vfaculty) (category=exfaculty) (category=adjunct) (category=emeritus) (category=retfaculty)(category=hod)(category=head) (&(category=student)(department=admin))))",array("uid","username","category"));
		$l_result = ldap_get_entries($ldapconn,$l_search);
		if ($l_result["count"] == 1){
			return $l_result[0]["username"][0];
		}
		else{
			return False;
		}
	}
}

if (!function_exists('get_day')){
	//Get the student name using student entry number from LDAP. Used while uploading CSV. If user is not found, return False
	function get_day($date){
		$day_number = date("N", strtotime($date));
		switch($day_number){
			case 1:
				return "Monday";
			case 2:
				return "Tuesday";
			case 3:
				return "Wednesday";
			case 4:
				return "Thursday";
			case 5:
				return "Friday";
			case 6:
				return "Saturday";
			default:
				return "Sunday";
		}
	}
}

if (!function_exists('get_day_sem')){
	//Get the student name using student entry number from LDAP. Used while uploading CSV. If user is not found, return False
	function get_day_sem($date){
		include('dbinfo.inc');
		$check_holiday = $con->query("SELECT * FROM semesters WHERE exams LIKE '%$date%'");
		if ($check_holiday->num_rows != 0)
			return "Exam";
		$check_in_sem = $con->query("SELECT * FROM semesters WHERE start_date <= '$date' AND end_date >= '$date'");
		if ($check_in_sem->num_rows == 0)
			return "Sunday";
		$semester = $check_in_sem->fetch_assoc();
		$exams = json_decode($semester["exams"]);
		for($i=0; $i<count($exams);$i++){
			if ($exams[$i][1] == "Major"){
				$major_dates[] = $exams[$i][0];
			}
		}
		sort($major_dates);
		if($date >= $major_dates[0])
			return "Holiday";
		$check_holiday = $con->query("SELECT * FROM semesters WHERE holidays LIKE '%$date%'");
		if ($check_holiday->num_rows != 0)
			return "Holiday";
		$check_change = $con->query("SELECT * FROM semesters WHERE day_changes LIKE '%$date%'");
		if ($check_change->num_rows != 0){
			$change_row = $check_change->fetch_assoc();
			$day_changes = json_decode($change_row["day_changes"]);
			for ($j=0; $j<count($day_changes); $j++){
				if ($day_changes[$j][0] == $date)
					return $day_changes[$j][1];
			}
		}
		else
			return get_day($date);
	}
}

if (!function_exists('check_pass_mail')){
	//Used to send system mails
	function check_pass_mail($subject,$body,$pass){		//$to must be just the user id, like ee1120445
		global $username;
		require_once "Mail.php";
		$from = "Room Booking Update<noreply@roombooking.iitd.ac.in>";
		$host = "ssl://smtp.iitd.ac.in";
		$port = "465";
		$to = $username."@iitd.ac.in";
		date_default_timezone_set("Asia/Calcutta");
		$date=date_create();
		$header_timestamp = date_format($date, "D, d M Y H:i:s")." +0530 (IST)";
		$timestamp = date_format($date,"Y-m-d H:i:s");
		$headers = array ('From' => $from,
			'To' => $to,
			'Subject' => $subject,
			'Date' => $header_timestamp);
		$smtp = Mail::factory('smtp',
			array ('host' => $host,
				'port' => $port,
				'auth' => true,
				'username' => $username."@iitd.ac.in",
				'password' => $pass ));
		$mail = $smtp->send($to, $headers, $body);
		if (PEAR::isError($mail)){
			return False;
		}
		else
			return True;
	}
}

if (!function_exists('get_in')){
	//Get the password for given username from database.
	function get_in($username){
		include('dbinfo.inc');
		//Put a custom encryption here
		return "password";
	}
}

if (!function_exists('send_mail')){
	//Used to send system mails
	function send_mail($to,$subject,$body){		//$to must be just the user id, like ee1120445
		include('dbinfo.inc');
		
		$from = "Room Management System<noreply@roombooking.iitd.ac.in>";
		$to = $to."@iitd.ac.in";

		date_default_timezone_set("Asia/Calcutta");
		$date=date_create();
		$header_timestamp = date_format($date, "D, d M Y H:i:s")." +0530 (IST)";
		$timestamp = date_format($date,"Y-m-d H:i:s");
		
		$headers = array ('From' => $from,
			'To' => $to,
			'Subject' => $subject,
			'Date' => $header_timestamp);
		
		$to = mysqli_real_escape_string($con,json_encode($to));
		$headers = mysqli_real_escape_string($con,json_encode($headers));
		$body = mysqli_real_escape_string($con,json_encode($body));
		
		$con->query("INSERT INTO mail_queue (`to`, `headers`, `body`, `timestamp`) VALUES ('$to', '$headers', '$body', '$timestamp')");
		$con->close();
//		clear_queue();
		return True;
	}
}

if (!function_exists('send_bulk_mail')){
	//Used to send system mails
	function send_bulk_mail($to,$to_alias,$from_alias,$subject,$body){		//$to must be comma separated email ids
		include('dbinfo.inc');
		
		$from = $from_alias." <noreply@roombooking.iitd.ac.in>";
		
		date_default_timezone_set("Asia/Calcutta");
		$date=date_create();
		$header_timestamp = date_format($date, "D, d M Y H:i:s")." +0530 (IST)";
		$timestamp = date_format($date,"Y-m-d H:i:s");
		
		$headers = array ('From' => $from,
			'To' => $to_alias."<noreply@roombooking.iitd.ac.in>",
			'Subject' => $subject,
			'Date' => $header_timestamp);
		
		$to = mysqli_real_escape_string($con,json_encode($to));
		$headers = mysqli_real_escape_string($con,json_encode($headers));
		$body = mysqli_real_escape_string($con,json_encode($body));
		$con->query("INSERT INTO mail_queue (`to`, `headers`, `body`, `timestamp`) VALUES ('$to', '$headers', '$body', '$timestamp')");
		$con->close();
//		clear_queue();
		return True;
	}
}

if (!function_exists('alert_admin')){
	function alert_admin($subject,$body){
		include('dbinfo.inc');
		$admin_query = $con->query("SELECT * FROM admin");
		$to = "";
		$curr = 0;
		while($admin = $admin_query->fetch_assoc()){
			if ($curr==20){
				send_bulk_mail($to,"RMS Administrators","RMS Update",$subject,$body);
				$to_addr = "";
				$curr = 0;
			}
			$curr++;
			$to .= $admin["login_id"]."@iitd.ac.in,";
		}
		if ($curr > 0){
			send_bulk_mail($to,"RMS Administrators","RMS Update",$subject,$body);
		}
	}
}

// if (!function_exists('send_file')){
// 	//Used to send database backup files etc, as a secondary backup
// 	function send_file($to,$subject,$content,$file){	//$to must be just the user id, like ee1120445
// 		include('dbinfo.inc');
// 		include('Mail/mime.php');
		
// 		$from = "RMS Update<noreply@roombooking.iitd.ac.in>";
		
// 		date_default_timezone_set("Asia/Calcutta");
// 		$date=date_create();
// 		$header_timestamp = date_format($date, "D, d M Y H:i:s")." +0530 (IST)";
// 		$timestamp = date_format($date,"Y-m-d H:i:s");
		
// 		$headers = array ('From' => $from,
// 			'To' => $to,
// 			'Subject' => $subject,
// 			'Date' => $header_timestamp);
		
// 		$filename = end(explode('/',$file));	//File name
// 		$size = filesize($file);				//File size
// 		$mime =& new Mail_mime();
// 		$mime->setTXTBody($content);
// 		$mime->setHTMLBody($content);			//Set content to be displayed
// 		$mime->addAttachment($file, 'text/plain; name='.$filename,'',true,'base64', 'attachment; filename='.$filename.'; size='.$size);	//File details
// 		$body = $mime->get();					//Get the body for mime mail
// 		$headers = $mime->headers($headers, true);	//Get the headers for mime mail
		
// 		$to = mysqli_real_escape_string($con,json_encode($to));
// 		$headers = mysqli_real_escape_string($con,json_encode($headers));
// 		$body = mysqli_real_escape_string($con,json_encode($body));
		
// 		$con->query("INSERT INTO mail_queue (`to`, `headers`, `body`, `timestamp`) VALUES ('$to', '$headers', '$body', '$timestamp')");
// 		$con->close();
// //		clear_queue();
// 		return True;
// 	}
// }

if (!function_exists('clear_queue')){
	function clear_queue(){
		global $username;
		include ('dbinfo.inc');
		date_default_timezone_set("Asia/Calcutta");
		$date=date_create();
		$timestamp = date_format($date,"Y-m-d H:i:s");
		$mail_time = date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")." - 10 sec"));
		$records_query = $con->query("SELECT last_run FROM maintenance WHERE task_name = 'clear_queue'")->fetch_assoc();
		if ($records_query["last_run"]!= NULL && $records_query["last_run"] < $mail_time){
			require_once "Mail.php";
			$host = "ssl://smtp.iitd.ac.in";
			$port = "465";
			$smtp = Mail::factory('smtp',
				array ('host' => $host,
					'port' => $port,
					'auth' => true,
					'username' => $username."@iitd.ac.in",
					'password' => get_in($username) ));
			$mails = $con->query("SELECT * FROM mail_queue WHERE try_sent < 5 ORDER BY timestamp LIMIT 2");	//Can send 60 in 5 minutes.	Send 2 in every 10 seconds
			if ($mails->num_rows != 0){
				while ($mail_row = $mails->fetch_assoc()){
					$mail_id = $mail_row["mail_id"];
					$to = json_decode($mail_row["to"], true);
					$headers = json_decode($mail_row["headers"], true);
					$body = json_decode($mail_row["body"], true);
					$mail = $smtp->send($to, $headers, $body);
					if (PEAR::isError($mail)){
						error_log($mail->getMessage());
						$con->query("UPDATE mail_queue SET try_sent = try_sent + 1 WHERE mail_id = '$mail_id'");
					}
					else{
						$con->query("DELETE FROM mail_queue WHERE mail_id = '$mail_id'");
					}
				}
			}
			$con->query("UPDATE maintenance SET last_run = '$timestamp' WHERE task_name = 'clear_queue'");
		}
		$con->close();
		return True;
	}
}

if (!function_exists('show_links')){
	//Show links when URLs are mentioned in the description
	function show_links($text){
		$regex = '@((https?://)?([-\w]+\.[-\w\.]+)+\w(:\d+)?(/([-\w/_\.\,]*(\?\S+)?)?)*)@';
		$regex = '@((https?://)([-\w\.]+)+\w(:\d+)?(/([-\w/_\.\,\%]*(\?\S+)?)?)*)@'; //http or https is required
		//$Db='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';
		//$regex = "~^(https?)://($Db?\\.)+$Db(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i";
		return preg_replace($regex, '<a href="$1" target="_blank">$1</a>', $text);
	}
}

if (!function_exists('generateRandomString')){
	//Generate random string to set mail links in NEN201
	function generateRandomString($length) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, 61)];
		}
		return $randomString;
	}
}

if (!function_exists('paginate')){
	//Pagination function is used when there are more than 10 results to show in any page
	function paginate($link, $upper_limit, $page){
		//$link - the base url of the page
		//$upper_limit - the upper_limit on the page numbers (max_records/records_per_page)
		//$page - the current page number
		$lower_limit = $page - 4;
		if ($lower_limit<1)
			$lower_limit = 1;
		$pseudo_upper = $lower_limit+9;
		if ($pseudo_upper>$upper_limit){
			$pseudo_upper = $upper_limit;
		}
		$paginate_text = '<nav><ul class="pagination">'."\n";
		if ($page>1){ //The user is not at the first page
			$paginate_text .= '<li><a href="'.$link.'page=1" aria-label="First"><span aria-hidden="true">&laquo;</span></a></li>'."\n";
		}
		else{	//The user is at the first page
			$paginate_text .= '<li class="disabled"><span aria-label="First"><span aria-hidden="true">&laquo;</span></span></li>'."\n";
		}
		if ($page>1){	//The user is not at the first page
			$paginate_text .= '<li><a href="'.$link.'page='.($page-1).'" aria-label="Previous"><span aria-hidden="true">&lsaquo;</span></a></li>'."\n";
		}
		else{	//The user is at the first page
			$paginate_text .= '<li class="disabled"><span aria-label="Previous"><span aria-hidden="true">&lsaquo;</span></span></li>'."\n";
		}
		for ($curr = $lower_limit; $curr <= $pseudo_upper; $curr++){
			if ($curr == $page) //The user is at the page
				$paginate_text .= '<li class="active"><span>'.$curr.'<span class="sr-only">(current)</span></span></li>'."\n";
			else //The user is not at the page
				$paginate_text .= '<li><a href="'.$link.'page='.$curr.'">'.$curr.'</a></li>'."\n";
		}
		if ($page<$upper_limit){	//The user is not at the last page
			$paginate_text .= '<li><a href="'.$link.'page='.($page+1).'" aria-label="Next"><span aria-hidden="true">&rsaquo;</span></a></li>'."\n";
		}
		else{	//The user is at the last page
			$paginate_text .= '<li class="disabled"><span aria-label="Next"><span aria-hidden="true">&rsaquo;</span></span></li>'."\n";
		}
		if ($page<$upper_limit){	//The user is not at the last page
			$paginate_text .= '<li><a href="'.$link.'page='.$upper_limit.'" aria-label="Last"><span aria-hidden="true">&raquo;</span></a></li>'."\n";
		}
		else{	//The user is at the last page
			$paginate_text .= '<li class="disabled"><span aria-label="Last"><span aria-hidden="true">&raquo;</span></span></li>'."\n";
		}
		$paginate_text .= '</ul></nav>';
		return($paginate_text);
	}
}

if (!function_exists('put_log')){
	//Put all sql commands in a log file, along with user id, timestamp and ip address
	function put_log($query){
		date_default_timezone_set("Asia/Calcutta");
		$date=date_create();
		$timestamp = date_format($date,"Y-m-d H:i:s");
		$sql = "[".$timestamp."] [client: ".$_SERVER['REMOTE_ADDR']."] ";
		if (isset($_SESSION["login_id"])){
			$sql .= "[login_id: ".$_SESSION["login_id"]."] ";
		}
		$sql .= "[query: ".$query."]\n";
		$sql_log_file = "/var/www/roombooking/http/logs/sql.log";
		// $sql_log = fopen($sql_log_file, "a");
		// fwrite($sql_log,$sql);
		// fclose($sql_log);
	}
}

if (!function_exists('error_name')){
	//Get error name when error page is shown - As all error codes show the same error page
	function error_name(){
		$code = $_SERVER["REDIRECT_STATUS"];
		switch ($code) {
			case 100: $text = 'Continue'; break;
			case 101: $text = 'Switching Protocols'; break;
			case 200: $text = 'OK'; break;
			case 201: $text = 'Created'; break;
			case 202: $text = 'Accepted'; break;
			case 203: $text = 'Non-Authoritative Information'; break;
			case 204: $text = 'No Content'; break;
			case 205: $text = 'Reset Content'; break;
			case 206: $text = 'Partial Content'; break;
			case 300: $text = 'Multiple Choices'; break;
			case 301: $text = 'Moved Permanently'; break;
			case 302: $text = 'Moved Temporarily'; break;
			case 303: $text = 'See Other'; break;
			case 304: $text = 'Not Modified'; break;
			case 305: $text = 'Use Proxy'; break;
			case 400: $text = 'Bad Request'; break;
			case 401: $text = 'Unauthorized'; break;
			case 402: $text = 'Payment Required'; break;
			case 403: $text = 'Forbidden'; break;
			case 404: $text = 'Not Found'; break;
			case 405: $text = 'Method Not Allowed'; break;
			case 406: $text = 'Not Acceptable'; break;
			case 407: $text = 'Proxy Authentication Required'; break;
			case 408: $text = 'Request Time-out'; break;
			case 409: $text = 'Conflict'; break;
			case 410: $text = 'Gone'; break;
			case 411: $text = 'Length Required'; break;
			case 412: $text = 'Precondition Failed'; break;
			case 413: $text = 'Request Entity Too Large'; break;
			case 414: $text = 'Request-URI Too Large'; break;
			case 415: $text = 'Unsupported Media Type'; break;
			case 500: $text = 'Internal Server Error'; break;
			case 501: $text = 'Not Implemented'; break;
			case 502: $text = 'Bad Gateway'; break;
			case 503: $text = 'Service Unavailable'; break;
			case 504: $text = 'Gateway Time-out'; break;
			case 505: $text = 'HTTP Version not supported'; break;
			default:
				$text = 'Unknown http status code "' . htmlentities($code) . '"';
				put_log($text);
			break;
		}
		return $code." - ".$text;
	}
}
?>
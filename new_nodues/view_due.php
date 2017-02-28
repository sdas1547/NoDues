<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>View Due Details</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="./mystyle.css" rel="stylesheet">
	</head>
	<?php
		session_start();
		include("./header.php");
		include("./dbinfo.inc");

		if(isset($_GET["due_id"])){
			$due_id = $_GET["due_id"];
		}
		else{	
			echo "NO data found!";	
		}

		
		$state = -1;
		if(isset($_SESSION["entry_no"])){
			$state = 1;
			$user = $_SESSION["entry_no"];
		}
		else if(isset($_SESSION["emp_no"])){
			$state = 2;
			$user = $_SESSION["emp_no"];
		}
		else{
			echo "You are not logged in";
			die;
		}
		
		
	?>
	<div class="container">
		<?php
			
			$student_pending = "student_pending".$user;
			$pending_dues_sql1 = "CREATE OR REPLACE VIEW $student_pending AS
										SELECT *
											FROM dues
												WHERE dueID = $due_id
												AND (employee_uID = '$user' OR entry_number = '$user');";
			
			$labDues = "labDues".$user;
			$pending_dues_sql2 = "CREATE OR REPLACE VIEW $labDues AS
										SELECT DISTINCT lab_code, employee_uID
											FROM $student_pending;";

			$labHeader = "labHeader".$user;
			$pending_dues_sql3 = "CREATE OR REPLACE VIEW $labHeader AS
									SELECT DISTINCT lab_info.lab_code, department_code, address
										FROM lab_info, $labDues
											WHERE lab_info.lab_code = $labDues.lab_code;";										

			$lab_details = "lab_details".$user;
			$pending_dues_sql4 = "CREATE OR REPLACE VIEW $lab_details AS
									SELECT a1.full_form AS lab_name, 
									a2.full_form AS department_name,
									lab_code, address
										FROM accronym AS a1, accronym AS a2, $labHeader
											WHERE a1.code = lab_code
											AND a2.code = department_code;";

			$employee_details = "employee_details".$user;
			$pending_dues_sql5 = "CREATE OR REPLACE VIEW $employee_details AS
									SELECT DISTINCT name as employee_name, uID as employee_uID 
										FROM user_table, $student_pending
											WHERE user_table.uID = employee_uID;";

			$student_details = "student_details".$user;
			$pending_dues_sql6 = "CREATE OR REPLACE VIEW $student_details AS
									SELECT DISTINCT name as student_name, entry_number from user_table, $student_pending
										WHERE user_table.uID = entry_number;";

			$due_query = "SELECT description,
								$student_pending.dueID, 
								generated_time, modified_time, 
								$student_details.entry_number, 
								student_name, status,
								employee_name, amount ,
								lab_name, department_name
								FROM $lab_details, $employee_details, $student_pending, $student_details
									WHERE $student_pending.lab_code = $lab_details.lab_code
									AND $student_pending.employee_uID = $employee_details.employee_uID 
										ORDER BY generated_time;";

			$pending_drop_sql = "DROP VIEW $student_pending, $labDues, $labHeader, $lab_details, $employee_details, $student_details;";

			// echo $pending_dues_sql1."<br>";
			// echo $pending_dues_sql2."<br>";
			// echo $pending_dues_sql3."<br>";
			// echo $pending_dues_sql4."<br>";
			// echo $pending_dues_sql5."<br>";
			// echo $pending_dues_sql6."<br>";
			// echo $due_query;


			$con->query($pending_dues_sql1);
			$con->query($pending_dues_sql2);
			$con->query($pending_dues_sql3);
			$con->query($pending_dues_sql4);
			$con->query($pending_dues_sql5);
			$con->query($pending_dues_sql6);			
			$due_result = $con->query($due_query);
			$con->query($pending_drop_sql);			
			while($due_data = $due_result->fetch_assoc()){

				$due_status = $due_data["status"];
				if(strcmp($due_status, "P")==0){
					$status_value = "Pending";
				}
				else if(strcmp($due_status, "C")==0){
					$status_value = "Cancelled";
				}
				else if(strcmp($due_status, "R")==0){
					$status_value = "Requested";
				}
				else if(strcmp($due_status, "A")==0){
					$status_value = "Approved";
				}

		?>
			<div class="panel panel-info">
				<div class="panel-heading">
					<strong>DUE DETAILS</strong>
				</div>				
				<div class="panel-body">
					<?php 
						echo "<strong>Due ID 		: </strong>".$due_data["dueID"]."<br>
							  <strong>Entry Number	: </strong>".$due_data["entry_number"]."<br>
							  <strong>Student Name	: </strong>".$due_data["student_name"]."<br>
							  <strong>Lab Name		: </strong>".$due_data["lab_name"]."<br>
							  <strong>Lab Department: </strong>".$due_data["department_name"]."<br>							  
							  <strong>Description	: </strong>".$due_data["description"]."<br>
							  <strong>Amount		: </strong>Rs. ".$due_data["amount"]."<br>
							  <strong>Status		: </strong>".$status_value."<br>
							  <strong>Added by		: </strong>".$due_data["employee_name"]."<br>
							  <strong>Added on		: </strong>".$due_data["generated_time"]."<br>
							  <strong>Uploaded File : </strong>";

					?>

					
								<?php 
									$filename="./uploads/".$due_id."_".$due_data["entry_number"].".pdf";
									if(!FILE_EXISTS($filename))
									{
										echo "No file available";										
									}
									else {
								?>
									<form class="form-horizontal" method="post" action="">
										<div class="form-group">
											<div class = "col-sm-offset-2">
											<?php
												echo "<embed src=".$filename." width=800px height=600px/>";
											?>
											</div>
										</div>
									</form>
								<?php
									}										
						 		 ?>
						 	
						<br>
						<br>
						<div class="form-group">
							<div class="col-sm-offset-4 col-sm-4">
								<button class="form-control btn btn-danger" type="reset" name="cancel_button" onclick="history.go(-1);">Back</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		<?php
			}
		?>
		<hr>	
	</div>
		<script src="script/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
	</body>
</html>
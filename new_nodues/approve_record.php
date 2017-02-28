<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Header</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="./mystyle.css" rel="stylesheet">
		<style>
			.error {color: #FF0000;}
		</style>
	</head>
	
	<?php
		session_start();
		include("./dbinfo.inc");
		include ("./header.php");	
	?>	
	
	<body>
	
	<?php
		$entryErr = $typeErr = $descErr = "";
		$amountErr = "";
		$type = "";
		
		if(isset($_GET["due_id"]) && isset($_SESSION["emp_no"])){
			$due_id = $_GET["due_id"];
			$emp_no = $_SESSION["emp_no"];
			$lab_id = $_GET["lab_id"];

		}		
		
		$correct_entry = false;
		if($_SERVER["REQUEST_METHOD"]=="POST"){
			$correct_entry = true;	
			$remark = $_POST["remark"];			
		}
		if($correct_entry){
			$con->query("SET AUTOCOMMIT = 0");
			$con->query("START TRANSACTION");
			$approve_sql1 = "UPDATE dues SET
								status = 'A'
									WHERE dueID = $due_id;";

			$approve_sql = "UPDATE duesra SET
								approved_comment = '$remark',
								approved_time = now()
									WHERE dueID = $due_id;";
			
			$approve_result1 = $con->query($approve_sql1);
			$approve_result = $con->query($approve_sql);			
			if ($approve_result1 && $approve_result){
				$con->query("COMMIT");
				// echo "Record Has been Successfully Approved;";
				header("Location: http://testportal.iitd.ac.in/new_nodues/lab_index.php?id=$lab_id");
				exit();
			}
			else{
				$con->query("ROLLBACK");
				// die;
				header("Location: http://testportal.iitd.ac.in/new_nodues/lab_index.php?id=$lab_id");
			}
		}
	?>
	
	
	<div class="container">
		<?php
			$student_pending = "student_pending".$emp_no;
			$pending_dues_sql1 = "CREATE OR REPLACE VIEW $student_pending AS
										SELECT dueID, amount, description, entry_number,
											generated_time, employee_uID, lab_code, modified_time
											FROM dues
												WHERE dueID = $due_id
												AND employee_uID = '$emp_no'
												AND status = 'R';";
			$labDues = "labDues".$emp_no;
			$pending_dues_sql2 = "CREATE OR REPLACE VIEW $labDues AS
										SELECT DISTINCT lab_code, employee_uID
											FROM $student_pending;";

			$labHeader = "labHeader".$emp_no;
			$pending_dues_sql3 = "CREATE OR REPLACE VIEW $labHeader AS
									SELECT DISTINCT lab_info.lab_code, department_code, address
										FROM lab_info, $labDues
											WHERE lab_info.lab_code = $labDues.lab_code;";										

			$lab_details = "lab_details".$emp_no;
			$pending_dues_sql4 = "CREATE OR REPLACE VIEW $lab_details AS
									SELECT a1.full_form AS lab_name, 
									a2.full_form AS department_name,
									lab_code, address
										FROM accronym AS a1, accronym AS a2, $labHeader
											WHERE a1.code = lab_code
											AND a2.code = department_code;";

			$employee_details = "employee_details".$emp_no;
			$pending_dues_sql5 = "CREATE OR REPLACE VIEW $employee_details AS
									SELECT DISTINCT name as employee_name, uID as employee_uID from user_table
										WHERE user_table.uID = '$emp_no';";

			$student_details = "student_details".$emp_no;
			$pending_dues_sql6 = "CREATE OR REPLACE VIEW $student_details AS
									SELECT DISTINCT name as student_name, entry_number from user_table, $student_pending
										WHERE user_table.uID = entry_number;";

			$due_query = "SELECT description,
								$student_pending.dueID, generated_time, modified_time, $student_details.entry_number, student_name,
								employee_name, amount , lab_name, department_name
								FROM $lab_details, $employee_details, $student_pending, $student_details
									WHERE $student_pending.lab_code = $lab_details.lab_code
									AND $student_pending.employee_uID = $employee_details.employee_uID ORDER BY generated_time;";

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

		?>
			<div class="panel panel-info">
				<div class="panel-heading">
					<strong>APPROVE RECORD</strong>
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
							  <strong>Added by		: </strong>".$due_data["employee_name"]."<br>
							  <strong>Added on		: </strong>".$due_data["generated_time"]."<br>
							  <strong>Last Modified	: </strong>".$due_data["modified_time"]."<br>
							  <strong>Uploaded File : </strong>";

					?>
					<form class="form-horizontal" method="post" action="">
						<div class="form-group">
							<div class = "col-sm-offset-2">
								<?php 
									$filename="./uploads/".$due_id."_".$due_data["entry_number"].".pdf";
									if(FILE_EXISTS($filename))
									{
										echo "<embed src=".$filename." width=800px height=600px/>";
									}
									else echo "No file available";
						 		 ?>
						 	</div>
						</div>
						<br>
						<br>

						<div class="form-group">
							<label class="control-lablel col-sm-1">Remark:</label>
							<div class="col-sm-10">
								<textarea class="form-control" rows="2" required column="40" name="remark"></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-3">
								<button class="form-control btn btn-success" type="submit" name="approve_button">Confirm Approve</button>
							</div>

							<div class="col-sm-offset-2 col-sm-3">
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
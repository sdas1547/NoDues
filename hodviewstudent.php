<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Header</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="./mystyle.css" rel="stylesheet">
	</head>
	<style>
		.table {
			font-size:15px;
		}
	</style>
	
	<?php
		include ("./header.php");
		session_start();
		include ("./dbinfo.inc");
		if (!isset($_SESSION["admin_id"])){
			header ("Location: ../index.php");
			die;
		}
		else{
			$admin_id = $_SESSION["admin_id"];
		}

		$entry_number=$_POST["entry"];

	?>	

	<body>

		<div class="container">
			<div class="row">
				<ul>
					<li><a href="#">Frequently Asked Questions (FAQs) </a></li>
					<li><a href="#">User Manual-Student</a></li>
				</ul>
				
					<hr>
				<ul>
					<li> <strong><?php echo "Dues for ".$entry_number;?></strong>
						<br><br>
						<ul class="nav nav-tabs">
							<li class="active" role="presentation">
								<a data-toggle="tab" href="#pending">Pending Records</a>
							</li>
							<li role="presentation">
								<a data-toggle="tab" href="#requested">Requested Records</a>
							</li>
							<li role="presentation">
								<a data-toggle="tab" href="#approved">Approved Records</a>
							</li>
							
						</ul>
						<br>
						<div class="tab-content">
							<div id="pending" class="tab-pane fade in active">
								<?php
									$student_pending = "student_pending".$entry_number;
									$pending_dues_sql1 = "CREATE OR REPLACE VIEW $student_pending AS
																SELECT dueID, amount, description,
																	generated_time, employee_uID, lab_code, modified_time
																	FROM dues
																		WHERE status = 'P'
																		AND entry_number = '$entry_number';";

									$labDues = "labDues".$entry_number;
									$pending_dues_sql2 = "CREATE OR REPLACE VIEW $labDues AS
																SELECT DISTINCT lab_code, employee_uID
																	FROM $student_pending;";

									$labHeader = "labHeader".$entry_number;
									$pending_dues_sql3 = "CREATE OR REPLACE VIEW $labHeader AS
															SELECT DISTINCT lab_info.lab_code, department_code, address
																FROM lab_info, $labDues
																	WHERE lab_info.lab_code = $labDues.lab_code;";										

									$lab_details = "lab_details".$entry_number;
									$pending_dues_sql4 = "CREATE OR REPLACE VIEW $lab_details AS
															SELECT a1.full_form AS lab_name, 
															a2.full_form AS department_name,
															lab_code, address
																FROM accronym AS a1, accronym AS a2, $labHeader
																	WHERE a1.code = lab_code
																	AND a2.code = department_code;";

									$employee_details = "employee_details".$entry_number;
									$pending_dues_sql5 = "CREATE OR REPLACE VIEW $employee_details AS
															SELECT DISTINCT name, employee_uID from user_table, $labDues
																WHERE user_table.uID = employee_uID;";

									$pending_dues_sql = "SELECT description,
															dueID, generated_time, name, amount , lab_name
															FROM $lab_details, $employee_details, $student_pending
																WHERE $student_pending.lab_code = $lab_details.lab_code
																AND $student_pending.employee_uID = $employee_details.employee_uID ORDER BY generated_time DESC;";

									$pending_drop_sql = "DROP VIEW $student_pending, $labDues, $labHeader, $lab_details, $employee_details;";

									$pending_result1 = $con->query($pending_dues_sql1);
									$pending_result2 = $con->query($pending_dues_sql2);
									$pending_result3 = $con->query($pending_dues_sql3);
									$pending_result4 = $con->query($pending_dues_sql4);
									$pending_result5 = $con->query($pending_dues_sql5);
									$pending_result = $con->query($pending_dues_sql); 
									$pending_drop_result = $con->query($pending_drop_sql);
									$pending_rows = mysqli_num_rows($pending_result);
									if($pending_rows>0){
								?>
									<table class="table table-sm table-striped table-hover">
										<tr>
											<th>S. No</th>
											<th>Amount</th>
											<th>Description</th>
											<th>Lab Name</th>
											<th>Added by</th>									
											<th>Added on</th>										
											
											<th> View</th>
										</tr>
								<?php
										$i = 1;
										while ($data = $pending_result->fetch_assoc()){
										echo "<tr>
											<td>".$i++."</td>
											<td>".$data["amount"]."</td>
											<td>".$data["description"]."</td>
											<td>".$data["lab_name"]."</td>
											<td>".$data["name"]."</td>
											<td>".$data["generated_time"]."</td>";
								?>
											
								<?php
											
											echo "<td><a href='./view_due.php?due_id=".$data["dueID"]."'>View</a>";
											echo "</tr>";
										}	
										echo "</table>";
									}
									else{
										echo "<strong>No records found</strong>";
									}
								?>
							</div>

							<div id="requested" class="tab-pane fade">
								<?php
									$student_requested = "student_requested".$entry_number;
									$requested_dues_sql1 = "CREATE OR REPLACE VIEW $student_requested AS
																SELECT dueID, amount, description,
																	generated_time, employee_uID, lab_code, modified_time
																	FROM dues
																		WHERE status = 'R'
																		AND entry_number = '2014CS10258';";

									$req_labDues = "req_labDues".$entry_number;
									$requested_dues_sql2 = "CREATE OR REPLACE VIEW $req_labDues AS
																SELECT DISTINCT lab_code, employee_uID
																	FROM $student_requested;";

									$req_labHeader = "req_labHeader".$entry_number;
									$requested_dues_sql3 = "CREATE OR REPLACE VIEW $req_labHeader AS
																SELECT DISTINCT lab_info.lab_code, department_code, address
																	FROM lab_info, $req_labDues
																		WHERE lab_info.lab_code = $req_labDues.lab_code;";

									$req_lab_details = "req_lab_details".$entry_number;
									$requested_dues_sql4 = "CREATE OR REPLACE VIEW $req_lab_details AS
																SELECT a1.full_form AS lab_name, 
																a2.full_form AS department_name,
																lab_code, address
																	FROM accronym AS a1, accronym AS a2, $req_labHeader
																		WHERE a1.code = lab_code
																		AND a2.code = department_code;";

									$req_dueIDs = "req_dueIDs".$entry_number;
									$requested_dues_sql6 = "CREATE OR REPLACE VIEW $req_dueIDs AS
																SELECT dueID
																	FROM $student_requested;";

									$req_details = "req_details".$entry_number;
									$requested_dues_sql7 = "CREATE OR REPLACE VIEW $req_details AS
																SELECT duesra.dueID, requested_time, requested_comment
																	FROM duesra, $req_dueIDs
																		WHERE $req_dueIDs.dueID = duesra.dueID;";


									$requested_dues_sql = "SELECT description, $req_details.dueID,
																requested_time, requested_comment , amount , lab_name
																FROM $req_lab_details, $req_details, $student_requested
																	WHERE $student_requested.lab_code = $req_lab_details.lab_code
																	AND $req_details.dueID = $student_requested.dueID ORDER BY requested_time DESC;";

									$requested_drop_sql = "DROP VIEW $student_requested, $req_labDues, $req_labHeader, $req_lab_details, $req_dueIDs, $req_details;";
									
									$requested_result1 = $con->query($requested_dues_sql1);
									$requested_result2 = $con->query($requested_dues_sql2);
									$requested_result3 = $con->query($requested_dues_sql3);
									$requested_result4 = $con->query($requested_dues_sql4);
									$requested_result6 = $con->query($requested_dues_sql6); 
									$requested_result7 = $con->query($requested_dues_sql7);

									$requested_result = $con->query($requested_dues_sql);
									$requested_drop_result = $con->query($requested_drop_sql);
									$requested_rows = mysqli_num_rows($requested_result);
									if($requested_rows>0){
								?>
									<table class="table table-sm table-striped table-hover">
										<tr>
											<th>S. No</th>
											<th>Amount</th>
											<th>Lab Name</th>										
											<th>Requested on</th>
											<th>Requested Comment</th>
											
											<th> View </th>
										</tr>
								<?php
										$i = 1;
										while ($data = $requested_result->fetch_assoc()){
										echo "<tr>
											<td>".$i++."</td>
											<td>".$data["amount"]."</td>
											<td>".$data["lab_name"]."</td>
											<td>".$data["requested_time"]."</td>
											<td>".$data["requested_comment"]."</td><td>";
								?>
											
								<?php
											echo "</td><td><a href='./view_due.php?due_id=".$data["dueID"]."'>View</a>";
											echo "</tr>";
										}	
										echo "</table>";
									}
									else{
										echo "<strong>No records found</strong>";
									}
								?>
							</div>
							
							<div id="approved" class="tab-pane fade">
								<?php
									$student_approved = "student_approved".$entry_number;
									$approved_dues_sql1 = "CREATE OR REPLACE VIEW $student_approved AS
																SELECT dueID, amount, description,
																	generated_time, employee_uID, lab_code, modified_time
																	FROM dues
																		WHERE status = 'A'
																		AND entry_number = '2014CS10258';";

									$approve_labDues = "approve_labDues".$entry_number;
									$approved_dues_sql2 = "CREATE OR REPLACE VIEW $approve_labDues AS
																SELECT DISTINCT lab_code, employee_uID
																	FROM $student_approved;";

									$approve_labHeader = "approve_labHeader".$entry_number;
									$approved_dues_sql3 = "CREATE OR REPLACE VIEW $approve_labHeader AS
																SELECT DISTINCT lab_info.lab_code, department_code, address
																	FROM lab_info, $approve_labDues
																		WHERE lab_info.lab_code = $approve_labDues.lab_code;";

									$approve_lab_details = "approve_lab_details".$entry_number;
									$approved_dues_sql4 = "CREATE OR REPLACE VIEW $approve_lab_details AS
																SELECT a1.full_form AS lab_name, 
																a2.full_form AS department_name,
																lab_code, address
																	FROM accronym AS a1, accronym AS a2, $approve_labHeader
																		WHERE a1.code = lab_code
																		AND a2.code = department_code;";

									$approve_dueIDs = "approve_dueIDs".$entry_number;
									$approved_dues_sql6 = "CREATE OR REPLACE VIEW $approve_dueIDs AS
																SELECT dueID
																	FROM $student_approved;";

									$approve_details = "approve_details".$entry_number;
									$approved_dues_sql7 = "CREATE OR REPLACE VIEW $approve_details AS
																SELECT duesra.dueID, approved_time, approved_comment
																	FROM duesra, $approve_dueIDs
																		WHERE $approve_dueIDs.dueID = duesra.dueID;";


									$approved_dues_sql = "SELECT description, $approve_details.dueID,
																approved_time, approved_comment , amount , lab_name
																FROM $approve_lab_details, $approve_details, $student_approved
																	WHERE $student_approved.lab_code = $approve_lab_details.lab_code
																	AND $approve_details.dueID = $student_approved.dueID ORDER BY generated_time DESC;";

									$approved_drop_sql = "DROP VIEW $student_approved, $approve_labDues, $approve_labHeader, $approve_lab_details, $approve_dueIDs, $approve_details;";
									
									$approved_result1 = $con->query($approved_dues_sql1);
									$approved_result2 = $con->query($approved_dues_sql2);
									$approved_result3 = $con->query($approved_dues_sql3);
									$approved_result4 = $con->query($approved_dues_sql4);
									$approved_result6 = $con->query($approved_dues_sql6); 
									$approved_result7 = $con->query($approved_dues_sql7);
									$approved_result = $con->query($approved_dues_sql);
									$approved_drop_result = $con->query($approved_drop_sql);

									$approved_rows = mysqli_num_rows($approved_result);

									if($approved_rows>0){									
								?>
									<table class="table table-sm table-striped table-hover">
										<tr>
											<th>S. No</th>
											<th>Amount</th>
											<th>Lab Name</th>
											<th>Approved on</th>
											<th>Approved Comment</th>
											<th> View </th>
										</tr>
								<?php
										$i = 1;
										while ($data = $approved_result->fetch_assoc()){
										echo "<tr>
											<td>".$i++."</td>
												<td>".$data["amount"]."</td>
												<td>".$data["lab_name"]."</td>
												<td>".$data["approved_time"]."</td>
												<td>".$data["approved_comment"]."</td>";
											echo "<td><a href='./view_due.php?due_id=".$data["dueID"]."'>View</a>";
											echo "</td></tr>";
										}	
										echo "</table>";
									}
									else{
										echo "<strong>No records found</strong>";
									}
								?>
							</div>
						</div>
					</li>
				</ul>
				<hr>
			</div>
		</div>		
		<script src="script/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
	</body>
</html>
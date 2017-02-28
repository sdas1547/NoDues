<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Header</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="./mystyle.css" rel="stylesheet">
		
		
	</head>
	<?php
		session_start();
		include ("./header.php");
		include("./dbinfo.inc");
		if(!isset($_SESSION["emp_no"])){
			echo "Please Login to continue";
			die;
		}
		else{
			$emp_no = $_SESSION["emp_no"];
		}
		
		if(!isset($_GET["id"])){
			echo "Lab not found";
			die;
			
		}
		else{
			$id = $_GET["id"] ;
			if(isset($_SESSION["department_code".$id]) && isset($_SESSION["department_code".$id]) && isset($_SESSION["lab_code".$id]) && isset($_SESSION["lab_name".$id])){

				$lab_name = $_SESSION["lab_name".$id];
				$lab_code = $_SESSION["lab_code".$id];
				$department_code = $_SESSION["department_code".$id];
				$department_name = $_SESSION["department_name".$id];
			}
			else{
				echo "Data not found".
				die;
			}
		}		
	?>
	
	<body>
		<div class="container">
			<div class="row">			
				<ul class="col-sm-offset-0">
					<li><a href="#">Frequently Asked Questions (FAQs) </a></li>
					<li><a href="#">User Manual- Lab Instructor</a></li>
					<li><a href="./new_record.php?lab_id=<?php echo $id;?>"><strong>Add a new record</strong></a></li>
					<hr>
				</ul>
				<ul>
					<li><strong><?php echo $lab_name;?></strong>
						<br><br>
						<div class="row">
							<div class="col-sm-5 col-md-4 col-xs-4"><input class='form-control' name="key" type="text" placeholder="Search here.." id="filter"></div><br>
						</div>
						<br>
						<ul class="nav nav-tabs">
							<li class="active" role="presentation">
								<a data-toggle="tab" href="#clearence">Clearence Request</a>
							</li>
							<li role="presentation">
								<a data-toggle="tab" href="#pending">Pending Records</a>
							</li>
							<li role="presentation">
								<a data-toggle="tab" href="#cancelled">Cancelled Records</a>
							</li>
							<li role="presentation">
								<a data-toggle="tab" href="#approved">Approved Records</a>
							</li>
						</ul>
						<br>
						<div class="tab-content">
							<div id="pending" class="tab-pane fade">
								<?php
									$dues_details = "dues_details".$emp_no;
									$pending_sql1 = "CREATE OR REPLACE VIEW $dues_details AS
														SELECT dueID,amount,description, 
															generated_time, modified_time, status, entry_number
															FROM dues
																WHERE employee_uID = '$emp_no'
																AND lab_code = 'col01';";

									$pending_sql = "SELECT dueID, entry_number,amount,
														generated_time, description
														FROM $dues_details
															WHERE status = 'P';";
									$pending_drop_sql = "DROP VIEW $dues_details;";

									$pending_result1 = $con->query($pending_sql1);
									$pending_result = $con->query($pending_sql);
									$con->query($pending_drop_sql);
									$pending_rows = mysqli_num_rows($pending_result);
									if($pending_rows>0){									
								?>
									<table class="table table-responsive table-striped table-hover" style="font-size:15">
										<tr id="heading">
											<th>S. No</th>
											<th>Entry Number</th>
											<th>Added on</th>											
											<th>Amount (in Rs.)</th>
											<th>Description</th>
											<th>Edit/ Delete</th>
											<th>View</th>
										</tr>
										<?php
											
											$i=1;
											while($pending_data = $pending_result->fetch_assoc()){
												echo "<tr class=data>
												<td>".$i++."</td>
												<td>".$pending_data["entry_number"]."</td>
												<td>".$pending_data["generated_time"]."</td>												
												<td>".$pending_data["amount"]."</td>
												<td>".$pending_data["description"]."</td>";												
										?>
												<td><a href="./edit_record.php?due_id=<?php echo $pending_data["dueID"];?>&lab_id=<?php echo $id;?>">Edit</a>/ <a href="./delete_record.php?due_id=<?php echo $pending_data["dueID"];?>&lab_id=<?php echo $id;?>">Delete</a></td>
										<?php
												echo "<td><a href='./view_due.php?due_id=".$pending_data["dueID"]."'>View</a>  </td></tr>";												
												
											}
										?>
									</table>
								<?php 
									}
									else{
										echo "<strong>No records found.</strong>";
									}
								?>
							</div>
							<div id="clearence" class="tab-pane fade in active">
								<?php

									$dues_details = "dues_details".$emp_no;
									$pending_sql1 = "CREATE OR REPLACE VIEW $dues_details AS
														SELECT dueID,amount,description, 
															generated_time, modified_time, status, entry_number
															FROM dues
																WHERE employee_uID = '$emp_no'
																AND lab_code = 'col01';";

									$duesr_details = "duesr_details".$emp_no;
									$pending_sql2 = "CREATE OR REPLACE VIEW $duesr_details AS
														SELECT $dues_details.dueID, requested_time,
															requested_comment, entry_number, amount
															FROM $dues_details , duesra
																WHERE $dues_details.dueID = duesra.dueID
																AND status = 'R';";
									$pending_sql = "SELECT * 
														FROM $duesr_details;";

									$duesr_drop_sql = "DROP VIEW $dues_details, $duesr_details;";

									$pending_result1 = $con->query($pending_sql1);
									$pending_result1 = $con->query($pending_sql2);
									$pending_result = $con->query($pending_sql);
									$con->query($duesr_drop_sql);

									$clearence_rows = mysqli_num_rows($pending_result);
									if($clearence_rows>0){
								?>
									<table class="table table-responsive table-striped table-hover" style="font-size:15">
										<tr>
											<th>S. No</th>
											<th>Entry Number</th>
											<th>Amount (in Rs.)</th>
											<th>Requested Time</th>
											<th>Student Response</th>
											<th>Edit/Delete</th>
											<th>View</th>
										</tr>
										<?php
											
											$i=1;
											$lab_total=0;
											while($pending_data = $pending_result->fetch_assoc()){
												echo "<tr class=data>
												<td>".$i++."</td>
												<td>".$pending_data["entry_number"]."</td>
												<td>".$pending_data["amount"]."</td>												
												<td>".$pending_data["requested_time"]."</td>
												<td>".$pending_data["requested_comment"]."</td>";
										?>
												<td><a href="./approve_record.php?due_id=<?php echo $pending_data["dueID"];?>&lab_id=<?php echo $id;?>">Approve</a></td>
										<?php
												echo "<td><a href='./view_due.php?due_id=".$pending_data["dueID"]."'>View</a></td>";
												echo "</tr>";												
												$lab_total=$lab_total+$pending_data["amount"];
											}
										?>
									</table>
								<?php 
									}
									else{
										echo "<strong>No records found.</strong>";
									}
								?>								
							</div>
							<div id="cancelled" class="tab-pane fade">
								<?php

									$dues_details = "dues_details".$emp_no;
									$pending_sql1 = "CREATE OR REPLACE VIEW $dues_details AS
														SELECT dueID,amount,description, 
															generated_time, modified_time, status, entry_number
															FROM dues
																WHERE employee_uID = '$emp_no'
																AND lab_code = 'col01';";

									$pending_sql = "SELECT dueID, entry_number,amount, generated_time,
														modified_time, description
														FROM ".$dues_details."
															WHERE status ='C';";
									$cancelled_drop_sql = "DROP VIEW $dues_details;";

									$con->query($pending_sql1);
									$pending_result = $con->query($pending_sql);
									$con->query($cancelled_drop_sql);
									$clearence_rows = mysqli_num_rows($pending_result);
									if($clearence_rows>0){
								?>
									<table class="table table-responsive table-striped table-hover" style="font-size:15">
										<tr>
											<th>S. No</th>
											<th>Entry Number</th>																						
											<th>Amount(in Rs.)</th>
											<th>Description</th>
											<th>Added on</th>
											<th>Cancelled on</th>											
											<th> View</th>
										</tr>
										<?php											
											$i=1;
											while($pending_data = $pending_result->fetch_assoc()){
												echo "<tr class=data>
												<td>".$i++."</td>
												<td>".$pending_data["entry_number"]."</td>
												<td>".$pending_data["generated_time"]."</td>												
												<td>".$pending_data["amount"]."</td>
												<td>".$pending_data["description"]."</td>
												<td>".$pending_data["modified_time"]."</td>";
												echo "<td><a href='./view_due.php?due_id=".$pending_data["dueID"]."'>View</a></td>";
												echo "</tr>";											
											}
										?>
									</table>
								<?php 
									}
									else{
										echo "<strong>No records found.</strong>";
									}
								?>								
							</div>
							<div id="approved" class="tab-pane fade">
								<?php

									$dues_details = "dues_details".$emp_no;
									$pending_sql1 = "CREATE OR REPLACE VIEW $dues_details AS
														SELECT dueID,amount,description, 
															generated_time, modified_time, status, entry_number
															FROM dues
																WHERE employee_uID = '$emp_no'
																AND lab_code = 'col01';";

									$duesa_details = "duesa_details".$emp_no;
									$pending_sql2 = "CREATE OR REPLACE VIEW $duesa_details AS
														SELECT $dues_details.dueID, approved_time,
															approved_comment , entry_number, amount	
															FROM $dues_details, duesra
																WHERE $dues_details.dueID = duesra.dueID
																AND status = 'A';";
									$pending_sql = "SELECT * 
														FROM $duesa_details;";

									$approved_drop_query = "DROP VIEW $dues_details, $duesa_details;";

									$con->query($pending_sql1);
									$pending_result2 = $con->query($pending_sql2);
									$pending_result = $con->query($pending_sql);
									$con->query($approved_drop_query);
									$approved_rows = mysqli_num_rows($pending_result);
									if($approved_rows>0){
								?>
									<table class="table table-responsive table-striped table-hover" style="font-size:15">
										<tr>
											<th>S. No</th>
											<th>Entry Number</th>
											<th>Amount(in Rs.)</th>
											<th>Approved on</th>										
											<th>Approved comment</th>
											<th> View</th>
										</tr>
										<?php
											$i=1;
											$lab_total=0;
											while($pending_data = $pending_result->fetch_assoc()){
												echo "<tr class=data>
												<td>".$i++."</td>
												<td>".$pending_data["entry_number"]."</td>
												<td>".$pending_data["amount"]."</td>
												<td>".$pending_data["approved_time"]."</td>
												<td>".$pending_data["approved_comment"]."</td>";
												echo "<td><a href='./view_due.php?due_id=".$pending_data["dueID"]."'>View</a></td>
												<tr>";
											}
										?>
									</table>
								<?php 
									}
									else{
										echo "<strong>No records found.</strong>";
									}
								?>
							</div>
						</div>								
						<hr>
					</li>										
				</ul>
			</div>
		</div>	
		
		<script src="script/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>

		<script>
			var $rows = $('.table .data');
			$('#filter').keyup(function() {
				var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();
				$rows.show().filter(function() {
					var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
					//alert(text);
					return !~text.indexOf(val);
				}).hide();
			});
		</script>
	</body>
	</body>
</html>
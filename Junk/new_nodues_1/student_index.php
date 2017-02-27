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
		if (!isset($_SESSION["login_id"])){
			header ("Location: ../index.php");
			die;
		}
		else{
			$entry_number = $_SESSION["entry_no"];
		}
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
					<li> <strong>My Dues</strong>
						<br><br>
						<ul class="nav nav-tabs">
							<li class="active" role="presentation">
								<a data-toggle="tab" href="#pending">Pending Records</a>
							</li>
							<li role="presentation">
								<a data-toggle="tab" href="#cancelled">Cancelled Records</a>
							</li>
							<li role="presentation">
								<a data-toggle="tab" href="#approved">Approved Records</a>
							</li>
							<li role="presentation">
								<a data-toggle="tab" href="#all">All Records</a>
							</li>
						</ul>
						<br>
						<div class="tab-content">
							<div id="pending" class="tab-pane fade in active">
								<?php
									$pending_dues_sql = "SELECT * FROM dues WHERE entry_number='$entry_number' AND (current_status='N' OR current_status='E') ORDER BY	created_time desc";
									$pending_result = $con->query($pending_dues_sql); 
									$pending_rows = mysqli_num_rows($pending_result);
									if($pending_rows>0){
								?>
									<table class="table table-sm table-striped table-hover">
										<tr>
										<th>S. No</th>
										<th>Department</th>
										<th>Lab</th>
										<th>Description</th>
										<th>Added on</th>
										<th>Amount</th>
										<th>Status</th>
										<th>Request Status</th>
										</tr>
								<?php
										$i = 1;
										while ($data = $pending_result->fetch_assoc()){
										echo "<tr>
											<td>".$i++."</td>
											<td>".$data["department"]."</td>
											<td>".$data["lab_id"]."</td>
											<td>".$data["description"]."</td>
											<td>".$data["created_time"]."</td>
											<td>".$data["amount"]."</td>
											<td>";												
												if($data["current_status"]=="N"){
													echo "Pending";
												}
												else{
													echo "Approval Required";
												}
											echo "</td>
											<td>";
												if($data["current_status"]=="N"){
													?>
													<a href="./make_request.php?due_id=<?php echo $data["due_id"];?>">
														Request
													</a>
													<?php
												}
												else{
													echo "Requested";
													?>
													/ <a href="./make_request.php?due_id=<?php echo $data["due_id"];?>">Edit Request
													</a>
													<?php
												}
											echo "</td></tr>";
											//$curr_lab_total=$curr_lab_total+$data["amount"];
											//$curr_dept_total=$curr_dept_total+$data["amount"];
										}	
										echo "</table>";
									}
									else{
										echo "<strong>No records found</strong>";
									}
								?>
							</div>
							
							<div id="cancelled" class="tab-pane fade">
								<?php
									$cancelled_dues_sql = "SELECT * FROM dues WHERE entry_number='$entry_number' AND current_status='C' ORDER BY	created_time desc";
									$cancelled_result = $con->query($cancelled_dues_sql); 
									$cancelled_rows = mysqli_num_rows($cancelled_result);
									if($cancelled_rows>0){
								?>
									<table class="table table-sm table-striped table-hover">
										<tr>
										<th>S. No</th>
										<th>Department</th>
										<th>Lab</th>
										<th>Description</th>
										<th>Added on</th>
										<th>Amount</th>
										<th>Status</th>
										<th>Cancelled On</th>
										</tr>
								<?php
										$i = 1;
										while ($data = $cancelled_result->fetch_assoc()){
										echo "<tr>
											<td>".$i++."</td>
											<td>".$data["department"]."</td>
											<td>".$data["lab_id"]."</td>
											<td>".$data["description"]."</td>
											<td>".$data["created_time"]."</td>
											<td>".$data["amount"]."</td>
											<td>Cancelled</td>
											<td>Not Allowed</td></tr>";
											//$curr_lab_total=$curr_lab_total+$data["amount"];
											//$curr_dept_total=$curr_dept_total+$data["amount"];
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
									$approved_dues_sql = "SELECT * FROM dues WHERE entry_number='$entry_number' AND current_status='Y' ORDER BY	created_time desc";
									$approved_result = $con->query($approved_dues_sql); 
									$approved_rows = mysqli_num_rows($approved_result);
									if($approved_rows>0){
								?>
									<table class="table table-sm table-striped table-hover">
										<tr>
										<th>S. No</th>
										<th>Department</th>
										<th>Lab</th>
										<th>Description</th>
										<th>Added on</th>
										<th>Amount</th>
										<th>Status</th>
										<th>Approved On</th>
										</tr>
								<?php
										$i = 1;
										while ($data = $approved_result->fetch_assoc()){
										echo "<tr>
											<td>".$i++."</td>
											<td>".$data["department"]."</td>
											<td>".$data["lab_id"]."</td>
											<td>".$data["description"]."</td>
											<td>".$data["created_time"]."</td>
											<td>".$data["amount"]."</td>
											<td>Approved</td>
											<td>Not Allowed</td></tr>";
											//$curr_lab_total=$curr_lab_total+$data["amount"];
											//$curr_dept_total=$curr_dept_total+$data["amount"];
										}	
										echo "</table>";
									}
									else{
										echo "<strong>No records found</strong>";
									}
								?>
							</div>
							
							<div id="all" class="tab-pane fade">
								<?php
									$all_dues_sql = "SELECT * FROM dues WHERE entry_number='$entry_number' ORDER BY	created_time desc";
									$all_result = $con->query($all_dues_sql); 
									$all_rows = mysqli_num_rows($all_result);
									if($all_rows>0){
								?>
									<table class="table table-sm table-striped table-hover">
										<tr>
										<th>S. No</th>
										<th>Department</th>
										<th>Lab</th>
										<th>Description</th>
										<th>Added on</th>
										<th>Amount</th>
										<th>Status</th>
										</tr>
								<?php
										$i = 1;
										while ($data = $all_result->fetch_assoc()){
										echo "<tr>
											<td>".$i++."</td>
											<td>".$data["department"]."</td>
											<td>".$data["lab_id"]."</td>
											<td>".$data["description"]."</td>
											<td>".$data["created_time"]."</td>
											<td>".$data["amount"]."</td>
											<td>";											
											if($data["current_status"]=="N"){
												echo "Pending";
											}
											else if($data["current_status"]=="E"){
												echo "Requested";
											}
											else if($data["current_status"]=="C"){
												echo "Cancelled";
											}
											else{
												echo "Approved";
											}
											//$curr_lab_total=$curr_lab_total+$data["amount"];
											//$curr_dept_total=$curr_dept_total+$data["amount"];
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
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
	</body>
</html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Lab</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="./mystyle.css" rel="stylesheet">
	</head>
	<?php
		session_start();
		include("./header.php");
		include("./dbinfo.inc");
		$lab_name = $_GET["lab_name"];
		$lab_id = $_GET["lab_id"];
		$department = $_GET["department"];
		$emp_id = $_SESSION["emp_no"];
	?>
	
	<body>
		<div class="container">
			<div class="row">
			
				<ul class="col-sm-offset-0">
					<li><a href="#">Frequently Asked Questions (FAQs) </a></li>
					<li><a href="#">User Manual- Lab Instructor</a></li>
					<li><a href="./new_record.php?lab_id=<?php echo $lab_id;?>&department=<?php echo $department;?>"><strong>Add a new record</strong></a></li>
					<hr>
					<li><strong><?php echo $lab_name;?></strong>
						<br><br>
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
							<li role="presentation">
								<a data-toggle="tab" href="#all">All Records</a>
							</li>
						</ul>
						<br>
						<div class="tab-content">
							<div id="all" class="tab-pane fade">
								<?php
									$pending_sql = "SELECT * FROM dues WHERE created_by='$emp_id' AND lab_id='$lab_id' ORDER BY created_time DESC";
									$pending_result = $con->query($pending_sql);
									$pending_rows = mysqli_num_rows($pending_result);
									if($pending_rows>0){
								?>
									<table class="table table-responsive table-striped table-hover" style="font-size:15">
										<tr>
											<th>S. No</th>
											<th>Entry Number</th>
											<th>Added on</th>
											<th>Description</th>
											<th>Amount</th>
											<th>Status</th>
											<th>Edit/Delete</th>
										</tr>
									<?php
										
										$i=1;
										$lab_total=0;
										while($pending_data = $pending_result->fetch_assoc()){
											echo "<tr>
											<td>".$i++."</td>
											<td>".$pending_data["entry_number"]."</td>
											<td>".$pending_data["created_time"]."</td>
											<td>".$pending_data["description"]."</td>
											<td>".$pending_data["amount"]."</td>";
											if($pending_data["current_status"]=="N"){
												echo "<td>Pending</td>";
											}
											else if($pending_data["current_status"]=="E"){
												echo "<td>Approval Required</td>";
											}
											else if($pending_data["current_status"]=="C"){
												echo "<td>Cancelled</td>";
											}
											else{
												echo "<td>Approved</td>";
											}
											
											if($pending_data["current_status"]=="E"){
												?>
													<td><a href="./approve_record.php?due_id=<?php echo $pending_data["due_id"];?>">Approve</a></td>
												<?php
											}
											else if($pending_data["current_status"]=="Y" || $pending_data["current_status"]=="C" ){
												echo "<td>Not Allowed</td>";
											}
											else if($pending_data["current_status"]=="N"){
												?>
													<td><a href="./edit_record.php?due_id=<?php echo $pending_data["due_id"];?>">Edit</a><br><a href="./delete_record.php?due_id=<?php echo $pending_data["due_id"];?>">Delete</a></td>
												<?php
											}
											else{}
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
							<div id="pending" class="tab-pane fade">
								<?php
									$pending_sql = "SELECT * FROM dues WHERE created_by='$emp_id' AND lab_id='$lab_id' AND (current_status='N' OR current_status='E') ORDER BY created_time DESC";
									$pending_result = $con->query($pending_sql);
									$pending_rows = mysqli_num_rows($pending_result);
									if($pending_rows>0){									
								?>
									<table class="table table-responsive table-striped table-hover" style="font-size:15">
										<tr>
											<th>S. No</th>
											<th>Entry Number</th>
											<th>Added on</th>
											<th>Description</th>
											<th>Amount</th>
											<th>Status</th>
											<th>Edit/Delete</th>
										</tr>
										<?php
											
											$i=1;
											$lab_total=0;
											while($pending_data = $pending_result->fetch_assoc()){
												echo "<tr>
												<td>".$i++."</td>
												<td>".$pending_data["entry_number"]."</td>
												<td>".$pending_data["created_time"]."</td>
												<td>".$pending_data["description"]."</td>
												<td>".$pending_data["amount"]."</td>";
												if($pending_data["current_status"]=="N"){
													echo "<td>Pending</td>";
												}
												else{
													echo "<td>Approval Required</td>";
												}
												
												if($pending_data["current_status"]=="E"){
													?>
														<td><a href="#">Approve</a></td>
													<?php
												}
												else if($pending_data["current_status"]=="Y" || $pending_data["current_status"]=="C" ){
													echo "<td>Not Allowed</td>";
												}
												else if($pending_data["current_status"]=="N"){
													?>
														<td><a href="./edit_record.php?due_id=<?php echo $pending_data["due_id"];?>">Edit</a><br><a href="#">Delete</a></td>
													<?php
												}
												else{}
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
							<div id="clearence" class="tab-pane fade in active">
								<?php
									$pending_sql = "SELECT * FROM dues WHERE created_by='$emp_id' AND lab_id='$lab_id' AND current_status='E' ORDER BY created_time DESC";
									$pending_result = $con->query($pending_sql);
									$clearence_rows = mysqli_num_rows($pending_result);
									if($clearence_rows>0){
								?>
									<table class="table table-responsive table-striped table-hover" style="font-size:15">
										<tr>
											<th>S. No</th>
											<th>Entry Number</th>
											<th>Added on</th>
											<th>Description</th>
											<th>Amount</th>
											<th>Status</th>
											<th>Edit/Delete</th>
										</tr>
										<?php
											
											$i=1;
											$lab_total=0;
											while($pending_data = $pending_result->fetch_assoc()){
												echo "<tr>
												<td>".$i++."</td>
												<td>".$pending_data["entry_number"]."</td>
												<td>".$pending_data["created_time"]."</td>
												<td>".$pending_data["description"]."</td>
												<td>".$pending_data["amount"]."</td>
												<td>Approval Required</td>";
												
												if($pending_data["current_status"]=="E"){
													?>
														<td><a href="#">Approve</a></td>
													<?php
												}
												else if($pending_data["current_status"]=="Y" || $pending_data["current_status"]=="C" ){
													echo "<td>Not Allowed</td>";
												}
												else if($pending_data["current_status"]=="N"){
													?>
														<td><a href="./edit_record.php?due_id=<?php echo $pending_data["due_id"];?>">Edit</a><br><a href="#">Delete</a></td>
													<?php
												}
												else{}
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
									$pending_sql = "SELECT * FROM dues WHERE created_by='$emp_id' AND lab_id='$lab_id' AND current_status='C' ORDER BY created_time DESC";
									$pending_result = $con->query($pending_sql);
									$clearence_rows = mysqli_num_rows($pending_result);
									if($clearence_rows>0){
								?>
									<table class="table table-responsive table-striped table-hover" style="font-size:15">
										<tr>
											<th>S. No</th>
											<th>Entry Number</th>
											<th>Added on</th>
											<th>Description</th>
											<th>Amount</th>
											<th>Status</th>
											<th>Edit/Delete</th>
										</tr>
										<?php
											
											$i=1;
											$lab_total=0;
											while($pending_data = $pending_result->fetch_assoc()){
												echo "<tr>
												<td>".$i++."</td>
												<td>".$pending_data["entry_number"]."</td>
												<td>".$pending_data["created_time"]."</td>
												<td>".$pending_data["description"]."</td>
												<td>".$pending_data["amount"]."</td>
												<td>Cancelled</td>";
												
												if($pending_data["current_status"]=="E"){
													?>
														<td><a href="#">Approve</a></td>
													<?php
												}
												else if($pending_data["current_status"]=="Y" || $pending_data["current_status"]=="C" ){
													echo "<td>Not Allowed</td>";
												}
												else if($pending_data["current_status"]=="N"){
													?>
														<td><a href="./edit_record.php?due_id=<?php echo $pending_data["due_id"];?>">Edit</a><br><a href="#">Delete</a></td>
													<?php
												}
												else{}
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
							<div id="approved" class="tab-pane fade">
								<?php
									$pending_sql = "SELECT * FROM dues WHERE created_by='$emp_id' AND lab_id='$lab_id' AND current_status='Y' ORDER BY created_time DESC";
									$pending_result = $con->query($pending_sql);
									$approved_rows = mysqli_num_rows($pending_result);
									if($approved_rows>0){
								?>
									<table class="table table-responsive table-striped table-hover" style="font-size:15">
										<tr>
											<th>S. No</th>
											<th>Entry Number</th>
											<th>Added on</th>
											<th>Description</th>
											<th>Amount</th>
											<th>Status</th>
											<th>Edit/Delete</th>
										</tr>
										<?php
											$i=1;
											$lab_total=0;
											while($pending_data = $pending_result->fetch_assoc()){
												echo "<tr>
												<td>".$i++."</td>
												<td>".$pending_data["entry_number"]."</td>
												<td>".$pending_data["created_time"]."</td>
												<td>".$pending_data["description"]."</td>
												<td>".$pending_data["amount"]."</td>
												<td>Approved</td>";
												
												if($pending_data["current_status"]=="E"){
													?>
														<td><a href="#">Approve</a></td>
													<?php
												}
												else if($pending_data["current_status"]=="Y" || $pending_data["current_status"]=="C" ){
													echo "<td>Not Allowed</td>";
												}
												else if($pending_data["current_status"]=="N"){
													?>
														<td><a href="./edit_record.php?due_id=<?php echo $pending_data["due_id"];?>">Edit</a><br><a href="#">Delete</a></td>
													<?php
												}
												else{}
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
						</div>								
						<hr>
					</li>										
				</ul>
			</div>
		</div>	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
	</body>
</html>
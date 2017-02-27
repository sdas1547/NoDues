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
		$emp_id = $_GET["emp_id"];
	?>
	
	<body>
		<div class="container">
			<div class="row">
			
				<ul class="col-sm-offset-0">
					<li><a href="#">Frequently Asked Questions (FAQs) </a></li>
					<li><a href="#">User Manual- Lab Instructor</a></li>
					<li><strong><?php echo $lab_name;?></strong></li><hr>
						<div class="panel-group">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h5 class="panel-title">
										<a data-toggle="collapse" href="#collapse1">
										<strong>Pending Dues</strong>
										</a>
									</h5>							
								</div>
								<div id="collapse1" class="panel-collapse collapse">
									<div class="panel-body">
										<table class="table">
												<tr>
													<th>S. No</th>
													<th>Entry Number</th>
													<th>Added on</th>
													<th>Description</th>
													<th>Amount</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											<?php
												$pending_sql = "SELECT * FROM dues WHERE created_by='$emp_id' AND lab_id='$lab_id' AND (current_status='N' OR current_status='E') ORDER BY created_time DESC";
												$pending_result = $con->query($pending_sql);
												$i=1;
												$lab_total=0;
												while($pending_data = $pending_result->fetch_assoc()){
													echo "<tr>
													<td>".$i++."</td>
													<td>".$pending_data["entry_number"]."</td>
													<td>".$pending_data["created_time"]."</td>
													<td>".$pending_data["description"]."</td>
													<td>".$pending_data["amount"]."</td>
													<td>".$pending_data["current_status"]."</td>
													<td>";
														if($pending_data["current_status"]=="N"){
															?><button class="btn btn-danger"><?php
																	$request_sql = "UPDATE dues SET current_status = 'Y' WHERE due_id =\"".$pending_data["due_id"]."\"";
																	$request_result = $con->query($request_sql);
																	die;
																	
																?>Cancel</button><?php									  
														}
														if($pending_data["current_status"]=="E"){
															?>
															<button class="btn btn-danger"><?php
																	$request_sql = "UPDATE dues SET current_status = 'Y' WHERE due_id =\"".$pending_data["due_id"]."\"";
																	$request_result = $con->query($request_sql);
																	
																?>Cancel</button>
															<button class="btn btn-primary"><?php
																	$request_sql = "UPDATE dues SET current_status = 'Y' WHERE due_id =\"".$pending_data["due_id"]."\"";
																	$request_result = $con->query($request_sql);
																	
																?>Approve</button><?php
													    }
													echo "</td>
													</tr>";
													$lab_total=$lab_total+$pending_data["amount"];
												}
											?>
										</table>
									</div>
								</div>
								<div class="panel-footer">
									<div class="panel-title pull-right">
									<strong>Total: </strong>Rs. <?php  
										echo $lab_total;?>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
						<div class="panel-group">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h5 class="panel-title">
										<a data-toggle="collapse" href="#collapse2">
										<strong>Cleared Dues</strong>
										</a>
									</h5>							
								</div>
								<div id="collapse2" class="panel-collapse collapse">
									<div class="panel-body">
										<table class="table">
												<tr>
													<th>S. No</th>
													<th>Entry Number</th>
													<th>Added on</th>
													<th>Description</th>
													<th>Amount</th>
													<th>Status</th>
												</tr>
											<?php
												$pending_sql = "SELECT * FROM dues WHERE created_by='$emp_id' AND lab_id='$lab_id' AND (current_status='C' OR current_status='Y') ORDER BY created_time DESC";
												$pending_result = $con->query($pending_sql);
												$i=1;
												$lab_total=0;
												while($pending_data = $pending_result->fetch_assoc()){
													echo "<tr>
													<td>".$i++."</td>
													<td>".$pending_data["entry_number"]."</td>
													<td>".$pending_data["created_time"]."</td>
													<td>".$pending_data["description"]."</td>
													<td>".$pending_data["amount"]."</td>
													<td>".$pending_data["current_status"]."</td>
													</tr>";
													$lab_total=$lab_total+$pending_data["amount"];
												}
											?>
										</table>
									</div>
								</div>
								<div class="panel-footer">
									<div class="panel-title pull-right">
									<strong>Total: </strong>Rs. <?php  
										echo $lab_total;?>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
						<div class="panel-group">
							<div class="panel panel-primary">
								<div class="panel-heading">
									<h5 class="panel-title">
										<a data-toggle="collapse" href="#collapse3">
										<strong>All Dues</strong>
										</a>
									</h5>							
								</div>
								<div id="collapse3" class="panel-collapse collapse">
									<div class="panel-body">
										<table class="table">
												<tr>
													<th>S. No</th>
													<th>Entry Number</th>
													<th>Added on</th>
													<th>Description</th>
													<th>Amount</th>
													<th>Status</th>
												</tr>
											<?php
												$pending_sql = "SELECT * FROM dues WHERE created_by='$emp_id' AND lab_id='$lab_id' ORDER BY created_time DESC";
												$pending_result = $con->query($pending_sql);
												$i=1;
												$lab_total=0;
												while($pending_data = $pending_result->fetch_assoc()){
													echo "<tr>
													<td>".$i++."</td>
													<td>".$pending_data["entry_number"]."</td>
													<td>".$pending_data["created_time"]."</td>
													<td>".$pending_data["description"]."</td>
													<td>".$pending_data["amount"]."</td>
													<td>".$pending_data["current_status"]."</td>
													</tr>";
													$lab_total=$lab_total+$pending_data["amount"];
												}
											?>
										</table>
									</div>
								</div>
								<div class="panel-footer">
									<div class="panel-title pull-right">
									<strong>Total: </strong>Rs. <?php  
										echo $lab_total;?>
									</div>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					<hr>	
					<li><a href="./new_record.php?lab_id=<?php echo $lab_id;?>&department=<?php echo $department;?>&emp_id=<?php echo $emp_id;?>"><strong>Add a new record</strong></li>				
					
				</ul>
			</div>
		</div>	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
	</body>
</html>
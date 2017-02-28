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
		include ("./header.php");	
	?>	

	<body>

		<div class="container">
			<div class="row">
			<ul>
				<li><a href="#">Frequently Asked Questions (FAQs) </a></li>
				<li><a href="#">User Manual- Lab Instructor</a></li>
				<li> <strong>My Dues</strong></li><br>
					<?php
						session_start();
						include ("./dbinfo.inc");
						$entry_number = "2012EE50563";
						$dep_sql = "SELECT DISTINCT department FROM dues WHERE entry_number='$entry_number' AND (current_status='N' OR current_status='E') ORDER BY	created_time desc";
						$dep_result = $con->query($dep_sql);
						$j=1;
						while($dept_name = $dep_result->fetch_assoc()){
					?>
						<div class="panel-group">
							<div class="panel panel-primary">
								<div class="panel-heading">					
									<div class="btn-group pull-right">
										<a data-toggle="collapse" href="#collapse<?php echo $j;?>" class="btn btn-danger btn-xs">Dues Pending</a>
									</div>
									<h4 class="panel-title">
										<a data-toggle="collapse" href="#collapse<?php echo $j;?>">
											<?php $curr_dept =$dept_name["department"];
												  $curr_dept_total = 0;
											echo $curr_dept;?>
										</a>
									</h4>
								</div>
								<div id="collapse<?php echo $j;?>" class="panel-collapse collapse">
									<div class="panel-body">
									<?php
										$lab_sql = "SELECT DISTINCT lab_id FROM dues WHERE entry_number='$entry_number' AND department='$curr_dept' AND (current_status='N' OR current_status='E') ORDER BY created_time desc";
										$lab_result = $con->query($lab_sql);
										while($lab_id = $lab_result->fetch_assoc()){
									?>
										<div class="panel panel-info">
											<div class="panel-heading">
												<?php $curr_lab =$lab_id["lab_id"]; 
													$curr_lab_total = 0;
													echo $curr_lab;?>
											</div>
											
											<div class="panel-body">
												<table class="table table-sm table-striped table-bordered table-hover table-condensed">
													<tr>
													<th>S. No</th>
													<th>Description</th>
													<th>Added on</th>
													<th>Added_by</th>
													<th>Amount</th>
													<th>Status</th>
													<th>Request Status</th>
													</tr>
													
													<?php
														$dues_sql = "SELECT * FROM dues WHERE entry_number='$entry_number' AND department='$curr_dept' AND lab_id='$curr_lab'  AND (current_status='N' OR current_status='E') ORDER BY	created_time desc";
														$result = $con->query($dues_sql); 
														$i = 1;
														while ($data = $result->fetch_assoc()){
														echo "<tr>
															<td>".$i++."</td>
															<td>".$data["description"]."</td>
															<td>".$data["created_time"]."</td>
															<td>".$data["created_by"]."</td>
															<td>".$data["amount"]."</td>
															<td>".$data["current_status"]."</td>
															<td>";
																if($data["current_status"]=="N"){
																	?>
																	<a href="./make_request.php?lab_id=<?php echo $curr_lab;?>&dept_name=<?php echo $curr_dept;?>&due_id=<?php echo $data["due_id"];?>&description=<?php echo $data["description"];?>&created_by=<?php echo $data["created_by"];?>&created_time=<?php echo $data["created_time"];?>&amount=<?php echo $data["amount"];?>">
																		<button class="btn btn-primary">Make Request</button>
																	</a>
																	<?php
																}
																else{
																	?>
																	<button class="btn btn-info" readonly>Requested</button>
																	<?php
																}
															echo "</td> </tr>";
															$curr_lab_total=$curr_lab_total+$data["amount"];
															$curr_dept_total=$curr_dept_total+$data["amount"];
														}	
														echo "</table>";
													?>
											</div>
											
											<div class="panel-footer">
												<div class="panel-title pull-right">
												<strong>Total: </strong>Rs. <?php  
													echo $curr_lab_total;?>
												</div>
												<div class="clearfix"></div>
											</div>
											
										</div>
									<?php
										}
									?>
									</div>
								</div>
								
								<div class="panel-footer">
									<div class="panel-title pull-right">
									<strong>Total: </strong>Rs. <?php  
										echo $curr_dept_total;?>
									</div>
									<div class="clearfix"></div>
								</div>
											
							</div>
						</div>
					<?php
						$j++;
						}
					?>				
			</ul>
			</div>
			
		</div>			
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
	</body>
</html>
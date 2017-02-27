<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Home HOD | No Dues IIT Delhi</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="./mystyle.css" rel="stylesheet">
	</head>
	
	<?php
		session_start();
		include("./header.php");
		include("./dbinfo.inc");
		$_SESSION["admin_id"] = "Adm1";
		$admin_id = $_SESSION["admin_id"];
		$_SESSION["department"] = "Electrical";
		$lab_sql = "SELECT * FROM lab_info WHERE admin_id ='$admin_id'";
		$lab_result = $con->query($lab_sql);
	?>
	
	<body>
		<div class="container">
			<div class="row">
				<ul>
					<li><a href="#">Frequently Asked Questions (FAQs)</a></li>
					<li><a href="#">User Manual - Department Head</a></li>
				
					<li><a data-toggle="collapse" href="#likely_graduating"><strong>Likely Graduating Students</strong></a>
						<ul class="collapse in" id="likely_graduating">
							<li><a href="./graduating_student.php?programme=ug">Undergraduate Students</a></li>
							<li><a href="./graduating_student.php?programme=pg">Postgraduate Students</a></li>
							<li><a href="./graduating_student.php?programme=phd">Phd and MSR Studnets</a></li>
							<li><a href="./graduating_student.php?programme=all">All Students</a></li>
						</ul>
					</li>
				
					<li><a data-toggle="collapse" href="#pending_dues"><strong>Pending Dues</strong></a>
						<ul class="collapse in" id="pending_dues">
							<li><a href="./hod_pending.php?programme=ug">Undergraduate Students</a></li>
							<li><a href="./hod_pending.php?programme=pg">Postgraduate Students</a></li>
							<li><a href="./hod_pending.php?programme=phd">Phd and MSR Studnets</a></li>
							<li><a href="./hod_pending.php?programme=all">All Students</a></li>
						</ul>
					</li>
					<li><a href="#"><strong>Add a new Component</strong></a></li>
				</ul>
			

				<form action="./insertlist.php" method="POST">
					<div class="col-sm-12" style="margin-top:50px; margin-bottom:50px;">
						<label> Upload the list of graduating student</label>
				<input type=file accept=".csv" name="filename">
				<br>
				<button class="btn btn-primary" type="submit" name="submit"> Process</button>
			</div>
		</form>

				<ul>
					<li><strong>My Components</strong></li>
					<hr>
					<div class="row">
						<?php 
							$lab_no = 1;
							while($lab_data = $lab_result->fetch_assoc()){
								$_SESSION["department".$lab_no]=$lab_data["department"];
								$_SESSION["lab_id".$lab_no] = $lab_data["lab_id"];
								$_SESSION["lab_name".$lab_no] = $lab_data["lab_name"];
						?>
							<div class="col-sm-6 col-md-4">
								<form method="post" action="./lab_index2.php?id=<?php echo $lab_no;?>" enctype="multipart/form-data">
									<div class="panel panel-primary">
									<div class="panel-heading"><strong><?php echo $lab_data["lab_name"];?></strong></div>
										<div class="panel-body">
											<strong>Labortory Id:</strong> <?php echo $lab_data["lab_id"]; ?>
											<address>
												<strong>Address: </strong><?php echo $lab_data["address"];?> <br>
												<strong>Phone:</strong> <?php echo $lab_data["phone"];?>
											</address>
											<button class="form-control btn btn-danger" type="submit" name="go_lab_view"><?php echo "Go to ".$lab_data["lab_name"];?></button>
										</div>						
									</div>
								</form>
							</div>
						<?php
								$lab_no++;
							}
						?>
					</div>
				</ul>
					
			</div>



		
		</div>	
	</body>
	
	<script src="script/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	
</html>

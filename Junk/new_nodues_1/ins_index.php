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
		$emp_no = "EMP03";
		$_SESSION["emp_no"]="EMP03";
		$lab_sql = "SELECT * FROM lab_info WHERE emp_id =\"".$emp_no."\"";
		$lab_result = $con->query($lab_sql);
	?>	
	
	<body>
	
	<div class="container">
		<div class="row">
			<ul>
			
					<li><a href="#">Frequently Asked Questions (FAQs) </a></li>
					<li><a href="#">User Manual- Lab Instructor</a></li>
					<li> <strong>My Labs</strong></li><br>
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
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>	
</html>
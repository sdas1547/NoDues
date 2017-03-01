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
		$emp_no = $_SESSION['emp_no'];
		
		//echo $emp_no;
		$labAssigned = "labAssigned".$emp_no;
		$lab_sql1 = 	"CREATE OR REPLACE VIEW $labAssigned AS
						SELECT lab_code
							FROM incharge 
								WHERE incharge_UID = '$emp_no';";

		$labHeader = "labHeader".$emp_no;
		$lab_sql2 = "CREATE OR REPLACE VIEW $labHeader AS
						SELECT lab_info.lab_code, department_code, address
							FROM $labAssigned, lab_info 
								WHERE lab_info.lab_code = $labAssigned.lab_code;";

		$lab_details = "lab_details".$emp_no;
		$lab_sql3 = "CREATE OR REPLACE VIEW $lab_details AS
						SELECT a1.full_form AS lab_name,
						a2.full_form AS department_name, department_code,
						lab_code, address
							FROM accronym AS a1, accronym AS a2, $labHeader
								WHERE a1.code = lab_code
								AND a2.code = department_code;";

		$lab_sql4 = "SELECT * 
						FROM $lab_details;";

		$view_drop_sql = "DROP VIEW $labAssigned, $labHeader, $lab_details;";

		$lab_result1 = $con->query($lab_sql1);
		$lab_result1 = $con->query($lab_sql2);
		$lab_result2 = $con->query($lab_sql3);
		$lab_result = $con->query($lab_sql4);
		$con->query($view_drop_sql);
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
							$_SESSION["department_code".$lab_no]=$lab_data["department_code"];
							$_SESSION["department_name".$lab_no]=$lab_data["department_name"];
							$_SESSION["lab_code".$lab_no] = $lab_data["lab_code"];
							$_SESSION["lab_name".$lab_no] = $lab_data["lab_name"];
					?>
						<div class="col-sm-6 col-md-4">
							<form method="post" action="./lab_index.php?id=<?php echo $lab_no;?>" enctype="multipart/form-data">
								<div class="panel panel-primary">
								<div class="panel-heading"><strong><?php echo $lab_data["lab_name"];?></strong></div>
									<div class="panel-body">
										<strong><?php echo $lab_data["department_name"]; ?></strong> <br>
										<strong>Labortory Id:</strong> <?php echo $lab_data["lab_code"]; ?>
										<address>
											<strong>Address: </strong><?php echo $lab_data["address"];?> <br>
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
	
	<script src="script/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>	
</html>
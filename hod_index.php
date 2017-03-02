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
		//$_SESSION["admin_id"] = "hodCS";
		$admin_id = $_SESSION["admin_id"];
		//$_SESSION["department"] = "CSE";		
	?>
	
	<body>
		<div class="container">
			<div class="row">
				<ul>
					<!-- <button class="btn-btn-warning">Hello Moto </button> -->
					<li><a href="#">Frequently Asked Questions (FAQs)</a></li>
					<li><a href="#">User Manual - Department Head</a></li>
					<br>
					<li><a data-toggle="collapse" href="#likely_graduating"><strong>Likely Graduating Students</strong></a>
						<ul class="collapse in" id="likely_graduating">
							<li><a href="./graduating_student.php?programme=ug">Undergraduate Students</a></li>
							<li><a href="./graduating_student.php?programme=pg">Postgraduate Students</a></li>
							
						</ul>
					</li>

					<hr>
					<li><a data-toggle="collapse" href="#pending_dues"><strong>Pending Dues</strong></a>
						<ul class="collapse in" id="pending_dues">
							<li><a href="./hod_pending.php?programme=ug">Undergraduate Students</a></li>
							<li><a href="./hod_pending.php?programme=pg">Postgraduate Students</a></li>
							<li><a href="./hod_pending.php?programme=phd">Phd and MSR Studnets</a></li>
							<li><a href="./hod_pending.php?programme=all">All Students</a></li>
						</ul>
					</li>
					
				</ul>
			
				<ul>
					<li><strong>My Components</strong></li>
					<hr>
					<div class="row">
						<?php 
							$labdata = "labdata".$admin_id;
							$lab_data_sql1 = "CREATE OR REPLACE VIEW $labdata AS
												SELECT lab_code, department_code, address
													FROM lab_info
														WHERE hod_UID = '$admin_id';";
							$lab_data_sql = "SELECT a1.full_form AS department_name,
												a2.full_form AS lab_name, address, lab_code, department_code
												FROM $labdata, accronym as a1, accronym as a2
													WHERE a1.code = lab_code
													AND a2.code = department_code;";

							$lab_drop_sql = "DROP VIEW $labdata;";

							$con->query($lab_data_sql1);
							$lab_data_result = $con->query($lab_data_sql); 
							$con->query($lab_drop_sql);

							// echo $lab_data_sql1."<br>";
							// echo $lab_data_sql."<br>";
							$lab_no = 0;
							while($lab_data = $lab_data_result->fetch_assoc()){
								$_SESSION["department_code".$lab_no]=$lab_data["department_code"];
								$_SESSION["department_name".$lab_no]=$lab_data["department_name"];
								$_SESSION["lab_code".$lab_no] = $lab_data["lab_code"];
								$_SESSION["lab_name".$lab_no] = $lab_data["lab_name"];
						?>		
						<div class="col-sm-6 col-md-4">
							<div class="panel panel-primary">
							<div class="panel-heading"><strong><?php echo $lab_data["lab_name"];?></strong></div>
								<div class="panel-body">
									<strong><?php echo $lab_data["department_name"]; ?></strong> <br>
									<strong>Labortory Id:</strong> <?php echo $lab_data["lab_code"]; ?>
									<address>
										<strong>Address: </strong><?php echo $lab_data["address"];?> <br>
									</address>
								</div>						
							</div>
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
 	<script>
// $('#process').click(function() {

//  $.ajax({
//   type: "POST",
//   url: "./insertlikely.php",
//   data: { name: "John" }
// }).done(function( msg ) {
//   alert( "Data Saved: " + msg );
// });    

//     });

// 	</script>
	
	
</html>

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
		if(!(isset($_SESSION["admin_id"]) && isset($_SESSION["department"]) && isset($_GET["programme"]))){
			echo "You are not logged in.";
			die;
		}
		$admin_id = $_SESSION["admin_id"];
		$programme = $_GET["programme"];
		$department = $_SESSION["department"];
	?>
	<body>
		<div class="container">
			<div class="row">
				<h3>
				<?php
					echo $department."	</h3><h4>Programme: ";
					if($programme=="ug"){
						echo "Undergraduate";
					}
					else if($programme=="pg"){
						echo "Postgraduate";
					}
					else if($programme=="phd"){
						echo "PhD";
					}
					else if($programme=="all"){
						echo "All";
					}
					else{
						die;
					}

					if($programme=='ug'){
							$pending_sql = "SELECT entry_number,SUM(amount) AS total_amount FROM dues WHERE department='$department'  AND (current_status='N' OR current_status='E') AND (category='btech' OR category='dual')  GROUP BY entry_number";
						}
						else if($programme=='pg')
						{
							$pending_sql = "SELECT entry_number,SUM(amount) AS total_amount FROM dues WHERE department='$department'  AND (current_status='N' OR current_status='E') AND category='mtech'  GROUP BY entry_number";

						}

						else if($programme=='phd')
						{
							$pending_sql = "SELECT entry_number,SUM(amount) AS total_amount FROM dues WHERE department='$department'  AND (current_status='N' OR current_status='E') AND category='phd'  GROUP BY entry_number";

						}
						else
						{
							$pending_sql = "SELECT entry_number,SUM(amount) AS total_amount FROM dues WHERE department='$department'  AND (current_status='N' OR current_status='E')   GROUP BY entry_number";
						}










				?>
				</h4>
				<hr>
				<div class="row">
					<div class="col-sm-5 col-md-4 col-xs-4">
						<input class='form-control' name="key" type="text" placeholder="Search here.." id="filter">
					</div>
				</div>
				<br>
				<ul class="nav nav-tabs">
					<li class="active" role="presentation">
						<a data-toggle="tab" href="#student_pending">Student with Pending Dues</a>
					</li>
					<li role="presentation">
						<a data-toggle="tab" href="#clear">Students with Cleared Dues</a>
					</li>
					<li role="presentation">
						<a data-toggle="tab" href="#student_all">All Students</a>
					</li>
				</ul>
				<br>
				<div class="tab-content">
					<div id="student_pending" class="tab-pane fade in active">
						<?php

							
							
							$pending_result = $con->query($pending_sql);
							$pending_rows = mysqli_num_rows($pending_result);
							if($pending_rows>0){							
						?>
							<table class="table table-responsive table-striped table-hover" style="font-size:15">
								<thead>
									<th>S. No</th>
									<th>Entry Number</th>
									
									<th>Amount</th>
									<th>Action</th>
								<thead>
							<?php
								$i = 1;
								while($pending_data = $pending_result->fetch_assoc()){

									$entr=$pending_data["entry_number"];

									$que="SELECT * from graduating where entry='$entr'";
									$re = $con->query($que);
							$rowss = mysqli_num_rows($re);
							if($rowss>0){



									echo "<tr class = data>
											<td>".$i++."</td>
											<td>".$pending_data["entry_number"]."</td>
											
											<td>".$pending_data["total_amount"]."</td>
											<td><a>Details</a><td>";}
								}
							
							echo "</table>";
						
							}
							else{
								echo "<strong>No Records Found.</strong>";
							}
						

						?>
					</div>

					

					<div id="clear" class="tab-pane fade ">
						







</div>

				
					<div id="student_all" class="tab-pane fade">
					</div>
				</div>
			</div>
		</div>
		
		<script src="js/bootstrap.min.js"></script>
		<script src="script/jquery.js"></script>
		
		<!-- Javascript for searching through the table-->
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
</html>
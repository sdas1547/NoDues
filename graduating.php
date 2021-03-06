<html>
	<head>
		<meta charset="utf-8">
		<script src="script/jquery.js"></script>
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
		if(!(isset($_SESSION["admin_id"]) && isset($_GET["programme"]))){
			echo "You are not logged in.";
			die;
		}
		$admin_id = $_SESSION["admin_id"];
		$programme = $_GET["programme"];
		
	?>
	<body>
		<div class="container">
			<div class="row">
				<h3>
				<?php
					echo "	</h3><h4>";
					if($programme=="ug"){
						echo "Undergraduate Section";
					}
					else if($programme=="pg"){
						echo "Postgraduate Section";
					}
					
					else{
						die;
					}




					$viewname="view_".$admin_id;
							$creating_view= "create or replace view ".$viewname." as select dues.entry_number,student_table.department_code,student_table.programme as category, sum(amount) as total_amount from dues join student_table where dues.entry_number=student_table.entry_number and (status='P' or status='R') and dues.entry_number in (select entry_num from likely_grad) group by entry_number";


					if($programme=='ug'){
							
							 $pending_sql = "SELECT * FROM ".$viewname. " where (category='btech' or category='dual')";
						}
						else if($programme=='pg')
						{
							 $pending_sql = "SELECT * FROM ".$viewname. " where category='mtech'";

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
						<a data-toggle="tab" href="#approved">Approved Students</a>
					</li>

					<li  role="presentation">
						<a data-toggle="tab" href="#student_pending">Student with Pending Dues</a>
					</li>
					
				</ul>
				<br>
				<div class="tab-content">
					<div id="student_pending" class="tab-pane fade ">
						<?php


							//echo $creating_view;
							$create_view=$con->query($creating_view);
							
							

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

									$que="SELECT * from likely_grad where entry_num='$entr' and category='".$programme."'";
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

					

				

				
					<div id="approved" class="tab-pane fade in active">





<?php


							//echo $creating_view;
							$create_view=$con->query($creating_view);
						$que="SELECT * from likely_grad where   hod_approval=1 and category='".$programme."'";
							

							$pending_result = $con->query($que);

							
							$pending_rows = mysqli_num_rows($pending_result);

							if($pending_rows>0){							
						?>
							<table class="table table-responsive table-striped table-hover" style="font-size:15">
								<thead>
									<th>S. No</th>
									<th>Entry Number</th>
									
									
									<th>Status</th>
								</thead>
							<?php
								$i = 1;
								while($pending_data = $pending_result->fetch_assoc()){

									$entr=$pending_data["entry_num"];
									echo "<tr class = data>
											<td>".$i++."</td>
											<td>".$pending_data["entry_num"]."</td>
											
											
											<td>Approved</td>";



										}
								
							
							echo "</table>";
						
							}
							else{
								echo "<strong>No Records Found.</strong>";
							}
						

						?>















					</div>
				</div>
			</div>
		</div>
		
		<script src="js/bootstrap.min.js"></script>
		
		
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
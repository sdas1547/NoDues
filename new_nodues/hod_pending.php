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
						<?php echo "yep"; ?>
					</div>

					

					<div id="clear" class="tab-pane fade">
						
						<?php echo "2";?>

					



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
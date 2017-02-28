<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Home HOD | No Dues IIT Delhi</title>
			<script src="script/jquery.js"></script>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="./mystyle.css" rel="stylesheet">
	</head>
	
	<?php
		session_start();
		include("./header.php");
		include("./dbinfo.inc");
		$_SESSION["admin_id"] = "arUGS";
		$admin_id = $_SESSION["admin_id"];
		

	?>
	
	<body>
		<div class="container">
			<div class="row">
				<ul>
					
				
					<li><a data-toggle="collapse" href="#likely_graduating"><strong>Likely Graduating Students</strong></a>
						<ul class="collapse in" id="likely_graduating">
							<li><a href="./graduating.php?programme=ug">Undergraduate Students</a></li>
							
							
						</ul>
					</li>
				
					<li><a data-toggle="collapse" href="#pending_dues"><strong>Pending Dues</strong></a>
						<ul class="collapse in" id="pending_dues">
							<li><a href="./pending.php?programme=ug">Undergraduate Students</a></li>
							
						</ul>
					</li>
					
				</ul>
			

				<form action="./insertlist.php" method="POST" enctype="multipart/form-data">
					
						<label> Upload the list of graduating student</label>
						<input type="hidden" value="ug" name="prg">
				<input class="form-control" type="file" accept=".csv" name="file_to_upload" id="file_to_upload" >
				<br>
				<button class="btn btn-primary" type="submit" name="submit" id="process"> Process</button>
			
		</form>

				
					
			</div>



		
		</div>	
	</body>
	



	<script src="js/bootstrap.min.js"></script>
	
</html>

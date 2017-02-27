<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Make Request</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="./mystyle.css" rel="stylesheet">
	</head>
	<?php
		session_start();
		include("./header.php");
		include("./dbinfo.inc");
		if(isset($_GET["due_id"])){
			$due_id = $_GET["due_id"];
		}
		else{}
		if(!isset($_SESSION["entry_no"])){
			echo "You are not logged in.";
		}
		else{
			$entry_num=$_SESSION["entry_no"];
		}
		
		
	?>
	<body>
		<div class="container">
		<?php
			$due_query = "SELECT * FROM dues WHERE due_id='$due_id'";
			$due_result = $con->query($due_query);
			while($due_data = $due_result->fetch_assoc()){
		?>
			<div class="panel panel-info">
				<div class="panel-heading">
					<strong>Due ID: <?php echo $due_id;?></strong>
				</div>				
				<div class="panel-body">
					<?php 
						echo "<strong>Department	: </strong>".$due_data["department"]."<br>
							  <strong>Lab ID		: </strong>".$due_data["lab_id"]."<br>
							  <strong>Description	: </strong>".$due_data["description"]."<br>
							  <strong>Amount		: </strong>Rs. ".$due_data["amount"]."<br>
							  <strong>Created by	: </strong>".$due_data["created_by"]."<br>
							  <strong>Created on	: </strong>".$due_data["created_time"]."<br><br>";
					?>
					
					


						<?php $file_name="uploads/".$due_id."_".$_SESSION['entry_no'].'.pdf';?>
						




						<div class="form-group">
							<label class="control-lablel col-sm-1 col-xs-2">Upload:</label>
							<div class="col-sm-11">
								<?php 

									if(file_exists($file_name))
									{


										echo '<iframe src='.$file_name.' width="800px" height="800px" />';
										 
									}
									else
									{
										echo '<img src="images/images.png" alt="Not Available" height="40px;" width="150px;">';

									}


								?>
								
							</div>							
						</div>

						</div>						
					
				</div>
			</div>
		<?php
			}
		?>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
	</body>
</html>
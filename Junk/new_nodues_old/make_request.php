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
		if(isset($_SESSION["entry_num"])){
			$entry_num=$_SESSION["entry_num"];		
		
	?>
	<body>
		<div class="container">
		<?php
			$due_query = "SELECT * FROM dues WHERE entry_number='$entry_num' AND due_id='$due_id' AND (current_status='N' OR current_status='E')";
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
					
					<form class="form-horizontal" method="post" action="./uploads.php?due_id=<?php echo $_GET["due_id"];?>" enctype="multipart/form-data">	
						<div class="form-group">
							<label class="control-lablel col-sm-2 col-xs-4">Attach a document:</label>
							<input type="file" name="file_to_upload" id = "file_to_upload">
						</div>
						
						<div class="form-group">
							<label class="control-lablel col-sm-1 col-xs-2">Remark:</label>
							<div class="col-sm-11">
								<textarea class="form-control" rows="4" column="40" name="comment" value="<?php echo $comment; ?>"></textarea>
							</div>							
						</div>
						
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-3">
								<button class="form-control btn btn-primary" type="submit" name="make_request_button">Submit</button>
							</div>
							
							<div class="col-sm-offset-2 col-sm-3">
								<button class="form-control btn btn-danger" type="reset" name="cancel_button">Cancel</button>
							</div>
						</div>						
					</form>
				</div>
			</div>
		<?php
			}
		?>
		</div>
	<?php
		}
		else{
			echo "You are not logged in.";
		}
	?>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		
	</body>
</html>
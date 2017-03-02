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

		if (!isset($_SESSION["login_id"])){
			header ("Location: ../index.php");
			die;
		}
		if(isset($_GET["due_id"])){
			$due_id = $_GET["due_id"];
		}
		else{}
		if(!isset($_SESSION["entry_no"])){
			echo "You are not logged in.";
		}
		else{
			$entry_number =$_SESSION["entry_no"];
		}
		
		
	?>
	<body>
		<div class="container">
		<?php

			$student_pending = "student_pending".$entry_number;
			$pending_dues_sql1 = "CREATE OR REPLACE VIEW $student_pending AS
										SELECT dueID, amount, description,
											generated_time, employee_uID, lab_code, modified_time
											FROM dues
												WHERE dueID = $due_id
												AND entry_number = '$entry_number';";
			
			$labDues = "labDues".$entry_number;
			$pending_dues_sql2 = "CREATE OR REPLACE VIEW $labDues AS
										SELECT DISTINCT lab_code, employee_uID
											FROM $student_pending;";

			$labHeader = "labHeader".$entry_number;
			$pending_dues_sql3 = "CREATE OR REPLACE VIEW $labHeader AS
									SELECT DISTINCT lab_info.lab_code, department_code, address
										FROM lab_info, $labDues
											WHERE lab_info.lab_code = $labDues.lab_code;";										

			$lab_details = "lab_details".$entry_number;
			$pending_dues_sql4 = "CREATE OR REPLACE VIEW $lab_details AS
									SELECT a1.full_form AS lab_name, 
									a2.full_form AS department_name,
									lab_code, address
										FROM accronym AS a1, accronym AS a2, $labHeader
											WHERE a1.code = lab_code
											AND a2.code = department_code;";

			$employee_details = "employee_details".$entry_number;
			$pending_dues_sql5 = "CREATE OR REPLACE VIEW $employee_details AS
									SELECT DISTINCT name, employee_uID from user_table, $labDues
										WHERE user_table.uID = employee_uID;";

			$due_query = "SELECT description,
								$student_pending.dueID, generated_time, modified_time, 
								name, amount , lab_name, department_name
								FROM $lab_details, $employee_details, $student_pending
									WHERE $student_pending.lab_code = $lab_details.lab_code
									AND $student_pending.employee_uID = $employee_details.employee_uID ORDER BY generated_time;";

			$pending_drop_sql = "DROP VIEW $student_pending, $labDues, $labHeader, $lab_details, $employee_details;";

			$con->query($pending_dues_sql1);
			$con->query($pending_dues_sql2);
			$con->query($pending_dues_sql3);
			$con->query($pending_dues_sql4);
			$con->query($pending_dues_sql5);
			$due_result = $con->query($due_query);
			$con->query($pending_drop_sql);
			
			while($due_data = $due_result->fetch_assoc()){
		?>
			<div class="panel panel-info">
				<div class="panel-heading">
					<strong>Due ID: <?php echo $due_id;?></strong>
				</div>				
				<div class="panel-body">
					<?php 
						echo "<strong>Department	: </strong>".$due_data["department_name"]."<br>
							  <strong>Lab Name		: </strong>".$due_data["lab_name"]."<br>
							  <strong>Description	: </strong>".$due_data["description"]."<br>
							  <strong>Amount		: </strong>Rs. ".$due_data["amount"]."<br>
							  <strong>Added by		: </strong>".$due_data["name"]."<br>
							  <strong>Added on		: </strong>".$due_data["generated_time"]."<br>
							  <strong>Last Modified	: </strong>".$due_data["modified_time"]."<br><br>";

					?>
					
					<form class="form-horizontal" method="post" action="./uploads.php?due_id=<?php echo $_GET["due_id"];?>" enctype="multipart/form-data">	
						<div class="form-group">
							<label class="control-lablel col-sm-2 col-xs-4">Attach a document:</label>
							<input accept="application/pdf" required  type="file" name="file_to_upload" id = "file_to_upload">
						</div>
						<?php $file_name="uploads/".$due_id."_".$_SESSION['entry_no'].'.pdf';?>
						<div class="form-group">
							<label class="control-lablel col-sm-2 col-xs-2"> Previous Upload:</label>
							<?php 
								if(!file_exists($file_name)){
									echo "No uploads available";
									}
								else
								{
							?>
								<div class="col-sm-10 col-sm-offset-2">	
							<?php		
								// $file11 = fopen($file_name, "r");
								echo "<iframe src=".$file_name." width=800px height=600px/></iframe>"; 
							?>
							</div>
							<?php	
								}
							?>

							
								
														
						</div>

						<input type=hidden value=<?php echo $due_id;?> name="did">

						<div class="form-group">
							<label class="control-lablel col-sm-1 col-xs-2">Remark:</label>
							<div class="col-sm-10">
								<textarea class="form-control" required rows="2" column="40" name="comment" value="<?php echo $comment; ?>"></textarea>
							</div>							
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-3">
								<button class="form-control btn btn-primary" type="submit" name="make_request_button">Submit</button>
							</div>
							
							<div class="col-sm-offset-2 col-sm-3">
								<button class="form-control btn btn-danger" type="reset" name="cancel_button" onclick="history.go(-1);">Back</button>
							</div>
						</div>						
					</form>
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
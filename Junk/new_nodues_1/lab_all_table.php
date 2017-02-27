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
		include("./header.php");
		include("./dbinfo.inc");
		$lab_id = $_GET["lab_id"];
		$department = $_GET["department"];
		$emp_id = $_GET["emp_id"];
	?>
	<body>
		<div class="container">
			<table class="table">
				<tr>
					<th>S. No</th>
					<th>Entry Number</th>
					<th>Added on</th>
					<th>Description</th>
					<th>Amount</th>
					<th>Status</th>
				</tr>
			<?php
				$pending_sql = "SELECT * FROM dues WHERE created_by='$emp_id' AND lab_id='$lab_id' ORDER BY created_time DESC";
				$pending_result = $con->query($pending_sql);
				$i=1;
				$lab_total=0;
				while($pending_data = $pending_result->fetch_assoc()){
					echo "<tr>
					<td>".$i++."</td>
					<td>".$pending_data["entry_number"]."</td>
					<td>".$pending_data["created_time"]."</td>
					<td>".$pending_data["description"]."</td>
					<td>".$pending_data["amount"]."</td>
					<td>".$pending_data["current_status"]."</td>
					</tr>";
					$lab_total=$lab_total+$pending_data["amount"];
				}
			?>
			</table>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
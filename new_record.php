<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Header</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="./mystyle.css" rel="stylesheet">
		<style>
			.error {color: #FF0000;}
		</style>
	</head>
	
	<?php
		session_start();
		include("./dbinfo.inc");
		include ("./header.php");	
	?>	
	
	<body>
	
	<?php
		$entryErr = $typeErr = $descErr = "";
		$amountErr = "";
		$entry_num = $type = $description = "";
		
		if(!isset($_SESSION["emp_no"])){
			echo "You are not logged in.";
			die;
		}
		else{
			$emp_no = $_SESSION["emp_no"];
		}
		
		if(isset($_GET["lab_id"])){
			$id = $_GET["lab_id"];
			if(isset($_SESSION["department_code".$id]) && isset($_SESSION["department_code".$id]) && isset($_SESSION["lab_code".$id]) && isset($_SESSION["lab_name".$id])){
				$lab_name = $_SESSION["lab_name".$id];
				$lab_code = $_SESSION["lab_code".$id];
				$department_name = $_SESSION["department_code".$id];
				$department_code = $_SESSION["department_name".$id];
			}
			else{
				echo "Data not found".
				die;
			}
		}		
		$amount = 0;
		$correct_entry = false;
		
		if($_SERVER["REQUEST_METHOD"]=="POST"){
			$correct_entry = true;
			if(empty($_POST["entry_num"])){
				$entryErr = "Required Field";
				$correct_entry = false;
			}
			else{
				$entry_num = strtoupper(test_input($_POST["entry_num"]));
			}

			if(empty($_POST["cat"])){
				$catErr = "Required Field";
				$correct_cat = false;
			}
			else{
				$categ = (test_input($_POST["cat"]));
			}		
			
			if(empty($_POST["description"])){
				$descErr = "Required Field";
				$correct_entry = false;
			}
			else{
				$description = test_input($_POST["description"]);
			}
			
			if($_POST["amount"]==0){
				$amountErr = "Amount must be greater than 0";
				$correct_entry = false;
			}
			else{
				$amount = test_input($_POST["amount"]);
			}
		}
		if($correct_entry){
			echo $amount.' '.$description.' '.$entry_num.' '.$emp_no.' '.$lab_code;
			$add_rec_sql = "INSERT INTO dues (
									amount, 
									description, 
									generated_time, 
									modified_time, 
									status,
									entry_number,
									employee_uID,
									lab_code) VALUES ( 
									'$amount', 
									'$description', 
									now(), 
									now(), 
									'P', 
									'$entry_num', 
									'$emp_no', 
									'$lab_code');";
			$add_result = $con->query($add_rec_sql);
			echo $add_result;
			if ($add_result){
				header("Location: http://testportal.iitd.ac.in/new_nodues/lab_index.php?id=$id");
				exit();
			}
		}
		
		function test_input($data){
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
	?>
	
	<div class="container">	
		<h3 class="col-sm-offset-1">Add a new record:</h3>
		<p><span class="col-sm-offset-1 error"> * Required field. </span></p>
		<br>	

		<form class="form-horizontal" method="post" action="">
			<div class="form-group">
				<label class="control-lablel col-sm-offset-1 col-sm-2">Entry No:</label>
				<div class="col-sm-3">
					<input class="form-control"  style="text-transform:uppercase" maxlength="11" placeholder="Enter Entry Number" type="text" name="entry_num" value="<?php echo $entry_num;?>" id="entry_n" onkeyup	="checkInput('entry_n')"  oninput="checkInput('entry_n')">
				</div>
				<span class="error">* <?php echo $entryErr; ?> </span>
			</div>
			
			<div class="form-group">
				<label class="control-lablel col-sm-offset-1 col-sm-2">Name</label>
				<div class="col-sm-3">
					<input class="form-control" placeholder="Name" type="text" name="name"  id="name" readonly>
				</div>
				<span class="error">*  </span>
			</div>

			<div class="form-group">
				<label class="control-lablel col-sm-offset-1 col-sm-2">Programme</label>
				<div class="col-sm-3">
					<input class="form-control" placeholder="category" type="text" name="cat"  id="cat" readonly>
				</div>
				<span class="error">*  </span>
			</div>

			
			<div class="form-group">
				<label class="control-lablel col-sm-offset-1 col-sm-2">Department:</label>
				<div class="col-sm-3">
					<input class="form-control" type="text" name="department" value="<?php echo $department_name;?>" readonly>
				</div>
			</div>
			
			<div class="form-group">
				<label class="control-lablel col-sm-offset-1 col-sm-2">Lab Name:</label>
				<div class="col-sm-3">
					<input class="form-control" type="text" name="lab_name" value="<?php echo $lab_name;?>" readonly>
				</div>
			</div>			
			
			<div class="form-group">
				<label class="control-lablel col-sm-2 col-sm-offset-1">Description:</label>
				<div class="col-sm-7">
					<textarea class="form-control" rows="2" column="40" name="description"><?php echo $description; ?></textarea>
				</div>
				<span class="error">* <?php echo $descErr; ?> </span>
			</div>
			
			<div class="form-group">
				<label class="control-lablel col-sm-2 col-sm-offset-1">Amount:</label>
				<div class="col-sm-3">
					<input class="form-control" placeholder="Enter Amount" type="number" min="1" name="amount" value="<?php echo $amount;?>">
				</div>
				<span class="error">* <?php echo $amountErr; ?> </span>
			</div>
			<br>
			<br>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-3">
					<button class="form-control btn btn-primary" type="submit" name="add_due_button">Submit</button>
				</div>
				
				<div class="col-sm-offset-1 col-sm-3">
					<button class="form-control btn btn-danger" type="reset" name="cancel_button" onclick="history.go(-1);">Back</button>
				</div>
			</div>			
		</form>	
	</div>

	<div class="footer">
		<div class="row">
			<br>
			<br>
		</div>
	</div>


		<script>
		function checkInput(textbox) {
			 var textInput = document.getElementById(textbox).value;
			 var len=textInput.length;
			if(len==11){
				$.get('getname.php', { uid:$("#entry_n").val() }).done(function(data){
					var obj = JSON.parse(data);				
					$('#name').val(obj.name);
					$('#cat').val(obj.cat);
				});
			}
			else 
			{
				$('#name').val("");
				$('#cat').val("");
			}
		}
		</script>
		<script src="script/jquery.js"></script>		
		<script src="js/bootstrap.min.js"></script>		
	</body>	
</html>
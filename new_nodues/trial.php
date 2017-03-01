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
	<body>
<?php include('./dbinfo.inc'); ?>

		<form class="form-horizontal" method="post" action="">
			<div class="form-group">
				<label class="control-lablel col-sm-offset-1 col-sm-2">Entry No:</label>
				<div class="col-sm-3">
					<input class="form-control"  style="text-transform:uppercase" maxlength="11" placeholder="Enter Entry Number" type="text" name="entry_num"  id="entry_n" onkeyup	="checkInput('entry_n')"  oninput="checkInput('entry_n')">
					<select id="students">

						<?php
                   
                    $result = mysqli_query($con, 'SELECT uID,name FROM user_table');
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='$row[0]'>$row[1]</option>";
                    }
                    ?>

					</select>

				</div>
				
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
					<input class="form-control" type="text" name="department"  readonly>
				</div>
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
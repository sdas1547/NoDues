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

<table>
<?php 
$i=0;
while($i<5)
{

$i++;
?>

<tr>
<td><input type="checkbox" class="ans" name="check[]"></td>
<td><input type="text" class="bingo" name="vals"></td>
</tr>
<?php

}


?>
<form id="sampleForm" name="sampleForm" method="post" action="stub.php">
<input type="hidden" name="total" id="total" value="">
<a href="#" onclick="setValue();">Click to submit</a>
</form>

<script>
function setValue(){


	
		document.sampleForm.total.value=""
  $('tr').each(function(ind) {
  	if($(this).find('.ans').prop("checked")==true)
  		
  	document.sampleForm.total.value += $(this).find('.bingo').val()+",";
  	//alert("as");
      });









   // document.sampleForm.total.value = 100;
    document.forms["sampleForm"].submit();
}
</script>

</body>
</html>
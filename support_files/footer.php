<?php
//Included in almost all pages
?>
<div id="footer">
	<?php
		if (!isset($_SESSION['login_id'])) {
			?>
			You are not logged in!
			<?php
		}
		else
		{
			echo $_SESSION["name"]." (".$_SESSION["login_id"].") (<a href='../logout'>Logout</a>)";
		}
		?>
	<br>
	Your IP address is <?php echo $_SERVER['REMOTE_ADDR'] ?><br>
	System Created & Developed By: <a href='https://www.linkedin.com/in/chiragb1994' target='_blank'>Chirag Bansal</a>
</div>
<!--System Created & Developed By: Chirag Bansal (2012EE10445) under the supervision of Prof M R Ravi (Associate Dean Curriculum)-->
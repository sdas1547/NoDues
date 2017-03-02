	<style>
		html {
			overflow-y: scroll;
		}
		td {
			word-wrap: break-word;
			max-width: 300px;
		}
	</style>
	<div class="header">
		<nav class = "navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<!--For responsive page -->
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  
				  <a class="navbar-brand iitd_logo" href="#">
						<img class="img" height="60px" width="60px" alt="iitd_logo" src="images\iitd_logo.png">
				 </a>
				  
				</div>

				<?php
					if(isset($_SESSION["admin_id"])){
						$location_link = "location.href = 'http://testportal.iitd.ac.in/new_nodues/hod_index.php'";
					}
					else if(isset($_SESSION["emp_no"])){
						$location_link = "location.href = 'http://testportal.iitd.ac.in/new_nodues/ins_index.php'";
					}
					else if (isset($_SESSION["entry_no"])){
						$location_link = "location.href = 'http://testportal.iitd.ac.in/new_nodues/student_index.php'";
					}
					else{
						$location_link = "http://testportal.iitd.ac.in/index.php";
					}

				?>
				
				<div class="collapse navbar-collapse" id="bs-navbar-collapse-1">
					<ul class="nav navbar-nav navbar-right">
					<?php
						// echo $_SERVER['REQUEST_URI'];
					if(strcmp($_SERVER['REQUEST_URI'], "/index")!=0
						&& strcmp($_SERVER['REQUEST_URI'], "/")!=0
						){
						// echo $_SERVER["REQUEST_URI"];

							if(strcmp($_SERVER['REQUEST_URI'], "/new_nodues/student_index.php")!=0 
								&& strcmp($_SERVER['REQUEST_URI'], "/new_nodues/hod_index.php")!=0 
								&& strcmp($_SERVER['REQUEST_URI'], "/new_nodues/ins_index.php")!=0 
							){
						?>
								<li class="col-sm-2">
									<button type="button" id="back_button" class="btn btn-warning navbar-btn"  onclick="history.go(-1);">Back</button>
								</li>
						<?php
							}
							
						?>
							<li class="col-sm-2 col-sm-offset-1">
								<button type="button" id="home_button" class="btn btn-primary navbar-btn"  onclick=<?php echo "\"".$location_link."\"";?>>Home</button>
							</li>
							<li class="col-sm-3 col-sm-offset-1">
								<button type="button" id="iitd_home_button" class="btn btn-primary navbar-btn" onclick="location.href='http://www.iitd.ac.in'">IITD Home</button>
							</li>
							<li class="col-sm-2 col-sm-offset-1">
								<button type="button" id="logout_button" class="btn btn-danger navbar-btn" onclick="location.href='http://testportal.iitd.ac.in/logout.php'">Logout</button>
							</li>
							
						
				<?php
					}
					else{
				?>
							<li class="col-sm-3 col-sm-offset-1">
								<button type="button"  target = "_blank" id="iitd_home_button" class="btn btn-primary navbar-btn" onclick="window.open('http://www.iitd.ac.in')">IITD Home</button>
							</li>
				<?php
					}					
				?>
					</ul>
				</div>
			</div>
		</nav>
	</div>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		
		
<?php
session_start();
if(isset($_SESSION['id']) && $_SESSION['id'] != "")
    {

require 'dbconnection.php';
$uid=$_SESSION['id'];

$already=0;

if($_SERVER['REQUEST_METHOD']=='POST')
{
    $dep=$_POST['depcode'];
    
    $sq="SELECT * from requestee where userid='$uid'";
    $result=mysqli_query($connection,$sq);
    if(mysqli_num_rows($result)>0)
    {
        $already=1;
    }
 else
 {
     $already=0;
$sq1="SELECT * FROM places where department='$dep'";
    $sub=mysqli_query($connection,$sq1);
     while($row=mysqli_fetch_array($sub))
        {            
                    $place=$row['place_id'];
         $status=0;
         $sq2="INSERT INTO requests (dep_id,place_id,userid,status) values('$dep','$place','$uid','$status')";
        $r= mysqli_query($connection,$sq2);
         
          
                    
        }
     $status=0;
    $sq3="INSERT INTO requestee(userid,dep_id,status) VALUES('$uid','$dep','$status')";
    mysqli_query($connection,$sq3);
     
 }
    
    header('Location: /nodues/status.php');

//echo $already;
    
    
}

?>









<html>
    <head><link rel='shortcut icon' href='favicon.ico' type='image/x-icon'/ >
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
   
        <link href="css/mystyles.css" rel="stylesheet">
         <link href="css/mystyles2.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap-social.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Prociono">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Fertigo">
        

<script src="js/jquery-3.0.0.js"></script>

        
        
    </head>
    <body>
        
        
        
<!--
          <nav class="navbar navbar-inverse navbar-fixed-top" role=navigation>
    
    <div class="container">
        
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/admn/welcome_page.php"><img id="intro_image" src="images/IIT_Delhi_logo.png" height="50px" width="50px"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            
            <li><a href="/admn/welcome_page.php">Home </a></li>
            <li><a href="http://www.iitd.ac.in">IITD Home</a></li>
            </ul>
            
           
            <ul class="nav navbar-nav navbar-right" style="position:relative; top:-6px;">
                <li><a href="/admn/logout.php"><button id="logout_button" class="btn btn-danger">Logout</button></a> </li>
            
            </ul>
       
            
        </div>
        </div>
            
            
            
            
    </nav>
        

    <div id="wrapper">
        
    
        
        <nav class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
                <li class="sidebar-brand">
                    <a href="#">
                       Hidden
                    </a>
                </li>
                <li>
                    <a href="#">IITD Home</a>
                </li>
                <li>
                    <a href="#">Home</a>
                </li>
                <li>
                    <a href="#">Info1</a>
                </li>
                <li>
                    <a href="#">Info2</a>
                </li>
                
               
                <li>
                    <a href="#">Contact</a>
                </li>
                
            </ul>
        </nav>    
             
-->
            </div>
       
        
    

            <script>
                $(document).ready(function() {
  $(window).keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });
});
            </script>
            
            
    </body>
    <!-- /#wrapper -->
    
</html>
<?php 
     }
else{
       header('Location: /nodues/welcome.php'); //redirect URL
    }
?>
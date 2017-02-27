<?php  

session_start();
error_reporting(0);
if (isset($_SESSION)&& $_SESSION['id'] != "")

{
  header('Location: /nodues/welcome.php');   
}

?>



<html>
    <head><link rel='shortcut icon' href='favicon.ico' type='image/x-icon'/ >
    <script>
  function preventBack(){window.history.forward();}
  setTimeout("preventBack()", 0);
  window.onunload=function(){null};
</script>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            NoDues Portal
        </title>
        
        <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/mystyles.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap-social.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Prociono">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Fertigo">
        
    </head>
    
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role=navigation>
    
    <div class="container">
        
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php"><img id="intro_image" src="images/IIT_Delhi_logo.png" height="50px" width="50px"></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            
            <li><a href="index.php">Home </a></li>
            <li><a href="http://www.iitd.ac.in">IITD Home</a></li>
            </ul>
            
           
            
       
            
        </div>
        </div>
            
            
            
    </nav>
        
        
        <header class="jumbotron">
        
        

        <div class="container">
            <div class="row row-header">
                
                 <div class="col-xs-2 col-sm-2">
                    <div class="media">
                    
                    <img src="images/logo.png" class="img-responsive img-thumbnail" style="height:75px; width:75px;" alt="IIT Delhi">
                    </div>
                </div>
                <div class="col-xs-9 col-sm-9">
                    
                    
                    <b>  <p style="font-family:Fertigo; font-size:3em;">Indian Institute of Technology, Delhi</p></b>
                    
                </div>
                <div class="row">
            <div class="col-sm-12">
                <center><p style="Font-family:Prociono; font-size:3em;"> NO DUES PORTAL</p></center>
            </div>
        </div>
                
                
            </div>
        </div>
    </header>
          
    <div class="container" style=" padding-left:0px; margin-left:50px;">
        
        <div class="row">
        
         <div class="col-xs-12 col-sm-6 media" style="padding-top:20px;">
                    <div class="media-right">
                    
                    <img src="images/image2.jpg" class="img-responsive img-thumbnail" style="height:300px; width:300px;" alt="IIT Delhi">
                    </div>
                </div>
        
       
            
            
          
        
           <div class="col-xs-12 col-sm-4 " >
              
               
               <h3><p style="font-family:Fertigo;">Login </p></h3> 
                <form class="form-horizontal" role="form" method="post" action="/nodues/login_check.php">
                    <div class="form-group">
                        <label for="id" class="col-sm-3 control-label">Email ID</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="username" name="id" placeholder="Enter Your kerberos id" ></div>
                        </div>
                    <div class="form-group">
                        <label for="passwd" class="col-sm-3 control-label">Password</label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" id="passwd" name="password" placeholder="Enter Your password" ></div>
                        </div>
                    
                    
                    
                
                    <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                    <?php


if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == "true")

{
header('Location: /nodues/welcome.php'); 
          exit();
}
else{
$image=rand(1, 199);
$_SESSION['captcha'] = $image;
$file='CAPTCHA/'.$image.'.jpg';}
?>
<p><img src="<?php echo $file;?>" width="150" height="60" border="1" alt="CAPTCHA"></p>

<p><input type="text" size="6" maxlength="6" name="captcha" autocomplete= "off"><br>
<small>Enter the above coloured text </small></p>
                        <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </div>
                </form>
            </div>
             
       
        </div>
        
        
        
        </div>
        
        
       

        
    </body>
</html>
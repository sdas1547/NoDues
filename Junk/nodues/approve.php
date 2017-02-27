<?php
 error_reporting(0);
 session_start();


//on session creation
if(isset($_SESSION['id']) && $_SESSION['id'] != "")
    {$_SESSION['logged_in'] = "true";}
    ?>


<html>
    <head><link rel='shortcut icon' href='favicon.ico' type='image/x-icon'/ >
    
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>
            No Dues Portal
        </title>
        
        <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="css/mystyles.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/bootstrap-social.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Prociono">
        <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Fertigo">
        <script src="js/jquery-3.0.0.js"></script>
         <link href="css/mystyles2.css" rel="stylesheet">
        
        
    </head>
    
    <body>
        <script>        

</script>
        
        
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
            
            <li><a href="/nodues/welcome2.php">Home </a></li>
            <li><a href="http://www.iitd.ac.in">IITD Home</a></li>
            </ul>
            
           
            <ul class="nav navbar-nav navbar-right" style="position:relative; top:-6px;">
                <li><a href="/nodues/logout.php"><button id="logout_button" class="btn btn-danger">Logout</button></a> </li>
            
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
                <li >
                    <a href="#">IITD Home</a>
                </li>
                <li>
                    <a href="/nodues/welcome2.php">Home</a>
                </li>
                
                
                
               
                <li>
                    <a href="#">Contact</a>
                </li>
                
                <li>
                    <a href="/nodues/logout.php">Logout</a>
                </li>
                
            </ul>
        </nav>    
             
            </div>
        
        
       <?php
        
        require 'dbconnection.php';
       
    
   
       

       $user=$_SESSION['id'];
        
        $sq="SELECT * from places where supervisor='$user'";
        $re=mysqli_query($connection,$sq);
        while($row=mysqli_fetch_array($re))
        {            
                    $place_id=$row['place_id'];
                    $place_name=$row['place_name'];
                    $department=$row['department'];
        
        
        }
        
    $sql_query="SELECT * from requests where place_id='$place_id' and status=0";
       
       
       
    $res=mysqli_query($connection,$sql_query);
    
    
    ?>
    

        
        
        <div class="container" style="padding-top:50px; margin-left:250px;">
            
            
            <div class="row">
                <div class="col-sm-12 col-xs-12 col-sm-offset-3">
                    <p style="font-family:Prociono; font-size:3em;">Pending Requests</p>
                </div>
            </div>
            
            
            
            
                
        
        <div class="row" style="padding-top:40px;">
   
    
        
        <?php
        if(mysqli_num_rows($res)<1)
            echo "No Requests ";
       
        ?>
        
        
        <?php
        while($row=mysqli_fetch_array($res))
            
        {   
            
            echo '<div class=row><div class="col-sm-4 col-xs-4"><p style="font-family:Prociono; font-size:1.5em;">';
                    $userid=$row['userid'];
                    $req_id=$row['req_id'];
                    $status=$row['status'];
                    
                  echo $userid;         
        ?>
        
             
            
                
                <?php if($status==0)
                
                {
                    echo'<button class="btn btn-warning">';echo "Requested";echo '</button>';
           
            
            echo '<form method="post" action="process.php">';
                    
                        
                        
echo '<input type=hidden class="form-control" id="type" name="req" value=';echo $req_id;echo ">";
            
            
                      
                    
           
            
            echo '<button type=submit class="btn btn-success">'; echo "Approve";echo '</button>';
            
             echo '</form>';
                }
                else
                {
                    echo'<button class="btn btn-success">';echo "Approved";echo '</button>';
                }
            
           
                 echo '</div></div>'
                ?>
               
          
            
            <br><br>
           
            
        
        <?php }?>
        
        <br>
        
        
            <br><br>
            
            
            
       </div>
       </div>
            
            
            
            
            
            
            
            
            
            
            
            
            
            
        </div>
        
        
        
        
    
        
        <footer class="row-footer" style="position:absolute; bottom:0px; width:100%;">
        <div class="container">
            <div class="row">  
                
                <div class="col-xs-5 col-sm-6 col-sm-offset-4">
                    <h4><p id ="loginstate" style="color:blue" style="font-family:Fertigo;"> You are currently logged in (<?php echo $_SESSION['id']  ?>) </p></h4>
                    
                </div>
                
          
                
                
            </div>
                
                
        </div>
    </footer>

        
    </body>
</html>
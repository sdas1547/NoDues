<?php
 error_reporting(0);
 session_start();


//on session creation
if(isset($_SESSION['id']) && $_SESSION['id'] != "")
    {$_SESSION['logged_in'] = "true";}

require 'dbconnection.php';
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
       $rpp=10;
    if(!isset($_GET['screen']))
    {
        $screen=0;
    }
       else $screen=$_GET['screen'];
       
       
    $start=$screen*$rpp;
    
   
       

       $user=$_SESSION['id'];
        
        
        $sql_q="SELECT * from places where supervisor='$user'";
       $ans=mysqli_query($connection,$sql_q);
        while($row=mysqli_fetch_array($ans))
        {            
                    $place=$row['place_id'];
            echo $place;
                    
                    
        }
        
        
        
        
        
        
        
        
    $sql_query="SELECT * from dues where place_id='$place'";
       $ans=mysqli_query($connection,$sql_query);
       $pages=ceil(mysqli_num_rows($ans)/$rpp);
       $sql_query.="LIMIT $start,$rpp";
       
    $res=mysqli_query($connection,$sql_query);
    
    
    ?>
    

        
        
        <div class="container" style="padding-top:50px; margin-left:250px;">
            
            
            <div class="row">
                <div class="col-sm-12 col-xs-12 col-sm-offset-3">
                    <p style="font-family:Prociono; font-size:3em;">Your Pending Dues</p>
                </div>
            </div>
            
            
            
             
            
            
            
                
        
        <div class="row" style="padding-top:40px;">
    <div class="table-responsive">
    <table class="table table-striped table-hover table-condensed">
        
        <?php
        if(mysqli_num_rows($res)<1)
            echo "No complaints yet";
       
        ?>
        
        <tr >
            <th>Due ID</th>
        
<th>Added By</th>            
            
            <th>Value</th>
            <th>Description</th>
            <th>Entry on</th>
            
        </tr>
        <?php
        while($row=mysqli_fetch_array($res))
        {            $id=$row['id'];
                    $dep=$row['dept_id'];
                    $place=$row['place_id'];
                    $adder=$row['adder'];
                    $value=$row['value'];
                    $desc=$row['description'];
                    $entryon=$row['date_added'];
                    
        
        ?>
        <tr>
            <td>
                <?php echo $id;?>
            </td>
           
            
            <td>
                <?php echo $adder;?>
            </td>
            <td>
                <?php echo $value;?>
            </td>
            <td>
                <?php echo $desc;?>
            </td>
            <td>
                <?php echo $entryon;?>
            </td>
            
        </tr>
        <?php }?>
        </table>
        <br>
        
        <div class="col-sm-offset-5">
            
        <?php 
            if($screen<1){
            ?>
            <button class="btn btn-primary disabled">Previous</button>
            
            <?php }else{ $url = "lab_pending.php?screen=".($screen - 1);
  echo "<a href=\"$url\">";?><button class="btn btn-primary">Previous</button></a>            
            <?php  }
            if($screen>=$pages-1){
            ?>
            <button class="btn btn-primary disabled">Next</button>
            
            <?php }else{ $url = "lab_pending.php?screen=".($screen + 1);
  echo "<a href=\"$url\">";?><button class="btn btn-primary">Next</button></a><?php } ?>
        </div>
   
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
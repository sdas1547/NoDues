<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Home HOD | No Dues IIT Delhi</title>
        <script src="script/jquery.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="./mystyle.css" rel="stylesheet">
    </head>
    
  <?php
        include ("./header.php");
        session_start();
        include ("./dbinfo.inc");
        if (!isset($_SESSION["login_id"])){
            header ("Location: ../index.php");
            die;
        }
        else{
            $entry_number = $_SESSION["entry_no"];
        }


        $query="SELECT * from likely_grad where entry_num='".$entry_number."'";
        $res=$con->query($query);
        

        $numrow = mysqli_num_rows($res);
        if($numrow>0){
            
            while ($data = $res->fetch_assoc()){
                $status=$data['hod_approval'];
                
            }


            $query2="SELECT sum(amount) as total from dues where entry_number='".$entry_number."' group by entry_number";
            $res2=$con->query($query2);
            $amount=0;

            $numrow2 = mysqli_num_rows($res2);
        if($numrow2>0){
            
            while ($data2 = $res2->fetch_assoc()){
                $amount=$data2['total'];
                
            }
        }

 


        if($status==1)
        {
            ?>  <div class="col-sm-5 col-sm-offset-4">
                <a href="generatepdf.php"><button class="btn btn-primary">Generate Clearance pdf</button></a>
            </div>
            <?php
        }

            }
        else{
            echo "<strong>You are not authorized for no dues clearance</strong>";
        }
    ?>


    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <label>HoD Clearance Status : <?php if($status==0)
                {
                    echo "Not Approved";
                }
                else
                {
                    echo "Approved";
                }

                ?></label>

                <br>
                <br>

                <label>Pending Due Amount : <?php if($amount==0)
                {
                    echo "Rs. 0";
                }
                else
                {
                    echo "Rs. ".$amount;
                }

                ?></label>
            </div>
        </div>
    </div>



    
    <body>
       














    </body>
    
    


    <script src="js/bootstrap.min.js"></script>
    
</html>

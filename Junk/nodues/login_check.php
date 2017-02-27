<?php
session_start();
class login_check{
    
    
    
	private $connection;
	private $database;
	private $login_id ;
	private $password ;
	private $authentication_table;
	public function __construct($host,$user, $pw,$db_name,$authentication_table){
		try{
   			
   				$this->connection  = new PDO('mysql:host='.$host.'; dbname='.$db_name, $user, $pw); 
				//variable $con is further used as it pdo connection variable
        		$this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        		$this->authentication_table=$authentication_table;	
   				
   		}
   		catch(PDOException $err){

   				//header('Location: /admn/');
   				echo '<script language="javascript">';   
				echo 'alert("Cannot connect to server!  Please try again")';
				echo '</script>';
				exit();
   				//$err->getMessage() . "<br/>";
   				
   		}
	}
	//take input login details and tries to login if valid details
	function take_from_user(){
        
        
       
        
		$captcha="sampledata";
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
            
            
            $this->login_id = $this->test_input($_POST["id"]);
				$_SESSION['id'] = $_POST["id"];
                
				$this->password = $this->test_input($_POST["password"]);	
			 	$this->try_login2(); 
            
            
            
            
            
            
//			try{
//				$a =$_SESSION['captcha'];
//			$sql = $this->connection->prepare("SELECT verification FROM image WHERE id= $a");
//			$sql->execute();
//			$result = $sql->fetchAll();
//			foreach ($result as $key => $value) {
//				$captcha=$value[0];		
//				//echo $captcha;
//			}
//			}
//			catch(Exception $err){
//   				
//   			}
//			if($_POST["captcha"]==$captcha){
//			 	$this->login_id = $this->test_input($_POST["id"]);
//				$_SESSION['id'] = $_POST["id"];
//                
//				$this->password = $this->test_input($_POST["password"]);	
//			 	$this->try_login2(); 
//			}
//			else{
//                
//                
//                
//                
//                
//				header('Location: /nodues/'); 
//				session_destroy();
//				exit();
//			}
			
		}
	}
	
	//for security reasons displaying differnet thing
	function test_input($data) {
  		//$data = trim($data);
  		//$data = stripslashes($data);
  		//$data = htmlspecialchars($data);
  		return $data;
	}
	function try_login2(){
		
		
			$result= array();
			$encrypt_pass= $this->password;
			try{
$sql = $this->connection->prepare("SELECT * FROM users WHERE userid='$this->login_id' and password='$this->password'");
			$sql->execute();
			$result = $sql->fetchAll();
            
			//die();
			}
			catch(Exception $err){
				
				exit();
   				
   				
   			}						
			if(sizeof($result)==1){
				
				if(isset($_SESSION['id']) )	{
					$_SESSION['timestamp']=time();
                    foreach($result as $row=>$link)
                        
            {
                $_SESSION['entry']=$link['userid'];
                        $_SESSION['role']=$link['role'];
            }
                    if($_SESSION['role']==0)
                    {
					header('Location: /nodues/welcome.php'); }
                    else if($_SESSION['role']==1)
                    {
                        header('Location: /nodues/welcome2.php');
                    }
                        
					exit();
				}
				
			}
			else{
				
				
              
				
   	
				header('Location: /nodues/');
				session_destroy();
				die();	
				exit();
			}	

		
			
	}
	

}
	$class_object=new login_check('localhost','root','','nodues','users');
	$class_object->take_from_user();
?>
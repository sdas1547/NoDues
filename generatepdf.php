<?php
require('pdfgen/fpdf.php'); 
require('dbinfo.inc');



session_start();

$entry=$_SESSION['entry_no'];
$name="";
$programme="";
$department="";




$check="select * from likely_grad where entry_num='".$entry."'";
$check_res=$con->query($check);
$number = mysqli_num_rows($check_res);
        if($number>0){
            
            while ($data1 = $check_res->fetch_assoc()){
                $status=$data1['hod_approval'];
               
            }
        }


if($status==1){
$query="select * from student_table join user_table on student_table.entry_number=user_table.uID where entry_number='".$entry."'";
$result=$con->query($query);
  $numrow = mysqli_num_rows($result);
        if($numrow>0){
            
            while ($data = $result->fetch_assoc()){
                $name=$data['name'];
                $programme=$data['programme'];
                $department=$data['department_code'];
                
            }
        }
        
 


class PDF extends FPDF {
 
function Header() {
    $this->SetFont('Times','',12);
    $this->SetY(0.25);
    $this->Cell(0, .25, "No Dues Portal ".$this->PageNo(), 'T', 2, "R");
    //reset Y
    $this->SetY(1);
}
 

 
}
 
$pdf=new PDF("P","in","Letter");
 
$pdf->SetMargins(1,1,1);
 
$pdf->AddPage();
$pdf->SetFont('Times','',12);
 



  
$pdf->SetFont('Times','',42);

$pdf->Write(0.5, "NO Dues Clearance Form");
  
$pdf->SetLineWidth(1);
$pdf->Ln(2);

$pdf->SetFont('Arial','B',25);

$pdf->Write(0.5, "Entry Number : ");
$pdf->Write(0.5, $entry."\n");

$pdf->Write(0.5, "Name : ");
$pdf->Write(0.5, $name."\n");

$pdf->Write(0.5, "Department : ");
$pdf->Write(0.5, $department."\n");

$pdf->Write(0.5, "Programme : ");
$pdf->Write(0.5, $programme."\n");

$pdf->Output();


}

else
{
		
		echo "NOT ALLOWED";
}
?>
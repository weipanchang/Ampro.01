<?php
require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}

?>
<!DOCTYPE HTML>
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>

<?php
   include("Ampro_station_info.php");
   require_once("connMysql.php");
   function clean($string) {
       $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
       return preg_replace('/[^A-Za-z0-9#!*&?!@%\-]/', '', $string); // Removes special chars.
   }
?>

<?php
$barcode = "";
$comment = "";
$barcodeerror = "";
$commenterror = "";
$error=0;
$operator = $_SESSION['username'];



function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
<h1 style="text-align:center; color:blue; text-decoration: underline";>Ampro System PCB Barcode Associate Page</h1>
<h3 style="text-align:center; color:blue; text-decoration: underline";> <?php echo $station_type; echo " Station    "; echo $line_number; ?></php?></h3>
<h4 style="text-align:center; color:blue;";> <?php echo "Name: "; echo $operator;?></php?></h4>

<form method = "post" action="">
   <p><span class="error">* Please Scan the Barcode *</span></p>
   <div style="text-align:center"> 
        <ul>
        <p>AmPro System Barcode: &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp SuperMicro   Barcode:</p>
        <p>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp<input type="text" style="text-align:center;color: #FF0000; font-size: large;" name="Ampro_barcode1" value="<?php echo "";?>"> &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
        <input type="text" style="text-align:center;color: #FF0000; font-size: large;" name="SMC_barcode1" value="<?php echo "";?>">

        &nbsp &nbsp &nbsp<input type="submit" name="submit8" style="text-align:center;color: #FF0000; font-size: large;" value="Register this Barcode"> </p>
        </ul>
    </div>
   <!--   Barcode:  <input type="text" name="barcode" value="">-->
      <span class="error"> <?php echo $barcodeerror;?></span>
   <br><br>
   <br><br>
</form>

<?php

if (!isset($_POST['Ampro_barcode1'])) 
    {
    $Ampro_barcode1 = "";
    }
else 
    {
    $Ampro_barcode1 = $_POST['Ampro_barcode1'];
    }

if (!isset($_POST['SMC_barcode1'])) 
    {
    $SMC_barcode1 = "";
    }
else 
    {
    $SMC_barcode1 = $_POST['SMC_barcode1'];
    }
    
if (isset($_POST['submit8'])) {
    $operator = $_SESSION['username'];
    //$Ampro_barcdoe1 = $_POST['Ampro_barcode1'];
    //$SMC_barcdoe1 = $_POST['SMC_barcode1'];
    if ($Ampro_barcode1 !="" and $SMC_barcode1 !="") {
    $con=mysql_connect($db_host,$db_username,$db_password);
    mysql_select_db($db_name);
    $sql = "SELECT `model` FROM `PCB_Tracking` WHERE `PCB` = '$Ampro_barcode1' and `note` = 'New PCB Record Created !'";
    $result=mysql_query($sql, $con);
    $row=mysql_fetch_array($result);
     if(! $result ) {
        die('Cannot Find Model Name:     ' . mysql_error());
     }
     else
     {
        $model = $row['model'];
     }
    
//    $result=mysql_query($sql, $con);
    $sql = "INSERT INTO `PCB_Barcode`(`AMP_barcode`, `SMC_Barcode`, `model`,`operator`) VALUES('$Ampro_barcode1','$SMC_barcode1', '$model', '$operator')";
    
    $result=mysql_query($sql, $con);
         if(! $result ) {
            die('Could not enter data:     ' . mysql_error());
         }
         else
         { 
            echo "<br>";
            echo "<br>";
            echo "Barcode Entered Successfully!\n";

         }
    $sql = "INSERT INTO `PCB_Tracking`(`PCB`, `model`, `top`, `bottom`,`line`, `station`, `status`,
        `scrapped`, `operator`, `note`) VALUES('$Ampro_barcode1', '$model', '1', '1','$line_number','$station_type',0,0,'$operator', 'SMC Barcode Associated')";
    $result=mysql_query($sql, $con);
         if(! $result ) {
            die('Could not enter data:     ' . mysql_error());
         }
         else
         { 
            echo "<br>";
            echo "<br>";
            echo "Tracking Record Updated Successfully!\n";
         }
    mysql_close($con);
    }
    else {
        echo "The Barcode entry is empty, both Barcodes are needed! ";
    }
}
?>
<form method="post" action="Ampro_Barcode_Matching.php" >
    <input type="submit" name="submit" style="color: #FF0000; font-size: larger;" value="Next">
</form>

<br><br>
<br><br>

<p><a href='logout.php'>Logout</a></p>
</body>
</html>
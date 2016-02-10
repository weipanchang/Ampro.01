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
    
    function IsChecked($chkname,$value)
    {
        if(!empty($_POST[$chkname]))
        {
            foreach($_POST[$chkname] as $chkval)
            {
                if($chkval == $value)
                {
                    return true;
                }
            }
        }
        return false;
    }

    if (isset($_POST['submit2'])) {
        $barcode=$_POST['barcode'];
        $operator=$_POST['name'];
        $model=$_POST['model'];
        $note = htmlspecialchars($_POST['note']);
        $note=clean($note);

        
      if(isset($_POST['top'])){
         $top = 1;
      }
      else {
         $top = 0;
      }
      
      if(isset($_POST['bottom'])) {
         $bottom = 1;
      }
      else {
         $bottom = 0;
      }

      if (!empty($_POST['Issue'])) {
         $checked_count = count($_POST['Issue']);
         if ($note != "") {
            $note = $note . "<br>";
         }
         $note = $note . "This PCB has the following ".$checked_count. " issue(s): "."<br>";
         foreach($_POST['Issue'] as $selected) {
            $note = $note . $selected . ".<br>";
         }
      }   
      
      if(isset($_POST['Scrapped'])){
  
          $sql = "INSERT INTO `PCB_Tracking`(`PCB`,`model`,`top`,`bottom`,`line`, `station`, `status`,
          `scrapped`,`operator`, `note`) VALUES('$barcode','$model','$top','$bottom','$line_number','$station_type',0,1,'$operator','$note')";
      }
      else {
  
          $sql = "INSERT INTO `PCB_Tracking`(`PCB`,`model`,`top`,`bottom`,`line`, `station`, `status`,
          `scrapped`,`operator`, `note`) VALUES('$barcode','$model','$top','$bottom','$line_number','$station_type',0,0,'$operator','$note')";
      }   
      $con=mysql_connect($db_host,$db_username,$db_password);
            
      mysql_select_db($db_name);
      $result=mysql_query($sql, $con);
      //mysql_query($sql) or die ('error: ' . mysql_error());
      //header("location:Ampro_php_form3.php"); 
    }
?>

<?php
$barcode = "";
$comment = "";
$barcodeerror = "";
$commenterror = "";
$error=0;
$operator = $_SESSION['username'];
if ($station_type=='AOI') {
   $model = $_POST['model'];
}
//echo $operator;
//echo $model;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["barcode"])) {
     $barcodeerror = "Barcode is required";
     $error=1;
   }
   elseif (strlen($_POST["barcode"]) != 12) {
     $barcodeerror = "Invalid Barcode. Please rescan!";
     $error=1;
   }
   else {
     $barcode = test_input($_POST["barcode"]);
     $error=0;
   }
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
<h1 style="text-align:center; color:blue; text-decoration: underline";>Ampro System PCB Check in/out</h1>
<h3 style="text-align:center; color:blue; text-decoration: underline";> <?php echo $station_type; echo " Station    "; echo $line_number; ?></php?></h3>
<h4 style="text-align:center; color:blue;";> <?php echo "Name: "; echo $operator;?></php?></h4>
<?php
   if ($station_type =="AOI") {
?>
   <h4 style="text-align:center; color:blue;";> <?php echo "Model: "; echo $model;?></php?></h4>
<?php
   }
?>

<form method="post" action="Ampro_startup.php" >
   <h5 style="text-align:right; color:red; text-decoration: underline";>Change Model, Please Click Back &nbsp;</h5>
   <div style="text-align:right">  
      <input type="submit" name="submit" style="text-align:center;color: #FF0000; font-size: medium;" value="Back">
   </div> 
</form>
<form method = "post" action="">
   <p><span class="error">* Please Scan the Barcode *</span></p>
   <?php
      if (isset($_POST['submit2'])) {
   ?>
         Barcode:  <input type="text" name="barcode" value="<?php echo "";?>">
   <?php
      }
      else {
   ?>   
      Barcode:  <input type="text" name="barcode" value="<?php echo $barcode;?>">
   <?php
   }
   ?>
      <input type="hidden" name="name" value="<?php echo $operator;?>">
      <?php
         if ($station_type =="AOI") {
      ?>
            <input type="hidden" name="model" value="<?php echo $model;?>">
      <?php
         }
      ?>
   <!--   Barcode:  <input type="text" name="barcode" value="">-->
      <span class="error"> <?php echo $barcodeerror;?></span>
   <br><br>
   <br><br>
</form>

<?php
if (isset($_POST['submit2'])) {
   $barcode = "";
}
echo "<h3>Your Input:</h3>";

echo $barcode;
echo "<br>";
//echo $comment;
echo "<br>";

$con=mysql_connect($db_host,$db_username,$db_password);
mysql_select_db($db_name);
$rowcount=0;
$sql = "SELECT * FROM `PCB_Tracking` WHERE PCB='$barcode'";

if (($barcode != "") and ($error == 0)) {
   $result=mysql_query($sql, $con);
   $rowcount=mysql_num_rows($result);
}

if ( $rowcount == 0) {
   if (($station_type =="AOI") and ($error == 0) and ($barcode != "")) {
      $sql = "INSERT INTO `PCB_Tracking`(`PCB`, `model`,`line`, `station`, `status`,
      `scrapped`, `operator`) VALUES('$barcode','$model','$line_number','$station_type',0,0,'$operator')";
      mysql_select_db($db_name);
      $result=mysql_query($sql, $con);
         if(! $result ) {
            die('Could not enter data: ' . mysql_error());
         }
         else {
         echo "<br>";
         echo "<br>";
         echo "Create new PCB record successfully, Please Check In!\n";
         echo "<br>";
         echo "<br>";
         }
      mysql_close($con);
?>
      <form method="post" action="Ampro_process.php" >
         <input type="hidden" name="barcode"
           value="<?php echo $_POST['barcode']; ?>">
         <input type="hidden" name="name"
            value="<?php echo  $operator; ?>">
<?php
         if ($station_type =="AOI") {
?>
            <input type="hidden" name="model" value="<?php echo $model;?>">
<?php
         }
?>
         <input type="submit" name="submit" value="Check In">
      </form>
<?php
   }
   elseif (($error==0) and ($rowcount == 0) and ($barcode != "") and ($station_type !="AOI")) {
      echo "<br>";
      echo "Barcode is not in database. Please send this PCB to AOI Station";
      echo "<br>";
   }

}
else {
   mysql_close($con);
   if ($error==0) {
?>
<form method="post" action="Ampro_process.php" >
   <input type="hidden" name="barcode"
     value="<?php echo $_POST['barcode']; ?>">
   <input type="hidden" name="name"
        value="<?php echo  $operator; ?>">
   <?php
         if ($station_type =="AOI") {
   ?>
            <input type="hidden" name="model" value="<?php echo $model;?>">
   <?php
         }
   ?>
   <input type="submit" name="submit" style="color: #FF0000; font-size: larger;" value="Check In">
</form>
<?php
   }
}
?>

</body>
</html>
<?PHP
require_once("./include/membersite_config.php");
$operator = $fgmembersite->UserFullName();

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
   // define variables and set to empty values
   $operator = "";
   $model = "";
   $comment = "";
   $operatorerror = "Your name is missing";
   $modelerror = "";
   $error=0;
   
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
      if (empty($_POST["operator"])) {
        $barcodeerror = "Name is required";
        $error=1;
      }
      else {
        $barcode = test_input($_POST["operator"]);
        $error=0;
      }
   }
   
   function test_input($data) {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
   }
   include("Ampro_station_info.php");
   require_once("connMysql.php");
?>

<h1 style="text-align:center; color:blue; text-decoration: underline";>Ampro System PCB Check in/out</h1>
<h3 style="text-align:center; color:blue; text-decoration: underline";> <?php echo $station_type; echo " Station    "; echo $line_number; ?></php?></h3>

   Welcome back <?= $fgmembersite->UserFullName(); ?>!
<?php
      $con=mysql_connect($db_host,$db_username,$db_password);
      mysql_select_db($db_name);
      $operator = $fgmembersite->UserFullName();

      if ($station_type=='AOI') {
?>
   <form name="myform3" method="POST" action="">
   <p><span class="error">* Please select the Model number *</span></p>
   <div style="text-align:left"> 
        <ul>
<?php
         $sql = "SELECT * FROM `PCB_Model` group by model order by model";
         $result=mysql_query($sql);
         echo "<select name='model' size=8>";
         while ($row= mysql_fetch_array($result) ) {
            echo "<option value='" . $row['model'] ."'>" . $row['model'] ."</option>";
         }
         echo "</select>";
?>
        <input type="submit" name="submit3" style="color: #FF0000; font-size: larger;" value="Select the Model and Click here">
        
<?php
        if ((isset($_POST['submit3']) and (isset($_POST['model'])))) {
?>            

     <form name="myform4" method="POST" action="">
<?php                
            $model = $_POST['model'];
            $sql = "SELECT `revision` FROM `PCB_Model` where `model` = '$model' order by revision";
            $result=mysql_query($sql);
            echo "<select name='revision' size=8>";
            while ($row= mysql_fetch_array($result))   {
            echo "<option value='" . $row['revision'] ."'>" . $row['revision'] ."</option>";
            }
            echo "</select>";
?>
        <input type="hidden" name="model" value="<?php echo  $model;?>">
        <input type="submit" name="submit4" style="color: #FF0000; font-size: larger;" value="Select the Revsion and Click here">
<?php
        }
      }
?>
        </ul>
    </div>
    </form>

   <br><br>
<?php
   if (($station_type=='AOI') and (isset($_POST['submit4']))) {
      $revision=test_input($_POST['revision']);
      $model=test_input($_POST['model']);
      $model=$model." R".$revision;
      echo "<h4>Your Login ID is:  $operator </h4>";
      echo "<h4>Model:  $model </h4>";
?>
   <form method="post" action="Ampro_php_form3.php" >
   <input type="hidden" name="name"
     value="<?php echo  $operator; ?>">
   <input type="hidden" name="model"
     value="<?php echo  $model; ?>">
   <input type="submit" name="submit5" style="color: #FF0000; font-size: larger;" value="Next">
   </form>
<?php   
   }
   elseif (isset($_POST['submit4'])) {
?>   
   <form method="post" action="Ampro_php_form3.php" >
      <input type="hidden" name="model"
        value="<?php echo  $model; ?>">
      <input type="submit" name="submit5" style="color: #FF0000; font-size: larger;" value="Next">
   </form>
<?php
   }
   echo "<br>";
   mysql_close($con);
?>

<p><a href='login-home.php'>Back</a></p>
<p><a href='logout.php'>Logout</a></p>


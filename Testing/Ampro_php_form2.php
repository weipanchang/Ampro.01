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
$barcode = "";
$comment = "";
$barcodeerror = "";
$commenterror = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   if (empty($_POST["barcode"])) {
     $barcodeerror = "Barcode is required";
   } else {
     $barcode = test_input($_POST["barcode"]);
   }

}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>

<h2>Ampro System PCB Check in/out</h2>
<form method = "post" action="">
<p><span class="error">* Please Scan the Barcode *</span></p>
   Barcode:  <input type="text" name="barcode" value="<?php echo $barcode;?>">
   <span class="error"> <?php echo $barcodeerror;?></span>
   <br><br>
   <br><br>
</form>

<?php
echo "<h2>Your Input:</h2>";
echo $barcode;
echo "<br>";
echo $comment;
echo "<br>";

include("Ampro_station_info.php");
require_once("connMysql.php");

$con=mysql_connect($db_host,$db_username,$db_password);
mysql_select_db($db_name);
$rowcount=0;
$sql = "SELECT COUNT(*) FROM `PCB_Tracking` WHERE PCB=$barcode";

if ($barcode != "") {
   $result=mysql_query($sql);
   $data=mysql_fetch_assoc($result);
   $rowcount= mysql_result($result, 0);
}

if ( $rowcount == 0) {
   if ($station_type =="AOI") {
      $sql = "INSERT INTO `PCB_Tracking`(`PCB`,`line`, `station`, `status`,
      `scrapped`) VALUES('$barcode', '$line_number','$station_type',1,0)";
      mysql_select_db($db_name);
      $result=mysql_query($sql, $con);
         if(! $result ) {
            die('Could not enter data: ' . mysql_error());
         }
         else {
         echo "<br>";
         echo "<br>";
         echo "Create new PCB record successfully, Please Check In!\n";
         }
      mysql_close($con);
?>
   <form method="post" action="Ampro_process.php" >
      <input type="hidden" name="barcode"
        value="<?php echo $_POST['barcode']; ?>">
      <input type="submit" name="submit" value="Check In">
   </form>
<?php
   }
   else {
      echo "<br>";
      echo "Barcode is not in database. Please send this PCB to AOI Station";
      echo "<br>";
   }
}
else {
   mysql_close($con);
?>
      <form method="post" action="Ampro_process.php" >
      <input type="hidden" name="barcode"
        value="<?php echo $_POST['barcode']; ?>">
      <input type="submit" name="submit" value="Check In">
   </form>
<?php
}
?>

</body>
</html>


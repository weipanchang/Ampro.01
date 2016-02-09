<?php
require_once("connMysql.php");


$connect = @mysql_connect($db_host, $db_username, $db_password);
if (!$connect) { echo 'Server error. Please try again sometime later.  '; echo "<br>";echo "<br>";}

mysql_select_db($db_name) or die("Could not connect to the database '$db_name'");
$test_query = "SHOW TABLES FROM $db_name";
$result = mysql_query($test_query);
$tblCnt = 0;
while($tbl = mysql_fetch_array($result)) {
  $tblCnt++;
}
if (!$tblCnt) {
  echo "There are no tables<br />\n";
} else {
  echo "Database connect successfully<br />\n";
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
<h3 style="text-align:center; color:blue; text-decoration: underline" >Ampro PCB Station Configuration</h3>
<?php


if (empty($_POST["station"])) {
  $station = "";
} else {
  $station = test_input($_POST["station"]);
}

if (empty($_POST["line"])) {
  $line = "";
} else {
  $line = test_input($_POST["line"]);
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<form action="" method="post">
<select name="station" >
<option value=""><?php echo "Select Station Type"; ?></option>

    <option value="AOI">AOI</option>
    <option value="Testing">Testing</option>
    <option value="QA">QA</option>
    <option value="Repair">Repair</option>
    <option value="Label">Label</option>
    <option value="Shipping">Shipping</option>

</select>
<p>&nbsp;</p>
<p>&nbsp;</p>
<select name="line" >
<option value=""><?php echo "Select Line number"; ?></option>

    <option value="1">1</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">5</option>

</select>

<p>&nbsp;</p>
<p>&nbsp;</p>

<input type="submit" name="Submit" style="color: #FF0000; font-size: larger;" id="stationsubmit" value="Submit" />
</form>
<?php

if(isset($_POST["station"])) $station = $_POST["station"];
if(isset($_POST["line"])) $line = $_POST["line"]; 
if ($station <> ''){
    $fp  = 'Ampro_station_info.php';
    $str='<?php'."\n".'$station_type='.'\''.$station.'\''.';'."\n".'$line_number='.$line.';'."\n".'?>'."\n";
    file_put_contents($fp, $str);
} 
?>

</body>
</html>

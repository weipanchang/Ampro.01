<?php

require_once("./include/membersite_config.php");
session_start();
if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
      <title>Search with Ampro Barcode Page</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css">
</head>
<?php
   include("Ampro_station_info.php");
   require_once("connMysql.php");
   function clean($string) {
       $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
       return preg_replace('/[^A-Za-z0-9#!*&?!@%\-]/', '', $string); // Removes special chars.
   }
?>

<h2>Search with Ampro Barcode</h2>
<form method="post" action="Search_with_Ampro_Barcode.php">
<table> 
<tr>
<td style="color:blue" >Enter Ampro System Barcode: &nbsp; &nbsp; &nbsp;</td>
<td><input type="text" name="AMP_barcode" size="25" value=""></td>
</tr>

<!--<tr>
<td style="color:blue" >Enter SuperMicro Barcode</td>
<td><input type="text" name="SMC_barcode" size="12" value=""></td>
</tr>-->
<td ><input type="submit" value="Submit" style="background-color:#0000ff; color:#fff;" ></td>
</table>
</form>
<?php


$con=mysql_connect($db_host,$db_username,$db_password);
mysql_select_db($db_name);
if (!isset($_POST['AMP_barcode'])) 
    {
    $AMP_barcode = null;
    }
else 
    {
    $AMP_barcode = $_POST['AMP_barcode'];
    }

if (!isset($_POST['SMC_barcode'])) 
    {
    $SMC_barcode = null;
    }
else 
    {
    $SMC_barcode = $_POST['SMC_barcode']; 
    }

if (($_POST) && ( strlen($AMP_barcode) >= 4 )) {

    echo "<h2> PCB Barcode Table  : </h2>";
    echo "<br>";
    echo "<table width='1300' border='5'; style='border-collapse: collapse;border-color: silver;'>";  
    echo "<tr style='font-weight: bold;'>";  
    echo "<td width=3%' align='center'>Rec</td>";
    echo "<td width='1%' align='center'>AMP Barcode</td><td width='4%' align='center'>HMC Barcode</td>";
    echo "<td width='15%' align='center'>Shipped</td>";  
    echo "<td width='15%' align='center'>Operator</td>";  
    echo "<td width='15%' align='center'>Updater</td>";
    echo "<td width='3%' align='center'>Comment</td><td width='8%' align='center'>Date</td>";
    echo "<td width='8%' align='center'>Time</td></tr>";

    $sql = "SELECT * FROM `PCB_Barcode` WHERE `AMP_barcode` = '$AMP_barcode' ORDER BY `time` DESC";
    $result=mysql_query($sql, $con);
    while($row=mysql_fetch_array($result))  {
        $SMC_barcode =  $row['SMC_barcode'];
        $rec_number=$row['recnumber'];
        
        if ($row['shipping_status'] == 1){
            $shipped = "Yes";
            $shipped_value = 1;
        }
        else {
            $shipped= "No";
            $shipped_value = 0;
        }
        echo "<tr style='font-weight: bold;'>"; 
        echo "<tr>";  
        echo "<td align='center' width='3%'>" . $row['recnumber'] . "</td>";  
        echo "<td align='center' width='5%'>" . $row['AMP_barcode'] . "</td>";
        echo "<td align='center' width='1%'>" . $row['SMC_barcode'] . "</td>";
        echo "<td align='center' width='1%'>" . $shipped . "</td>";
        echo "<td align='center' width='4%'>" . $row['operator'] . "</td>";
        echo "<td align='center' width='15%'>" . $row['updater'] . "</td>";
        echo "<td align='left' width='15%'>" . $row['comment'] . "</td>";  
        echo "<td align='center' width='8%'>" . $row['date'] . "</td>";
        echo "<td align='center' width='8%'>" . $row['time'] . "</td>";
        
    echo "</tr>";
    }
    echo "</table>";

    echo "   ";
}
mysql_close($con);

?> 
<p>&nbsp;</p>
<p>&nbsp;</p>
<div>
<ul>
<HR WIDTH="100%" COLOR="#6699FF" SIZE="6"AMP000000001>
</ul>
</div>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

<?php

if (!isset($_POST['AMP_barcode'])) 
    {
    $AMP_barcode = null;
    }
else 
    {
    $AMP_barcode = $_POST['AMP_barcode'];
    }

?>

<form method = "post" action="">

    <input type="hidden" name="recnumber" value="<?php echo $rec_number;?>
   <div style="text-align:center; font-size: large;"> 
        <ul>
        <p><pre>                AmPro System Barcode:                   SuperMicro Barcode:                           Shipped</pre></p>
        <p><pre> 
                   <?php echo "<span style='color: red; font-size: 15pt';>$AMP_barcode</span>";?>                 <input type="text" style="text-align:center;color: #FF0000; font-size: large;" name="SMC_barcode" value="<?php echo $SMC_barcode;?>">                          <input type="checkbox" name="shipped" value="" checked>         <input type="submit" name="submit8" style="text-align:center;color: #FF0000; font-size: large;" value="Update">
        </pre></p>

        </ul>

    </div>

   <br><br>
   <br><br>
</form>

<?php
if (isset($_POST['submit8'])) {
    $con=mysql_connect($db_host,$db_username,$db_password);
    mysql_select_db($db_name);
    $operator = $fgmembersite->UserFullName();

    //$Ampro_barcode = $_POST['Ampro_barcode'];
    $rec_number = $_POST['recnumber'];
    $SMC_barcode = $_POST['SMC_barcode'];
    $shipped_value = (isset($_POST['shipped'])) ? 1 : 0;

    $sql = "UPDATE `PCB_Barcode` SET `SMC_barcode` = '$SMC_barcode', `shipping_status` ='$shipped_value', `updater`='$operator' WHERE `recnumber`='$rec_number'";

    $result=mysql_query($sql, $con);
    if(! $result ) {
        die('Could not enter data:     ' . mysql_error());
    }
    else { 
        echo "<br>";
        echo "<br>";
        echo "Barcode and Shpping Status Updated Successfully!\n";

    }

}
?>

<p>
Logged in as: <?= $fgmembersite->UserFullName() ?>
</p>
<p>
<a href='Ampro_barcode_reassociate.php'>Back</a>
</p>
</div>
</body>
</html>
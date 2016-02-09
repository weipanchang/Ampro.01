<?PHP
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
    if ($_POST['barcode'] == null ) {
        header("location:Ampro_php_form3.php"); 
    }
    
    $barcode = $_POST['barcode'];
    $operator = $_POST['name'];
    if ($station_type =="AOI") {
        $model = $_POST['model'];
    }
    
    //$issueinfo = $_POST['issueinfo'];
    include("Ampro_station_info.php");
    require_once("connMysql.php");
?>
<h4 style="text-align:center; color:blue; text-decoration: underline";> <?php echo "Ampro System PCB Check in/out"; ?></php></h4>
<h5 style="text-align:center; color:blue; text-decoration: underline";> <?php echo $station_type; echo " Station    "; echo $line_number; ?></php?></h5><h5 style="text-align:center; color:blue;";> <?php echo "Name: "; echo $operator;?></php?></h5>
<h4>
<?php
    //echo "You currently are processing PCB - ";
    //echo $barcode;
    //echo "<br>";
?>
</h4>

<?php
    $con=mysql_connect($db_host,$db_username,$db_password);
    mysql_select_db($db_name);
    $sql = "SELECT * FROM `PCB_Tracking` WHERE `PCB`='$barcode' order by time DESC limit 15";
    $result_1=mysql_query($sql);
    $row=mysql_fetch_array($result_1);
    $top = $row['top'];
    $bottom = $row['bottom'];
    if ($station_type !=="AOI") {
        $model = $row['model'];
    }
    if (!(($row['line'] == $line_number) and ($row['station'] == $station_type) and ($row['status'] == 1))) {
        $sql = "INSERT INTO `PCB_Tracking`(`PCB`, `model`, `top`, `bottom`,`line`, `station`, `status`,
        `scrapped`, `operator`, `note`) VALUES('$barcode', '$model', '$top', '$bottom','$line_number','$station_type',1,0,'$operator', 'Checked in')";
        $result=mysql_query($sql, $con);
    }
?>

<h5 style="text-align:center; color:blue;";> <?php echo "Model: " . $row['model'];?></php?></h5>

<?php
    echo "You currently are processing PCB - ";
    echo $barcode;
    echo "<br>";
    echo "<br>";
    echo "Message History: ";
?>
<h5 style="text-align:left; color:red;">Please Use F5 key to show all message history.....</h5>


<?php
    while($row=mysql_fetch_array($result_1)) {
?>
<h5>
<?php
        if (($row['note'] != "Checked in") and ($row['note'] != "")) {
            echo $row['station'];
            echo " ";
            echo $row['line']. ": ";
            //echo "<br>";
            echo "<font color='blue'>".$row['note']."</font>";
            echo "<br>";
        }
    }
?>
</h5>
    
<?php
    $rec_array = array();
//    $rec_array=[];
    echo "Issue Log: ";
    echo "<br>";
    echo "<table width='1000' border='5'; style='border-collapse: collapse;border-color: silver;'>";  
    echo "<tr style='font-weight: bold;'>";  
    echo "<td width='5%' align='center'>Rec</td><td width='15%' align='center'>PCB Number</td><td width='5%' align='center'>Line</td><td width='10%' align='center'>Station</td><td width='38%' align='center'>Issue</td>  ";  
    echo "<td width='5%' align='center'>Fixed</td><td width='25%' align='center'>Time</td></tr>";
    $sql = "SELECT * FROM `PCB_Issue_Tracking` WHERE `PCB` = '$barcode' order by create_time DESC";
    $result=mysql_query($sql, $con);
    while($row=mysql_fetch_array($result))  {
        array_push($rec_array, $row['recnumber']);
        echo "<tr style='font-weight: bold;'>"; 
        echo "<tr>";  
        echo "<td align='center' width='5%'>" . $row['recnumber'] . "</td>";  
        echo "<td align='center' width='15%'>" . $row['PCB'] . "</td>";
        echo "<td align='center' width='5%'>" . $row['line'] . "</td>";
        echo "<td align='center' width='10%'>" . $row['station'] . "</td>";
        echo "<td align='left' width='38%'>" . $row['Issue_log'] . "</td>";  
        echo "<td align='center' width='5%'>" . "No" . "</td>";  
        echo "<td align='center' width='25%'>" . $row['create_time'] . "</td>"; 
    echo "</tr>";
    }
    echo "</table>";  
    echo "   ";
    mysql_close($con);
?>

<?php
  if ($station_type=='Repair') {
     $con=mysql_connect($db_host,$db_username,$db_password);
     mysql_select_db($db_name);
     $sql = "SELECT `Issue` FROM `PCB_Issue` WHERE `station` = '$station_type'  ";
     $result=mysql_query($sql, $con);
?>

<form name="repairform" action="Ampro_process.php" method="POST">
    <p>
    <h5 style="text-align:left; color:red;">Please Input The Issue Rec Number You Fixed, Click at "Update this issue", and Refresh the Screen! (F5 key).....</h5>
    </p>
    <br>
    Comment: <textarea cols=70 rows=3 name="r_comment"  style="color:#CD2200" value=""></textarea>
    Issue Rec Number:  <input type="text" name="update" value="<?php echo "";?>">
    <input type="hidden" name="barcode" value="<?php echo  $barcode;?>">
    <input type="hidden" name="name" value="<?php echo  $operator;?>">
    <input type="hidden" name="model" value="<?php echo $model;?>">
    <input type="hidden" name="issueinfo" value="<?php echo $issueinfo;?>">
    <input type="submit" name="submit7" style="color: #FF0000; font-size: larger;" value="Update this issue">
</form>

<?php
    if (isset($_POST['submit7'])) {
        $rec_number = $_POST['update'];
        $r_comment = $_POST['r_comment'];
        if (in_array( $rec_number, $rec_array)) {
            $sql = "UPDATE `PCB_Issue_Tracking` SET `fixed`=1,`R_Person`='$operator', `r_comment` = '$r_comment' WHERE `recnumber` = '$rec_number'";
            $result=mysql_query($sql, $con);
            echo "Issue ". $rec_number ." is marked as fixed.";
        }
        else {
            echo "No Issue is marked as fixed";
        }
    }
    mysql_close($con);
  }
?>

<?php
  if (($station_type=='AOI') or ($station_type=='Testing') or ($station_type=='QA') or ($station_type=='Label') or ($station_type=='Shipping')) {
     $con=mysql_connect($db_host,$db_username,$db_password);
     mysql_select_db($db_name);
     $sql = "SELECT `Issue` FROM `PCB_Issue` WHERE `station` = '$station_type'  ";
     $result=mysql_query($sql, $con);
?>

<form name="issueform" action="Ampro_process.php" method="POST">
    <p>
    <h5 style="text-align:left; color:red;">Enter New Issue Here, Then click at Add This Issue! (F5 to Refresh the Screen).....</h5>
    </p>
    <div align="left">
<?php
    echo "<select name='issue'>";
        while ($row= mysql_fetch_array($result) ) {
            echo "<option value='" . $row['Issue'] ."'>" . $row['Issue'] ."</option>";
        }
    echo "</select>";
    echo " On ";
?>
    <select name="topbottom">
        <option value="top">Top</option>
        <option value="bottom">Bottom</option>
    </select>
    <br>
    <textarea cols=70 rows=3 name="location"  style="color:#CD2200" value="">Location: </textarea>
    <input type="hidden" name="barcode" value="<?php echo  $barcode;?>">
    <input type="hidden" name="name" value="<?php echo  $operator;?>">
    <input type="hidden" name="model" value="<?php echo $model;?>">
    <?php
    if (isset($_POST['issueinfo'])) {
    ?>
    <input type="hidden" name="issueinfo" value="<?php echo $issueinfo;?>">
    <?php
    }
    ?>
    <input type="submit" name="submit6" style="color: #FF0000; font-size: larger;" value="Add this issue">
    </div>   
</form>

<?php
    if (isset($_POST['submit6'])) {
        $issueinfo = $_POST['issue']. " on " .$_POST['topbottom']. " in ".  $_POST['location'];
        if (!($issueinfo === ' on top in ')) {
            if ((substr($issueinfo, 0, 11)) == ' on top in '){
                $issueinfo = substr($issueinfo, 11);
            }
            
            $sql="SELECT `Issue_log`, `create_time` FROM `PCB_Issue_Tracking` where `PCB` = '$barcode' order by `create_time` DESC";
            $result = mysql_query($sql, $con);
            $row= mysql_fetch_array($result); 
            $dd = new DateTime();
            //echo $dd->format('Y-m-d H:i:s');
            //echo "<br>";
            $dd= ($dd->modify("-20 minutes"));
            //echo $dd->format('Y-m-d H:i:s');
            //echo "<br>";
            //echo $row['create_time'];
            //echo "<br>";
            //echo $row['Issue_log'];
            //echo "<br>";
            if (!($issueinfo == $row['Issue_log'] and $row['create_time'] <= $dd)) {
            $sql = "INSERT INTO `PCB_Issue_Tracking`(`PCB`, `Issue_log`, `station`, `line`, `operator`) VALUES('$barcode','$issueinfo','$station_type','$line_number', '$operator')";
            $result=mysql_query($sql, $con);
 
            echo "New issue Added:---- " . $issueinfo;
            }
        }
    }
    mysql_close($con);
  }
?>


<form name="testform" action="Ampro_php_form3.php" method="POST">
    <br>
    <br>
    <br>
    <br>
    <div align="center"><br>
    <?php
 
        if ($top == 1){
    ?>
            <input type="checkbox" name="top" value=$top checked> Top is done!
    <?php
        }
        else {
    ?>
            <input type="checkbox" name="top" value=$top > Top is done!
    <?php
        }
        if ($bottom == 1){
    ?>      <input type="checkbox" name="bottom" value=$bottom checked> Bottom is done!
    <?php
        }
        else {
    ?>
            <input type="checkbox" name="bottom" value=$bottom > Bottom is done!  
    <?php
        }
    ?>
    <br>
    </div>
    <br>
    <br>
    (Please limit to  100 characters):<textarea name="note" id="note" cols=70 rows=2 style="color: #FF0000; font-size: larger;"></textarea>
    <br>
    <br>
    <input type="checkbox" name="Scrapped" value="Scrapped"> Scrapped this PCB <br>
    <br>
    <br>
    <input type="hidden" name="barcode" value="<?php echo  $barcode;?>">
    <input type="hidden" name="name" value="<?php echo  $operator;?>">
    <input type="hidden" name="model" value="<?php echo $model;?>">
    
    <input type="submit" name="submit2" style="color: #FF0000; font-size: larger;" value="Check Out">
</form>
<br>
<br>
<br>
<br>
</body>
</html>



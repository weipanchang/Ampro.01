<?php
require_once("./include/membersite_config.php");

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
      <title>Ampro Barcode Associate With SMC Barcode Page</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css">
</head>
<body>
<div id='fg_membersite_content'>
<h2>Ampro Barcode Associate With SMC Barcode Page</h2>
Welcome back <?= $fgmembersite->UserFullName(); ?>!


<HR WIDTH="100%" COLOR="#6699FF" SIZE="6">
<li><a href='Search_with_Ampro_Barcode.php' style="color:blue"> Search with Ampro Barcode </a></li>
<li><a href='Search_with_SMC_Barcode.php' style="color:blue"> Search with SMC Barcode </a></li>
<!--<li><a href='Modify_Ampro_and_HMC_Barcode_Association.php' style="color:blue"> Modify Ampro and HMC Barcode Association</a></li>-->
<HR WIDTH="100%" COLOR="#6699FF" SIZE="6">
<!--<li><a href='Ampro_change_shipping_flag.php' style="color:blue">Modify Shipping Flag </a></li>-->
<br><br><br>
<p><a href='index.html'>Back</a></p>
</div>
</body>
</html>
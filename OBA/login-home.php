<?PHP
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
      <title>Home page</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css">
</head>
<body>
<div id='fg_membersite_content'>
<h2>Home Page</h2>
Welcome back <?= $fgmembersite->UserFullName(); ?>!
<?php

?>
<li><p><a href='change-pwd.php'>Change password</a></p></li>


<li><a href='Ampro_php_form3.php' style="color:blue"> Ampro Assemble Line Operation Page</a></li>
<li><a href='Ampro_barcode_reassociate.php' style="color:blue">Change Barcode Associate and Modify Shipping Flag </a></li>

<br><br><br>
<p><a href='logout.php'>Logout</a></p>
</div>
</body>
</html>

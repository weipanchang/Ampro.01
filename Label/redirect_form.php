<!DOCTYPE html>
<html>
<head>
<title>Redirect Form To a Particular Page On Submit - Demo Preview</title>
<meta content="noindex, nofollow" name="robots">
<link href='css/redirect_form.css' rel='stylesheet' type='text/css'> <!--== Include CSS File Here ==-->
</head>
<body>
<div class="main">
<div class="first">
<h2>Redirect Form To a Particular Page On Submit using PHP</h2>
<form action="redirect_form.php" id="#form" method="post" name="#form">
<label>Name :</label>
<input id="name" name="name" placeholder='Your Name' type='text'>
<label>Email :</label>
<input id="email" name="email" placeholder='Valid Email Address' type='text'>
<label>Contact :</label>
<input id="contact" name="contact" placeholder='Contact' type='text'>
<label>Address:</label>
<input id="address" name="address" placeholder='Address' type='text' value="">
<input id='btn' name="submit" type='submit' value='Submit'>
<!---- Including PHP File Here ---->
<?php
include "./redirect.php";
?>
</form>
</div>
</div>
</body>
</html>

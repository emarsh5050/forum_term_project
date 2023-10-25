<?php 
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables
include ("shared_session.php");

print $HTMLHeader; 

$Admin = $_SESSION['Admin'];

if($Admin){echo $admin_nav;}
else {echo $normal_nav;}
 ?>
 
 <p>You can't do that</p>
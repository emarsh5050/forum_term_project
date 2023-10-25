<?php
// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables
include ("shared_session.php");

$UID = $_SESSION['UID'];
$Admin = $_SESSION['Admin'];

if(!$Admin){header("Location: editUserForm.php?UID=$UID");}
    	else{header("Location: newUserForm.php?UID=$UID");}
    	exit;

?>
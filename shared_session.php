<?php
// start the session
 session_start();

// check to see if the use has access to this page
if (!isset($_SESSION['access']) || $_SESSION['access'] != true) {
  header("Location: login.php");
  exit;
}
?>
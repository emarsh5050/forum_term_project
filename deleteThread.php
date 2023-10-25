<?php
// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables
include ("shared_session.php");

// make database connection
$conn = dbConnect();

$TID = ""; // place holder for product id information


//See if a product id is available from the client side. If yes, then retrieve the info from the database based on the product id.  If not, present the form.
if (isset($_GET['TID'])) { // note that the spelling 'TID' is based on the query string variable name

	// product id available, vaTIDate the information, then compose a query accordingly to retrieve information.

	$TID = intval($_GET['TID']); // force all input into an integer.  If the input is a string or empty, it will be converted to 0.

	// vaTIDate the product id -- check to see if it is greater than 0
		if ($TID>0 ){
			//compose the query
			$sql = "DELETE FROM `Thread` WHERE `TID` = ?;
                    DELETE FROM `Post` WHERE `TID` = ?;"; // note that the spelling "TID" is based on the field name in the database product table.

			$stmt = $conn->stmt_init();

			if ($stmt->prepare($sql)){

				$stmt->bind_param('i',$TID, $TID);

				if ($stmt->execute()){ // $stmt->execute() will return true (successful) or false
					$output = "<span class='success'>Success!</span><p>The selected record has been seccessfully deleted.</p>";
				} else {
					$output = "<div class='error'>The database operation to delete the record has been failed.  Please try again or contact the system administrator.</div>";
				}
				
			}else{$output = "STMT not prepared";}
			
			$sql = "DELETE FROM `Post` WHERE `TID` = ?"; // note that the spelling "TID" is based on the field name in the database product table.

			$stmt = $conn->stmt_init();

			if ($stmt->prepare($sql)){

				$stmt->bind_param('i',$TID);

				if ($stmt->execute()){ // $stmt->execute() will return true (successful) or false
					header('Location: ' . $_SERVER['HTTP_REFERER']);
            	    exit;
				} else {
					$output = "<div class='error'>The database operation to delete the record has been failed.  Please try again or contact the system administrator.</div>";
				}
				
			}else{$output = "STMT not prepared";}
				
			
		} else {
			// product id <= 0. reset $TID. prepare error message
			$TID = "";
			// compose an error message
			$output = "<p><b>!</b> If you are expecting to delete an exiting item, there are some error occured in the process -- the product you selected is not recognizable. Please contact the webmaster.  Thank you.</p>";
		}
} else {
	// $_GET['TID'] is not set, which means that no product id is provided
	$output = "<p><b>!</b> To manage product records, please follow the link below to visit the admin page.  Thank you. </p>";
}

?>
<br>

<?php 
$ThisUserAdmin = $_SESSION['Admin'];

if($ThisUserAdmin){echo $admin_nav;}
else {echo $normal_nav;}
 ?>

<main class='flexboxContainer'>
    
    <div class="center">   
        <?= $output ?>
        <p>Back to the <a href='admin_threadList.php'>thread list page</a></p>
    </div>

</main>





<?php print $Footer; ?>

</body>
</html>
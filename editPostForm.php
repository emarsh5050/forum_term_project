<?php
// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables
include ("shared_session.php");

// make database connection
$conn = dbConnect();

$PID = ""; 
$ThisUserUID = $_SESSION['UID'];

// Set all values for the form as empty.  To prepare for the "adding a new item" scenario.  
$Title = ""; $PostDate=""; $FID=""; $Content=""; $FPID="";

$errMsg = "";

// check to see if a product id available via the query string
if (isset($_GET['PID'])) { 

	$PID = intval($_GET['PID']); // get the integer value from $_GET['pid'] (ensure $pid contains an integer before use it for the query).  If $_GET['pid'] contains a string or is empty, intval will return zero.

	// vaPIDate the product id -- $pid should be greater than zero.
	if ($PID > 0){
				$sql = "SELECT `postDate`, `UID`, `TID`, `ApprovalStatus`, `Rejected`, `Content` FROM `Post` WHERE `PID` = ?";

		$stmt = $conn->stmt_init();
		

		if ($stmt->prepare($sql)) {
			
			/* bind the parameter onto the query*/
			$stmt->bind_param('i', $PID);

			/* execute statement */
			$stmt->execute();

			/* bind result variables */
			$stmt->bind_result($postDate, $UID, $TID, $ApprovalStatus, $Rejected, $Content);

			/* fetch values */
			if ($stmt->fetch()) { // there should be only one record, therefore, no need for a while loop
			
				if($ThisUserUID != $UID){
                header("Location: bad.php");
        	    exit;
                }
				
				//print ("Thread found, $FPID");
			} else {
				print ("<div class='error'>No product category found that matches your request. If you believe this is an error, please contact our webmaster at admin@mycompany.com.</div>");
			}
			
			
			
		} else {
			print ("<div class='error'>Query failed</div>");
		}
		
		$conn->close();
		
	}
}

// function to create the options for the category drop-down list.  
//  -- the value of parameter $selectedPID comes from the function call

?>

<?php 
$ThisUserAdmin = $_SESSION['Admin'];

if($ThisUserAdmin){echo $admin_nav;}
else {echo $normal_nav;}
 ?>

<main class='flexboxContainer'>

    
<h2>Product Information Form</h2>

<p><?= $errMsg ?></p>

<div class="container">
	<div class="row">
		<div class="col-xs-12 col-m-8">
            <form class="title" action="editPost.php" method="post">
            <input type="hidden" name="TID" value="<?=$TID?>">
        	<input type="hidden" name="PID" value="<?=$PID?>">
        	<label for="threadT">Content:</label><br>
            <textarea class= "boxsizingBorder" id="postContent" name="postContent" rows="4" cols="100"><?= htmlentities($Content) ?></textarea>	
        	<br>
            <br>
            		<input type=submit name="Submit" value="Submit New Post Information">  
            </form>

        </div>
    </div>
</div>

</main>

<?php print $Footer; ?>

</body>
</html>
<?php
// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables
include ("shared_session.php");

// make database connection
$conn = dbConnect();
$ThisUserUID = $_SESSION['UID'];

// This form is used for both adding or updating a record.
// When adding a new record, the form should be an empty one.  When editing an existing item, information of that item should be preloaded onto the form.  How do we know whether it is for adding or editing?  Check whether a product id is available -- the form needs to know which item to edit.

$TID = ""; // place holder for product id information.  Set it as empty initally.  You may want to change its name to something more fitting for your application.  However, please note this variable is used in several places later in the script. You need to replace it with the new name through out the script.

// Set all values for the form as empty.  To prepare for the "adding a new item" scenario.  
$Title = ""; $PostDate=""; $FID=""; $Content=""; $FPID="";

$errMsg = "";

// check to see if a product id available via the query string
if (isset($_GET['TID'])) { 

	$TID = intval($_GET['TID']); // get the integer value from $_GET['pid'] (ensure $pid contains an integer before use it for the query).  If $_GET['pid'] contains a string or is empty, intval will return zero.

	// vaTIDate the product id -- $pid should be greater than zero.
	if ($TID > 0){
				$sql = "SELECT `Title`, `PostDate`, `FPID` FROM `Thread` WHERE `TID` = ?";

		$stmt = $conn->stmt_init();
		

		if ($stmt->prepare($sql)) {
			
			/* bind the parameter onto the query*/
			$stmt->bind_param('i', $TID);

			/* execute statement */
			$stmt->execute();

			/* bind result variables */
			$stmt->bind_result($Title, $PostDate, $FPID);

			/* fetch values */
			if ($stmt->fetch()) { // there should be only one record, therefore, no need for a while loop
				
				//print ("Thread found, $FPID");
			} else {
				print ("<div class='error'>No product category found that matches your request. If you believe this is an error, please contact our webmaster at admin@mycompany.com.</div>");
			}
			
			
			
		} else {
			print ("<div class='error'>Query failed</div>");
		}
		
		/* close statement */
		$stmt->close(); // this statment object needs to be closed, otherwise, the next one below won't work.
		
		
		//============================================
		// Item List
		//============================================

		/* compose a query to retrieve the link information for the selected category */ 
		$sql = "SELECT `Content`, `UID` FROM `Post` WHERE `PID` = ?";
		
		/* create a prepared statement */
		$stmt = $conn->stmt_init();
		
		$count = 0;
		
		if ($stmt->prepare($sql)){
		    
		$stmt->bind_param('i', $FPID);

		$stmt->execute();
		$stmt->bind_result($Content, $UID);
	
		$tblRows = "";
		while($stmt->fetch()){
            if($ThisUserUID != $UID){
                header("Location: bad.php");
        	    exit;
                }
    		}
		}
		$stmt->close();
		
		$conn->close();
		
	}
}

// function to create the options for the category drop-down list.  
//  -- the value of parameter $selectedTID comes from the function call

?>

<?php 
$Admin = $_SESSION['Admin'];

if($Admin){echo $admin_nav;}
else {echo $normal_nav;}
 ?>

<main class='flexboxContainer'>

<div>
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-m-8">
            <p><?= $errMsg ?></p>
            <div>
            <form action="editThread.php" method="post">
        	<input type="hidden" name="TID" value="<?=$TID?>">
        	<label for="threadT">Thread Title:</label><br>
        	<textarea class="" id="threadT" name="threadT" rows="2" cols="100"><?= htmlentities($Title) ?></textarea><br><br>
        	<label for="threadT">Content:</label><br>
        	<textarea class="" id="postContent" name="postContent" rows="4" cols="100"><?= htmlentities($Content) ?></textarea>
            <br><br>
            		<input type=submit name="Submit" value="Submit New Thread Information">  
            </form>
            </div>
        </div>
    </div>
</div>



</div>
</main>

<?php print $Footer; ?>

</body>
</html>
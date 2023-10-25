<?php
// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables
include ("shared_session.php");

// make database connection
$conn = dbConnect();

$PID = ""; 

// Set all values for the form as empty.  To prepare for the "adding a new item" scenario.

$errMsg = "";

// check to see if a product id available via the query string
if (isset($_GET['PID'])) { 

	$PID = intval($_GET['PID']); // get the integer value from $_GET['PID'] (ensure $PID contains an integer before use it for the query).  If $_GET['PID'] contains a string or is empty, intval will return zero.

	// vaPIDate the product id -- $PID should be greater than zero.
	if ($PID > 0){
				$sql = "UPDATE Post, Users SET Post.Rejected=1, Users.RejectedPosts=Users.RejectedPosts+1, Users.TrustedUser = 3 WHERE Post.PID = ? and Users.UID = Post.UID";

		$stmt = $conn->stmt_init();
		

		if ($stmt->prepare($sql)) {
			
			/* bind the parameter onto the query*/
			$stmt->bind_param('i', $PID);

			/* execute statement */
			if(!$stmt->execute()){ echo("STMT not exicuted");}
            else{
		    header('Location: ' . $_SERVER['HTTP_REFERER']);
    	    exit;}
			
		} else {
			print ("<div class='error'>Query failed</div>");
		}
		
		$conn->close();
		
	}
}

// function to create the options for the category drop-down list.  
//  -- the value of parameter $selectedPID comes from the function call

?>



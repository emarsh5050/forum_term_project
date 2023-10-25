<?php
// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables
include ("shared_session.php");

// make database connection
$conn = dbConnect();

$TID = ""; 

// Set all values for the form as empty.  To prepare for the "adding a new item" scenario.

$errMsg = "";

// check to see if a product id available via the query string
if (isset($_GET['TID'])) { 

	$TID = intval($_GET['TID']); // get the integer value from $_GET['TID'] (ensure $TID contains an integer before use it for the query).  If $_GET['TID'] contains a string or is empty, intval will return zero.

	// vaTIDate the product id -- $TID should be greater than zero.
	if ($TID > 0){
				$sql = "UPDATE Post, Users SET Post.Rejected=1, Users.RejectedPosts=Users.RejectedPosts+1, Users.TrustedUser = 3 WHERE Post.TID = ? and Users.UID = Post.UID";

		$stmt = $conn->stmt_init();
		

		if ($stmt->prepare($sql)) {
			
			/* bind the parameter onto the query*/
			$stmt->bind_param('i', $TID);

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
//  -- the value of parameter $selectedTID comes from the function call

?>



<?php
// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables

// make database connection
$conn = dbConnect();

?>
<?php 
	print $HTMLHeader; 
	print $course;
?>
<header>
	<h1>Product Category List</h1>
</header>

<main>
<?php
	/* construct the query to be sent to the database */
	$sql = "SELECT `TID`, `Title`, `FID` FROM `Thread` ORDER BY `TID`";
	
	/* create a prepared statement object*/
	$stmt = $conn->stmt_init();
		
    /* prepare (test) the statement object */
	if ($stmt->prepare($sql)) {

		/* execute statement */
		$stmt->execute();

		/* bind result variables */
		$stmt->bind_result($TID, $Title, $FID);

		print ("<ul>");
		/* fetch records */
		while ($stmt->fetch()) {
			print ("<li><a href='VeiwThread.php?TID=$TID'>$Title</a>\n");
		}
		print ("</ul>");

		/* close statement */
		$stmt->close();

	} else {
		print ("<div class='error'>Query failed</div>");
	}

/* close connection */
$conn->close();
	
	
?>

</main>

<?php print $PageFooter; ?>

</body>
</html>
<?php
include ("shared_session.php");

// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables

/*if(!isset($_SESSION['access']) || $_SESSION['access'] != true) {
	header('Location: login.php');
	exit;
}*/


// make database connection
$conn = dbConnect();

?>
<?php 
	
	$UID = $_SESSION['UID'];
	//echo("UID: $UID");
?>
<header>
	<h1><?= $SubTitle_Admin ?></h1>
</header>

<script>
function confirmDel(title, lid) {
// javascript function to ask for deletion confirmation

	url = "deleteThread.php?TID="+lid;
	var agree = confirm("Delete this item: <" + title + "> ? ");
	if (agree) {
		// redirect to the deletion script
		location.href = url;
	}
	else {
		// do nothing
		return;
	}
}
</script>


<?php echo $admin_nav ?>

<main>
<?php

	
	// Retrieve the product & category info
	$sql = "SELECT Post.PID, Post.UID, Post.postDate, Post.Content FROM Post, Thread WHERE Post.ApprovalStatus = 0 and Thread.FPID != Post.PID and Post.TID = Thread.TID";

	$stmt = $conn->stmt_init();

	if ($stmt->prepare($sql)){

		$stmt->execute();
		$stmt->bind_result($PID, $UID, $PostDate, $Content);
	
		$tblRows = "";
		while($stmt->fetch()){
            $Title_js = str_replace('"', "'", $Content); // replace double quotes by the single quote in the title to avoid trouble in the javascript function.
			$Title_js = htmlspecialchars($Title_js, ENT_QUOTES); // convert quotation marks in the product title to html entity code.  This way, the quotation marks won't cause trouble in the javascript function call ( href='javascript:confirmDel ...' ) below.  
            
            $tblRows = $tblRows."<tr><td><a href='approvePost.php?PID=$PID'>Approve</a>";
            
            $tblRows = $tblRows."<td><a href='editUser.php?PID=$PID'>Edit</a></td><td><a href='javascript:confirmDel(\"$Content\",$PID)'>Delete</a></td>";
			$tblRows = $tblRows."<td>$Content</td>";
	
			$tblRows = $tblRows."<td>$PostDate</td></tr>";
		} 
		
		$output = "
		<div class=\"title\">
		<h2  class=\"title\">Unapproved Post List</h2>
		</div>
        <table class=\"styled-table\">\n
		<tr><th></th><th></th><th></th><th>Title<span><a href='?sort=title&order=a' title='Sort by title ascending'>&darr;</a></span> <span><a href='?sort=title&order=d' title='Sort by title descending'>&uarr;</a></span></th><th>Date <span><a href='?sort=DateID&order=a' title='Sort by Date ascending'>&darr;</a></span> <span><a href='?sort=DateID&order=d' title='Sort by Date descending'>&uarr;</a></span></th></tr>\n".$tblRows.
		"</table>\n";
		
		if(empty($tblRows)){$output ="<div class=\"title\">
		<h2  class=\"title\">All current posts are approved</h2>
		</div>";}
					
		$stmt->close();
	} else {

		$output = "Query to retrieve product information failed.";
	
	}

	$conn->close();
?>
	
		

<div class='flexboxContainer'>
    <div>
        <?php echo $output ?>
    </div>
</div>
</main>

<?php print $Footer; ?>

</body>
</html>
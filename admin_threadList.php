<?php
include ("shared_session.php");

//Much of the code for this site is adapted from example code take from CTEC-4321-001-DIGITAL COMM MANAGEMENT. It can be found here: https://cyjang.utasites.cloud/ctec4321/index.php

// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables

$conn = dbConnect();

?>
<?php 
	
	$thisUID = $_SESSION['UID'];
	
	//echo("UID: $UID");
?>
<header>
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


<?php 
$ThisUserAdmin = $_SESSION['Admin'];

if($ThisUserAdmin){echo $admin_nav;}
else {echo $normal_nav;}
 ?>

<main>
<?php

	
	// Retrieve the product & category info
	$sql = "SELECT      Thread.TID, Thread.Title, Thread.PostDate, Thread.FID, Post.UID, Post.ApprovalStatus, Post.Rejected
            FROM       Thread, Post
            WHERE      Thread.FID = 1 and Thread.FPID = Post.PID and Thread.TID = Post.TID
            ORDER BY `PostDate` DESC";

	$stmt = $conn->stmt_init();

	if ($stmt->prepare($sql)){

		$stmt->execute();
		$stmt->bind_result($TID, $Title, $PostDate, $FID, $UID, $AppStat, $Rejected);

	
		$tblRows = "";
		while($stmt->fetch()){
		    $Ttblr="";
		    $msg="";
            $Title_js = str_replace('"', "'", $Title); // replace double quotes by the single quote in the title to avoid trouble in the javascript function.
			$Title_js = htmlspecialchars($Title_js, ENT_QUOTES); // convert quotation marks in the product title to html entity code.  This way, the quotation marks won't cause trouble in the javascript function call ( href='javascript:confirmDel ...' ) below.  
            
            if($thisUID==$UID){$edit = "<a href='NewThreadForm.php?TID=$TID'>Edit</a>";
                if(!$AppStat && !$Rejected){$msg = "Your thread awaiting approval:";}
                elseif ($Rejected){$msg = "Your thread was deemed offensive by admin. You may edit for reapproval:";}
            } else {$edit ="";}
            if($thisUID==$UID && !$Rejected){$delete = "<a href='javascript:confirmDel(\"$Title\",$TID)'>Delete</a>";} else{$delete="";}

            if($ThisUserAdmin){
                if(!$AppStat && !$Rejected){$msg = "A thread is awaiting approval:";}
                elseif ($Rejected){$msg = "A thread was deemed offensive by admin:";}
            }
            if($msg){$Ttblr .= "<tr><td colspan=\"4\">$msg</td></tr>";}
            $Ttblr .= "<tr><td>$edit</td><td>$delete</td><td><a href='ViewThread.php?TID=$TID'>$Title</a></td></td><td>$PostDate</td></tr>";
            
            if(!($thisUID==$UID || $ThisUserAdmin) && (!$AppStat || $Rejected)){$Ttblr="";}
            
            $tblRows.=$Ttblr;
		}
		
		$output = "
		<div class=\"title\">
		<h2  class=\"title\">Thread List</h2>
		<p>All threads and posts on this forum must be approved by Admin before they will become public unless they are posted by a trusted user or an Admin.</p>
		</div>
        <table class=\"styled-table\">\n
		<tr class=\"active-row\"><th></th><th></th><th>Title</th><th>Date</th></tr>".$tblRows.
		"</table>";
					
		$stmt->close();
	} else {

		$output = "Query to retrieve product information failed.";
	
	}

	$conn->close();
?>
	
		

<div class='flexboxContainer'>
    <div>
        <?php echo ("$output $Footer")?>
    </div>
</div>
</main>

</body>
</html>
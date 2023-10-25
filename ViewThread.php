<?php
//Much of the code for this site is adapted from example code take from CTEC-4321-001-DIGITAL COMM MANAGEMENT. It can be found here: https://cyjang.utasites.cloud/ctec4321/index.php

// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php");
include ("shared_session.php");// stored shared contents, such as HTML header and page title, page footer, etc. in variables

$thisUID = $_SESSION['UID'];
$ThisUserAdmin = $_SESSION['Admin'];

$output = "";

// make database connection
$conn = dbConnect();
	
// check to see if $TID is an number
if (!empty($_GET['TID']) && is_numeric($_GET['TID'])){
	
		// get the TID value (integer value) from the query string
		$TID = intval($_GET['TID']);
		
		$stmt_prepared = 0;} else{echo("This thread does not exist");}

if (isset($_POST['postContent']) && !empty($_POST['postContent'])){

    $stmt = $conn->stmt_init();
    $postContent = $_POST['postContent'];
    
    $sql = "INSERT INTO `Post`(`UID`, `TID`, `ApprovalStatus`, `Content`) VALUES (?,?,?,?)";
    
    $Tuser = $_SESSION['TUser'];
     $ThisUserAdmin = $_SESSION['Admin'];
     //echo("TU: $Tuser, Admin: $ThisUserAdmin<br>");
     if($Tuser==1 || $ThisUserAdmin){$TuserVal=1;} else {$TuserVal=0;}

	if($stmt->prepare($sql)){

		// Note: user input could be an array, the code to deal with array values should be added before the bind_param statment.

		$stmt->bind_param('iiis',$thisUID, $TID, $TuserVal, $postContent);
		$stmt_prepared = 1; // set up a variable to signal that the query statement is successfully prepared.
	} else{echo"STMNT not prepared";}
    
    $executed = 0; 
      
	if ($stmt_prepared == 1){
			if ($stmt->execute() && $executed == 0){
			    $executed = 1;
			    header("Location:ViewThread.php?TID=$TID");
                exit;
	
			} else {
				//$stmt->execute() failed.
				$output = "<div class='error'>Database operation failed.  Please contact the webmaster.</div>";
			}
		} else {
			// statement is not successfully prepared (issues with the query).
			$output = "<div class='error'>Database query failed.  Please contact the webmaster.</div>";
		}	
	
	$stmt->close(); 
}

?>

<script>
function confirmDel(title, lid) {
// javascript function to ask for deletion confirmation

	url = "deletePost.php?PID="+lid;
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

<main>
<?php 
$ThisUserAdmin = $_SESSION['Admin'];

if($ThisUserAdmin){echo $admin_nav;}
else {echo $normal_nav;}
 ?>

<?php


		//==================================================
		// category information (to print the heading)
		//==================================================

		/* compose a query to retrieve the information for the selected category */
		$sql = "SELECT Thread.Title, Thread.FPID, Post.PID, Post.ApprovalStatus, Post.Rejected FROM Thread, Post WHERE Thread.TID = ? and Post.PID = Thread.FPID ";

		$stmt = $conn->stmt_init();
		

		if ($stmt->prepare($sql)) {
			
			/* bind the parameter onto the query*/
			$stmt->bind_param('i', $TID);

			/* execute statement */
			$stmt->execute();

			/* bind result variables */
			$stmt->bind_result($threadName, $FPID, $PID, $PAS, $Rej);

			/* fetch values */
			if ($stmt->fetch()) { // there should be only one record, therefore, no need for a while loop
				
				print ("<div class=\"title\">
		<h2  class=\"title\">$threadName</h2> ");
		
		if($ThisUserAdmin){
    		if(!$PAS){print("<a href='approveThread.php?TID=$TID'>Approve</a>&emsp;");}
  
    		if(!$Rej && !$PAS){ print("<a href='unapprovePost.php?PID=$PID'>Reject</a>&emsp;this thread <br>");} 
    		elseif (!$Rej) { print("<a href='unapprovePost.php?PID=$PID'>Remove</a>&emsp;this thread <br>");} 
		}
		
		
		print("</div>");
			} else {
				print ("<div class='error'>No pFroduct category found that matches your request. If you believe this is an error, please contact our webmaster at admin@mycompany.com.</div>");
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
		$sql = "SELECT Post.PID, Post.postDate, Post.UID, Post.TID, Post.ApprovalStatus, Post.Content, Post.Rejected, Users.UserName, Users.Admin FROM Post, Users WHERE Post.TID=? and Users.UID = Post.UID ORDER BY postDate";
		
		/* create a prepared statement */
		$stmt = $conn->stmt_init();
		
		if ($stmt->prepare($sql)) {

			/* bind the parameter onto the query*/
			$stmt->bind_param('i', $TID);

			/* execute statement */
			$stmt->execute();

			/* store result: this will allow the use of $stmt->num_rows (providing the number of the rows/records in the result set).  Note that num_rows is not used in this version. */
		    $stmt->store_result();
			
			/* bind result variables */
			$stmt->bind_result($PID, $postDate , $UID , $TID , $ApprovalStatus, $Content, $Rejected, $Username, $Admin);

			
			/* fetch records to compose the link list */
			
			$FPFOUND=0;
			
			$tmsg = "";
			$msg="";
			while ($stmt->fetch()) {
			    if($Admin){$admsg = " (Admin)";} else {$admsg = "";}
			    
			    if($UID == $thisUID){
			        $Pmsg = "This post by you ";
			        $edit = "<a href='editPostForm.php?PID=$PID'>Edit</a>&emsp;";
			        if($PID != $FPID){ $delete = "<a href='javascript:confirmDel(\"$Content\",$PID)'>Delete</a>&emsp;";}
			        else $delete = "";
			    } else {
			        $Pmsg = "This post ";
			        $edit = "";
			    }
			    
			    
			    if(!$Rejected && !$ApprovalStatus){
			        $Pmsg .= "is awaiting approval.";
			        $apOrRej="<a href='approvePost.php?PID=$PID'>Approve</a>&emsp;|&emsp;<a href='unapprovePost.php?PID=$PID'>Reject</a>";
			    }
			     elseif($Rejected && $ApprovalStatus){
			        $Pmsg .= "was removed by admin.";
			        if($UID == $thisUID){ $Pmsg .= " You may edit for reapproval.";}
			        $apOrRej="<a href='approvePost.php?PID=$PID'>Restore</a>&emsp;";
			        $delete = "";
			    } 
			    elseif($Rejected && !$ApprovalStatus && ($ThisUserAdmin || $UID == $thisUID)){
			        $Pmsg .= "was rejected by admin.";
			        if($UID == $thisUID){ $Pmsg .= " You may edit for reapproval.";}
			        $apOrRej="<a href='approvePost.php?PID=$PID'>Approve</a>&emsp;";
			        $delete = "";
			    }
			   else{$Pmsg = ""; $apOrRej="";}
			   
			   
			    
			    if(!$Rejected && $ThisUserAdmin && ($PID != $FPID)){$remove="<a href='unapprovePost.php?PID=$PID'>Remove</a>&emsp;";} else{$remove="";}
			    if(!$ThisUserAdmin){$apOrRej="";}
			    if($UID != $thisUID){$delete = "";}
			    
			    if(!empty($Pmsg)){
			            $tmsg .= " 
                        	$Pmsg<br>
                        	"; 
			        if($PID != $FPID){$tmsg.="$apOrRej
                        <br>";}
			        
			    }
                
                if(($UID == $thisUID || $ThisUserAdmin) || ($ApprovalStatus && !$Rejected)){
                $tmsg .= " 
                        	Posted by: $Username$admsg, On: $postDate<br> $Content 
                        <br>";}
                
                if($UID == $thisUID || $ThisUserAdmin){
                $tmsg .= " 
                        	$edit 
                        	$delete 
                        	$remove 
                        <br>";}
                
                if(!empty($tmsg)) {$tmsg="<tr><td>".$tmsg. "</td></tr>";}
                
                $msg.=$tmsg;
                $tmsg="";
			}
			
$newPostForm = <<<OUT
			
	<form action="" method="post">
    <textarea id="textboxid" type='textarea' name="postContent" rows="5" cols="100"></textarea>
    <br>
    <input type="submit" name="submit" value="Post">        
    </form>
        
OUT;
			
			if(!empty($msg)){print ("<table class=\"styled-table\">\n
		<tr class=\"active-row\">$msg<tr><td>$newPostForm</li></td></table>$output");}
			else{print("<p>No Records Found</p>");}


			/* close statement */
			$stmt->close();

		} else {
			print ("<div class='error'>Query failed</div>");
		}
		

    		$conn->close();
	
?>


</main>
<?php print $Footer; ?>

</body>
</html>
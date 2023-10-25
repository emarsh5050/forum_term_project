<?php
// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables
include ("shared_session.php");

// make database connection
$conn = dbConnect();
$output="";

// Process only if there is any submission
if (isset($_POST['Submit'])) {

	// ==========================
	//vaPIDate user input
	
	// set up the required array 
	$required = array("TID", "PID", "postContent"); // note that, in this array, the spelling of each item should match the form field names

	// set up the expected array
	$expected = array("TID", "PID", "postContent"); // again, the spelling of each item should match the form field names
    
    // set up a label array, use the field name as the key and label as the value
    $label = array ('PID'=>'PID', "postContent"=>'Post Content', 'TID'=>'TID');

	$missing = array();
	
	foreach ($expected as $field){

		if (in_array($field, $required) && empty($_POST[$field])) {
			array_push ($missing, $field);
		} else {
			if (!isset($_POST[$field])) {
				${$field} = "";
			} else {
				${$field} = $_POST[$field];
			}
		}
	}
	
	
	
	if (empty($missing)){
	         $stmt = $conn->stmt_init();
            
            $Tuser = $_SESSION['TUser'];
	         $Admin = $_SESSION['Admin'];
	         //echo("TU: $Tuser, Admin: $Admin<br>");
	         if($Tuser==1 || $Admin){$TuserVal=1;} else {$TuserVal=0;}
            $sql = "UPDATE Post SET Post.Content =?, Post.ApprovalStatus=?, Post.Rejected=0 WHERE Post.PID = ?";
        
        	if($stmt->prepare($sql)){
        
        		$stmt->bind_param('sii',  htmlentities($postContent), $TuserVal, $PID);
        		$stmt_prepared = 1; // set up a variable to signal that the query statement is successfully prepared.
        	} else{echo"STMNT not prepared";}
            
            $executed = 0; 
              
        	if ($stmt_prepared == 1){
        			if ($stmt->execute() && $executed == 0){
       			    $executed = 1;
       			    header("Location: ViewThread.php?TID=$TID");
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
	    }else{ header("Location: editPostForm.php?PID=$PID"); exit;}
	} 



?>


<?php 
$ThisUserAdmin = $_SESSION['Admin'];

if($ThisUserAdmin){echo $admin_nav;}
else {echo $normal_nav;}
 ?>

<main class='flexboxContainer'>
    
    <div>   
        <?= $output ?>
    </div>

</main>

<?php print $Footer; ?>

</body>
</html>
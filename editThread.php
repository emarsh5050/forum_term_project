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
	//vaTIDate user input
	
	// set up the required array 
	$required = array("threadT", "postContent"); // note that, in this array, the spelling of each item should match the form field names

	// set up the expected array
	$expected = array("threadT", "postContent", "TID"); // again, the spelling of each item should match the form field names
    
    // set up a label array, use the field name as the key and label as the value
    $label = array ('TID'=>'TID', 'threadT'=>'Thread Title', "postContent"=>'Post Content');

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
	    $UID=$_SESSION['UID'];
	    $executed = 0; 
	    
	    if(empty($TID)){ 
	        $stmt = $conn->stmt_init();
            
            $sql = "INSERT INTO `Thread`(`Title`, `FPID`, `FID`) VALUES (?,0,1)";
        
        	if($stmt->prepare($sql)){
        
        	
        		$UID = $_SESSION['UID'];
        
        		$stmt->bind_param('s', $threadT);
        		$stmt_prepared = 1; // set up a variable to signal that the query statement is successfully prepared.
        	} else{echo"STMNT not prepared";}
            
            $executed = 0; 
              
        	if ($stmt_prepared == 1){
        			if ($stmt->execute() && $executed == 0){
        			    $last_id = $conn->insert_id;
        			    $TID = $last_id;
                    // echo "New record created successfully. Last inserted ID is: " . $last_id;
       			    $executed = 1;
        	
        			} else {
        				//$stmt->execute() failed.
        				$output = "<div class='error'>Database operation failed.  Please contact the webmaster.</div>";
        			}
        		} else {
        			// statement is not successfully prepared (issues with the query).
        			$output = "<div class='error'>Database query failed.  Please contact the webmaster.</div>";
        		}	
        	
        	$stmt->close(); 
        	
        //INSERT INTO `Post`(`UID`, `TID`, `ApprovalStatus`, `Content`) VALUES (?, ?, 0, ?)
        
            $stmt = $conn->stmt_init();
            
            $Tuser = $_SESSION['TUser'];
             $Admin = $_SESSION['Admin'];
             //echo("TU: $Tuser, Admin: $Admin<br>");
             if(($Tuser == 1)|| $Admin){$TuserVal=1;} else {$TuserVal=0;}
            
            $sql = "INSERT INTO `Post`(`UID`, `TID`, `ApprovalStatus`, `Content`) VALUES (?, ?, ?, ?)";
        
        	if($stmt->prepare($sql)){
        
        		$stmt->bind_param('iiis', $UID, $last_id, $TuserVal, htmlentities($postContent));
        		$stmt_prepared = 1; // set up a variable to signal that the query statement is successfully prepared.
        	} else{echo"STMNT not prepared";}
            
            $executed = 0; 
              
        	if ($stmt_prepared == 1){
        			if ($stmt->execute() && $executed == 0){
        			    $last_id = $conn->insert_id;
                     //echo "New record created successfully. Last inserted ID is: " . $last_id;
       			    $executed = 1;
        	
        			} else {
        				//$stmt->execute() failed.
        				$output = "<div class='error'>Database operation failed.  Please contact the webmaster.</div>";
        			}
        		} else {
        			// statement is not successfully prepared (issues with the query).
        			$output = "<div class='error'>Database query failed.  Please contact the webmaster.</div>";
        		}	
        	
        	$stmt->close(); 
        	
        	 $stmt = $conn->stmt_init();
            
            $sql = "UPDATE `Thread` SET `FPID`=? WHERE `TID` = ?";
        
        	if($stmt->prepare($sql)){
        
        		$stmt->bind_param('ii', $last_id, $TID);
        		$stmt_prepared = 1; // set up a variable to signal that the query statement is successfully prepared.
        	} else{echo"STMNT not prepared";}
            
            
              
        	if ($stmt_prepared == 1){
        			if ($stmt->execute() && $executed == 0){
       			    $executed = 1;
        	
        			} else {
        				//$stmt->execute() failed.
        				$output = "<div class='error'>Database operation failed.  Please contact the webmaster.</div>";
        			}
        		} else {
        			// statement is not successfully prepared (issues with the query).
        			$output = "<div class='error'>Database query failed.  Please contact the webmaster.</div>";
        		}	
        	
        	$stmt->close(); 
        
            $conn->close();
	    } else{
	         $stmt = $conn->stmt_init();
	         
	         $Tuser = $_SESSION['TUser'];
	         $Admin = $_SESSION['Admin'];
	         //echo("TU: $Tuser, Admin: $Admin<br>");
	         if($Tuser==1 || $Admin){$TuserVal=1;} else {$TuserVal=0;}
            
            $sql = "UPDATE Thread, Post SET Thread.Title = ?, Post.Content =?, Post.ApprovalStatus = ?, Post.Rejected=0 WHERE Thread.TID = ? and Post.PID = Thread.FPID";
        
        	if($stmt->prepare($sql)){
        
        		$stmt->bind_param('ssii', $threadT,  htmlentities($postContent), $TuserVal, $TID);
        		$stmt_prepared = 1; // set up a variable to signal that the query statement is successfully prepared.
        	} else{echo"STMNT not prepared";}
            
             
              
        	if ($stmt_prepared == 1){
        			if ($stmt->execute() && $executed == 0){
       			    $executed = 1;
        	
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
	    
	    if($executed == 1){		    
	   	    header("Location: admin_threadList.php");
            exit;}
            
        $output .="Your thread has been submitted for approval<br>";
    	foreach($expected as $key){
    	        $bool = empty($_POST[$key]);
    			$output .= "<b>{$label[$key]}</b>: {$_POST[$key]}, $bool <br>"; 
    	}
	} else{ 
	       echo "<script> alert('You Need to put something in the boxes.');
                
                
                window.location.href = 'NewThreadForm.php?TID=$TID';
                
             
                </script>";}
	
	   	
	
	
	
	
} else {
	$output = "What's wrong with the post button?";}


?>
<main class='flexboxContainer'>
    
    <div>   
        <?= $output ?>
    </div>

</main>

<?php print $Footer; ?>

</body> class=""title
</html><<p>h2 </h2>p





<?php
// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables
include ("shared_session.php");

// make database connection
$conn = dbConnect();

// Process only if there is any submission
if (isset($_POST['Submit'])) {

	// ==========================
	//vaUIDate user input
	
	//`UID`, `UserName`, `Password`, `EmailAdress`, `StreetAdress`, `City`, `State`, `Zip`, `UserImageURL`, `Admin`, `ParentID`, `ForumAcess`
	
	// set up the required array 
	$ThisUserAdmin = $_SESSION['Admin'];

    if($ThisUserAdmin){$required = array("UserName", "Password","EmailAdress", "ForumAcess");} else{$required = array("UserName", "Password","EmailAdress");}
	
	 // note that, in this array, the spelling of each item should match the form field names

	// set up the expected array
	$expected = array("UID", "UserName", "Password","EmailAdress", "StreetAdress", "City","State", "Zip", "Admin","ParentID", "ForumAcess", "Edited", "FirstName", "LastName", "TrustedUser"); // again, the spelling of each item should match the form field names
    
    // set up a label array, use the field name as the key and label as the value
    $label = array ('UID'=>'UID', 'UserName'=>'UserName', "Password"=>'Password', 'EmailAdress'=>'EmailAdress', 'ForumAcess'=>'ForumAcess');

	$missing = array();
	
	foreach ($expected as $field){
		// Under what conditions the script needs to record a field as missing -- $field is required and one of the following two (1)  $_POST[$field] is not set or (2) $_POST[$field] is empty
        
        // Enable the line below to debug
		//echo "$field: in_array(): ".in_array($field, $required)." empty(_POST[$field]): ".empty($_POST[$field])."<br>"; 

		if (in_array($field, $required) && empty($_POST[$field])) {
			array_push ($missing, $field);
			${$field} = "";
		
		} else {
			// Passed the required field test, set up a variable to carry the user input.  
			// Note the variable set up here uses the $field value as the veriable name. Notice the syntax ${$field}.  This is a "variable variable". For example, the first $field in the foreach loop here is "title" (the first one in the $expected array) and a $title variable will be created.  The value of this variable will be either "" or $_POST["title"] depending on whether $_POST["title"] is set up.
            // once we run through the whole $expected array, then these variables, $title, $artist, $price, $categoryID, $pDtail, and $pid, will be generated.
            
			if (!isset($_POST[$field])) {
				//$_POST[$field] is not set, set the value as ""
				${$field} = "";
			} else {
				
				${$field} = $_POST[$field];
				
			}
		
		}

	}
	
	if (str_contains($UserName, ' ')) {array_push ($missing, "UserName");}
	if (str_contains($Password, ' ')) {array_push ($missing, "Password");}
	
	//echo("Admin: $Admin");

	//print_r ($missing); // for debugging purpose

	/* add more data vaUIDation here */
	// ex. $price should be a number

	/* proceed only if there is no required fields missing and all other data vaUIDation rules are satisfied */
	if (empty($missing)){

		//========================
		// processing user input

		$stmt = $conn->stmt_init();


		// compose a query: Insert or Update? Depending on whether there is a $pid.
		
		//echo("UID: $UID<br>");
		$stmt_prepared = 0;
		
		if ($UID != "") {
		    //echo("<br> UID != NOTHING");
			/* there is an existing pid ==> need to deal with an existing reocrd ==> use an update query */ 
			
			// Ensure $pid contains an integer. 
			$UID = intval($UID); 

			$sql = "UPDATE `Users` SET `UserName`=?,`Password`=?,`FirstName`=?,`LastName`=?,`EmailAdress`=?,`StreetAdress`=?,`City`=?,`State`=?,`Zip`=?,`Admin`=?,`TrustedUser`=?,`ParentID`=?,`ForumAcess`=? WHERE UID=?";
				
			//echo("URL: $URL, AT: $anchorText, CID: $categoryID, UID: $UID");
			
			if($stmt->prepare($sql)){
			    
			    if(empty($Zip)){$Zip=0;}

				// Note: user input could be an array, the code to deal with array values should be added before the bind_param statment.
				$stmt->bind_param('ssssssssiiiiii', $UserName, $Password, $FirstName, $LastName, $EmailAdress, $StreetAdress, $City, $State, $Zip, $Admin, $TrustedUser, $ParentID, $ForumAcess, $UID);
				$stmt_prepared = 1;// set up a variable to signal that the query statement is successfully prepared.
			}

		} else {
		    //echo("<br> UID == NOTHING");
		    $UserImageURL="";
		    if(!isset($_POST[$Admin])){ $Admin = 0;}
		    if(!isset($_POST[$ParentID])){ $ParentID = 0;}
			// no existing pid ==> this means no existing record to deal with, then it must be a new record ==> use an insert query
			$sql = "INSERT INTO `Users`(`UserName`, `Password`, `FirstName`, `LastName`, `EmailAdress`,`UserImageURL`, `StreetAdress`, `City`, `State`, `Zip`, `Admin`, `TrustedUser`, `ParentID`, `ForumAcess`) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			if($stmt->prepare($sql)){

				// Note: user input could be an array, the code to deal with array values should be added before the bind_param statment.

				$stmt->bind_param('ssssssssiiiiii', $UserName, $Password, $FirstName, $LastName, $EmailAdress, $UserImageURL, $StreetAdress, $City, $State, $Zip, $Admin, $TrustedUser, $ParentID, $ForumAcess);
				$stmt_prepared = 1; // set up a variable to signal that the query statement is successfully prepared.
			}
		}
		
		//echo("<br>STMT preped: $stmt_prepared");

		if ($stmt_prepared == 1){
			if ($stmt->execute()){
                
                // NOTE: the following code does not produce most user-friendly message.  Particularly the category information is presented as an number which the user will have no idea about.  Can you fix it?
                
				$output = "<span class='success'>Success!</span><p>The following information has been saved in the database:</p>";
				
				 header("Location:editUser.php?UID=$UID");
                 exit;
			} else {
				//$stmt->execute() failed.
				$output = "<div class='error'>Database operation failed.  Please contact the webmaster.</div>";
			}
		} else {
			// statement is not successfully prepared (issues with the query).
			$output = "<div class='error'>Database query failed.  Please contact the webmaster.</div>";
		}
		
		foreach($expected as $key){
			$output .= "<b>{$label[$key]}</b>: {$_POST[$key]} <br>"; 
		}

	} else { 
		// $missing is not empty 
		$output = "<div class='center'><p>The following required fields are missing or incorrectly filled out in your form submission.  Please check your form again and fill them out.  Thank you.</p>\n";
		foreach($missing as $m){
			$output .= "<p>{$label[$m]}</p>\n";
		}
		if($UID){
		$output .= "</ul><br><a class=\"btn btn-secondary\" onclick=\"history.go(-1);\">Go back</a></div>\n";}
		else{
		    $output .= "</ul><br><a class=\"btn btn-secondary\" href=\"newUserForm.php?UID=$UID\">Go back</a></div>\n";
		}
	}

} else {
	$output = "<p>User information has been successfully submitted.</p>";
}


?>

<?php 
$ThisUserAdmin = $_SESSION['Admin'];

if($ThisUserAdmin){echo $admin_nav;}
else {echo $normal_nav;}
 ?>

<main class='flexboxContainer'>
    
    <div class="title">   
        <?= $output ?>
    </div>

</main>

<?php print $Footer; ?>

</body>
</html>
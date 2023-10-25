<?php
// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables
include ("shared_session.php");

?>

<?php

// make database connection
$conn = dbConnect();

// This form is used for both adding or updating a record.
// When adding a new record, the form should be an empty one.  When editing an existing item, information of that item should be preloaded onto the form.  How do we know whether it is for adding or editing?  Check whether a product id is available -- the form needs to know which item to edit.

$UID = ""; // place holder for product id information.  Set it as empty initally.  You may want to change its name to something more fitting for your application.  However, please note this variable is used in several places later in the script. You need to replace it with the new name through out the script.

// Set all values for the form as empty.  To prepare for the "adding a new item" scenario.  `UID`, `UserName`, `Password`, `EmailAdress`, `StreetAdress`, `City`, `State`, `Zip`, `UserImageURL`, `Admin`, `ParentID`, `ForumAcess`

$UserName="";
$Password="";
$EmailAdress="";
$StreetAdress="";
$City="";
$State="";
$Zip="";
$Admin="";
$ParentID="";
$ForumAcess="";
$TrustedUser=0;
$FirstName="";
$LastName="";

$errMsg = "";

// check to see if a product id available via the query string
if (isset($_GET['UID'])) { // note that the spelling 'UID' is based on the query string variable name.  When linking to this form (form.php), if a query string is attached, ex. form.php?UID=3 , then that information will be detected here and used below.

	$UID = intval($_GET['UID']); // get the integer value from $_GET['pid'] (ensure $pid contains an integer before use it for the query).  If $_GET['pid'] contains a string or is empty, intval will return zero.

	// validate the product id -- $pid should be greater than zero.
	if ($UID > 0){
			
		//compose a select query
		$sql = "SELECT `UID`, `UserName`, `Password`, `FirstName`, `LastName`, `EmailAdress`, `StreetAdress`, `City`, `State`, `Zip`, `UserImageURL`, `Admin`, `TrustedUser`, `ParentID`, `ForumAcess`, `RejectedPosts` FROM `Users` WHERE `UID` = ?"; // note that the spelling "lid" is based on the field name in my product table (database).
			
		$stmt = $conn->stmt_init();

		if($stmt->prepare($sql)){
			$stmt->bind_param('i',$UID);
			$stmt->execute();
				
			$stmt->bind_result($UID, $UserName, $Password, $FirstName, $LastName, $EmailAdress, $StreetAdress, $City, $State, $Zip, $UserImageURL, $Admin, $TrustedUser, $ParentID, $ForumAcess, $RejectedPosts); // bind the five pieces of information necessary for the form.
			
			$stmt->store_result();
				
			// proceed only if a match is found -- there should be only one row returned in the result
			if($stmt->num_rows == 1){
				$stmt->fetch();
			} else {
				$errMsg = "<div class='error'>Information on the record you requested is not available.  If it is an error, please contact the webmaster.  Thank you.</div>";
				$UID = ""; // reset $UID
			}

		} else {
			// reset $UID
			$UID = "";
			// compose an error message
			$errMsg = "<div class='error'> If you are expecting to edit an exiting item, there are some error occured in the process -- the selected product is not recognizable.  Please follow the link below to the product adminstration interface or contact the webmaster.  Thank you.</div>";
		}
		
		$stmt->close();
	} // close if(is_int($UID))
	
}

// function to create the options for the category drop-down list.  
//  -- the value of parameter $selectedCID comes from the function call
function UIDList(){
	
	$list = ""; //placeholder for the CD category option list
	
	global $conn;
	// retrieve category info from the database to compose a drop down list
	$sql = "SELECT `UID`, `UserName`, `FirstName`, `LastName`, `EmailAdress` FROM `Users` ";
	
	$stmt = $conn->stmt_init();

    $list = $list."<p>List of users for parent ID:</p><ul>";

	if ($stmt->prepare($sql)){
		
		$stmt->execute();
		$stmt->bind_result($UID, $UserName, $FirstName, $LastName, $EmailAdress);

		while ($stmt->fetch()) {
			$list = $list."<li>User: $UID, $UserName, $FirstName $LastName, $EmailAdress</li>";
		}
	}
	$stmt->close();
	
	$list = $list."</ul>";
	
	return $list;
}

$ThisUserAdmin = $_SESSION['Admin'];

if($ThisUserAdmin){echo $admin_nav;}
else {echo $normal_nav;}
 ?>

<main class='flexboxContainer'>

	<div class="container">
			<div class="row">
					<div class="col-xs-12 col-m-7">

<p>Spaces are not allowed in the Username or Password. You will have to go back and fix any that you add.</p>

    <p><?= $errMsg ?></p>

<form action="editUser_admin.php" method="POST">
* Required
	<!-- `UID`, `UserName`, `Password`, `EmailAdress`, `StreetAdress`, `City`, `State`, `Zip`, `UserImageURL`, `Admin`, `ParentID`, `ForumAcess` -->
	<input type="hidden" name="UID" value="<?=$UID?>">
	<input type="hidden" name="Edited" value="<?(empty($UserName))?>">

	
		<br>User Name*:<br><input id="input1" type="text" name="UserName" size="100" value="<?= htmlentities($UserName) ?>">
		<br>Password*:<br><input id="input1" type="text" name="Password" size="100" value="<?= htmlentities($Password) ?>">
		<br>First Name:<br><input id="input1" type="text" name="FirstName" size="100" value="<?= htmlentities($FirstName) ?>">
		<br>Last Name:<br><input id="input1" type="text" name="LastName" size="100" value="<?= htmlentities($LastName) ?>">
		<br>Email Adress*:<br><input type="email" name="EmailAdress" size="100" value="<?= htmlentities($EmailAdress) ?>">
		<br>Street Adress:<br><input type="text" name="StreetAdress" size="100" value="<?= htmlentities($StreetAdress) ?>">
		<br>City:<br><input type="text" name="City" size="100" value="<?= htmlentities($City) ?>">
		<br>State:<br><input type="text" name="State" size="100" value="<?= htmlentities($State) ?>">
	
		<br>Zip:<br><input type="number" name="Zip" size="100" value="<?= htmlentities($Zip) ?>">
		
		
		<?php
			function radioChecked($fieldName,$v){
				global $missing;
				if ((!empty($fieldName))&& $fieldName == $v){
					$checked = "checked";
				} else {
					$checked = "";
				}
				echo $checked;
			}
		  ?>
		
		<br>Admin*:<br><input class = "width20" type="radio" id="Admin" name="Admin" value="0" <?php if(!$Admin){echo("checked");} ?>>No&emsp;<input class = "width20" type="radio" id="Admin" name="Admin" value="1" <?php if($Admin){echo("checked");} ?>>Yes&emsp;
		<br><br>Trusted User:<br><input class = "width20" type="radio" id="TrustedUser" name="TrustedUser" value="0" <?php if(!$TrustedUser){echo("checked");} ?>>No&emsp;<input class = "width20" type="radio" id="TrustedUser" name="TrustedUser" value="1" <?php if($TrustedUser){echo("checked");} ?>>Yes&emsp;
		<br><br>ParentID:<br><input  type="number" id="ParentID" name="ParentID" value="<?= htmlentities($ParentID) ?>"><p>If the user is a child this is the UID of thier parent.</p>
		<br>ForumAcess*:<br><input class = "width20" type="radio" id="ForumAcess" name="ForumAcess" value="1" <?php radioChecked($ForumAcess, 1) ?>>Kid&emsp;<input class = "width20" type="radio" id="ForumAcess" name="ForumAcess" value="2" <?php radioChecked($ForumAcess, 2) ?>>Parent&emsp;<input class = "width20" type="radio" id="ForumAcess*" name="ForumAcess" value="3" <?php radioChecked($ForumAcess, 3) ?>>Other&emsp;
		<br>
		<td colspan=2><br><input type=submit name="Submit" value="Submit New User Information">


</form>
</div>

<div class="col-xs-12 col-m-4">
<?php print UIDList(); ?>
</div>

</div>
</div>
</main>


<?php print $Footer; ?>

</body>
</html>
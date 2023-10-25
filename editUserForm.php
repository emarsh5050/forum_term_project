<?php
// acquire shared info from other files
include("dbconn.inc.php"); // database connection 
include("shared.php"); // stored shared contents, such as HTML header and page title, page footer, etc. in variables
include ("shared_session.php");

?>

<script>
function confirmDel(title, UID) {
// javascript function to ask for deletion confirmation

	url = "deleteUser.php?UID="+UID;
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

$errMsg = "";

// check to see if a product id available via the query string
if (isset($_GET['UID'])) { // note that the spelling 'UID' is based on the query string variable name.  When linking to this form (form.php), if a query string is attached, ex. form.php?UID=3 , then that information will be detected here and used below.

	$UID = intval($_GET['UID']); // get the integer value from $_GET['pid'] (ensure $pid contains an integer before use it for the query).  If $_GET['pid'] contains a string or is empty, intval will return zero.

	// validate the product id -- $pid should be greater than zero.
	if ($UID > 0){
			
		//compose a select query
		$sql = "SELECT `UID`, `UserName`, `Password`, `EmailAdress`, `StreetAdress`, `City`, `State`, `Zip`, `UserImageURL`, `Admin`, `ParentID`, `ForumAcess` FROM `Users` WHERE `UID` = ?"; // note that the spelling "lid" is based on the field name in my product table (database).
			
		$stmt = $conn->stmt_init();

		if($stmt->prepare($sql)){
			$stmt->bind_param('i',$UID);
			$stmt->execute();
				
			$stmt->bind_result($UID, $UserName, $Password, $EmailAdress, $StreetAdress, $City, $State, $Zip, $UserImageURL, $Admin, $ParentID, $ForumAcess); // bind the five pieces of information necessary for the form.
			
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
function CategoryOptionList($selectedCID){
	
	$list = ""; //placeholder for the CD category option list
	
	global $conn;
	// retrieve category info from the database to compose a drop down list
	$sql = "SELECT `CID`, `Name` FROM `LinkCategory`";
	
	$stmt = $conn->stmt_init();

	if ($stmt->prepare($sql)){
		
		$stmt->execute();
		$stmt->bind_result($CategoryID, $CategoryName);

		while ($stmt->fetch()) {
			// while going through the rows in the results, check if the category id of the current row matches the parameter ($CID) provided by the function call
			if ($CategoryID == $selectedCID){
				$selected = "Selected";
			} else {
				$selected = "";
			}
			// create an option based on the current row
			$list = $list."<option value='$CategoryID' $selected>$CategoryName</option>";
		}
	}
	$stmt->close();
	return $list;
}
?>

<?php 
$ThisUserAdmin = $_SESSION['Admin'];

if($ThisUserAdmin){echo $admin_nav;}
else {echo $normal_nav;}
 ?>

<main class='flexboxContainer'>

<div class="center">
    
<h2>Product Information Form</h2>

<p>Spaces are not allowed in the Username or Password. You will have to go back and fix any that you add.</p>

    <p><?= $errMsg ?></p>

<form class="title" action="editUser_admin.php" method="POST">
* Required
	<!-- `UID`, `UserName`, `Password`, `EmailAdress`, `StreetAdress`, `City`, `State`, `Zip`, `UserImageURL`, `Admin`, `ParentID`, `ForumAcess` -->
	<input type="hidden" name="UID" value="<?=$UID?>">
	<input type="hidden" name="Edited" value="<?(empty($UserName))?>">

	
		<br>User Name*:<br><input id="input1" type="text" name="UserName" size="100" value="<?= htmlentities($UserName) ?>">
		<br>Password*:<br><input id="input1" type="text" name="Password" size="100" value="<?= htmlentities($Password) ?>">
		<br>Email Adress*:<br><input type="email" name="EmailAdress" size="100" value="<?= htmlentities($EmailAdress) ?>">
		<br>Street Adress:<br><input type="text" name="StreetAdress" size="100" value="<?= htmlentities($StreetAdress) ?>">
		<br>City:<br><input type="text" name="City" size="100" value="<?= htmlentities($City) ?>">
		<br>State:<br><input type="text" name="State" size="100" value="<?= htmlentities($State) ?>">
	
		<br>Zip:<br><input type="number" name="Zip" size="100" value="<?= htmlentities($Zip) ?>">
		
		
		
		
		<td colspan=2><br><input type=submit name="Submit" value="Submit New User Information">


</form>



</div>
</main>

<?php print $Footer; ?>

</body>
</html>
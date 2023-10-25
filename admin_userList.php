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
	
	$thisUID = $_SESSION['UID'];
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
$ThisUserAdmin = $_SESSION['Admin'];

if($ThisUserAdmin){echo $admin_nav;}
else {echo $normal_nav;}
 ?>

<main>
<?php
$defaultSortingField = "LinkCategory.CID";
	if (isset($_GET['sort'])) {
		$sort = $_GET['sort'];
		if ($sort == "title"){
			$sort = "Links.AnchorText";
		} else {
			$sort = $defaultSortingField;
		}
	} else {
		// defaulty sorting field
		$sort = $defaultSortingField;
	}

	// the sorting order: ascending or descending
	if (isset($_GET['order'])){
		$order = $_GET['order'];
		if ($order == "d"){
			$order = " DESC";
		} else {
			$order = " ASC";
		}
	} else $order= "ASC";

// Retrieve the product & category info
	$sql = "SELECT `UID`, `UserName`, `Password`, `FirstName`, `LastName`, `EmailAdress`, `StreetAdress`, `City`, `State`, `Zip`, `UserImageURL`, `Admin`, `TrustedUser`, `ParentID`, `ForumAcess`, `RejectedPosts` FROM `Users`";
	
	/*SELECT product.cid, product.Title, category.CategoryName FROM product, category where product.CategoryID = category.CategoryID order by category.CategoryName
	
	SELECT Thread.TID, Thread.Title, Thread.PostDate, Thread.FID FROM Thread WHERE `FID` = 1 ORDER BY `PostDate`*/

	$stmt = $conn->stmt_init();

	if ($stmt->prepare($sql)){

		$stmt->execute();
		$stmt->bind_result($UID, $UserName, $Password, $FirstName, $LastName, $EmailAdress, $StreetAdress, $City, $State, $Zip, $UserImageURL, $Admin, $TrustedUser, $ParentID, $ForumAcess, $RejectedPosts);
	
		$tblRows = "";
		while($stmt->fetch()){

			$tblRows = $tblRows."<tr>";
				/*<td><a href='javascript:confirmDel(\"$Title_js\",$TID)'>Delete</a> </td>*/
				
			if($ThisUserAdmin){$tblRows = $tblRows."<td><a href='newUserForm.php?UID=$UID'>Edit</a></td><td><a href='javascript:confirmDel(\"$UserName\",$UID)'>Delete</a></td>";}
		
		    else if($thisUID == $UID){$tblRows = $tblRows."<td><a href='editUserForm.php?UID=$UID'>Edit</a></td><td><a href='javascript:confirmDel(\"$UserName\",$UID)'>Delete</a></td>";}else{$tblRows = $tblRows."<td></td>
		    <td></td>";}
		
		
		//editUserForm.php
				
			$tblRows = $tblRows."<td>$UserName</td>";
			
			if($ThisUserAdmin){$tblRows = $tblRows."<td>$FirstName</td>
                                            		<td>$LastName</td>
                                            		<td>$EmailAdress</td>
                                            		<td>$StreetAdress</td>
                                            		<td>$City</td>
                                            		<td>$State</td>
                                            		<td>$Zip</td>
                                            		<td>$Admin</td>
                                            		<td>$TrustedUser</td>
                                            		<td>$ParentID</td>
                                            		<td>$ForumAcess</td>
                                            		<td>$RejectedPosts</td>";
			    
			$tblRows = $tblRows."</tr>";
			}
		}
		
		if($ThisUserAdmin){$adminCols = "
		<th>First Name</th>
		<th>Last Name</th>
		<th>Email Adress</th>
		<th>Street Adress</th>
		<th>City</th>
		<th>State</th>
		<th>Zip</th>
		<th>Admin</th>
		<th>Trusted User</th>
		<th>Parent ID</th>
		<th>Forum Acess</th>
		<th>Rejected Posts</th>";} else {$adminCols="";}
		
		$output = "
        <div class=\"title\">
		<h2  class=\"title\">User List</h2>
		</div>
        <table class=\"styled-table\">\n
		<tr>
		<th></th>
		<th></th>
		<th>UserName</th>$adminCols
		
	    </tr>\n".$tblRows.
		"</table>\n";
					
		$stmt->close();
	} else {

		$output = "Query to retrieve product information failed.";
	
	}

	$conn->close();
?>
	
		

<div class='flexboxContainer'>
    <div>
        <h2>User List</h2>
        <?php echo $output ?>
    </div>
</div>
</main>

<?php print $Footer; ?>

</body>
</html>
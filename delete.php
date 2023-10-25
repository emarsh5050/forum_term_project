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
	<h1><?= $SubTitle_Admin ?></h1>
</header>

<script>
function confirmDel(title, lid) {
// javascript function to ask for deletion confirmation

	url = "admin_delete.php?lid="+lid;
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
	$sql = "SELECT Links.LinkID, Links.URL, Links.AnchorText, LinkCategory.Name FROM `Links`, `LinkCategory` WHERE Links.CID = LinkCategory.CID order by $sort $order";
	
	/*SELECT product.cid, product.Title, category.CategoryName FROM product, category where product.CategoryID = category.CategoryID order by category.CategoryName*/

	$stmt = $conn->stmt_init();

	if ($stmt->prepare($sql)){

		$stmt->execute();
		$stmt->bind_result($LID, $URL, $AnchorText, $CategoryName);
	
		$tblRows = "";
		while($stmt->fetch()){
            $AnchorText_js = str_replace('"', "'", $AnchorText); // replace double quotes by the single quote in the title to avoid trouble in the javascript function.
			$AnchorText_js = htmlspecialchars($AnchorText_js, ENT_QUOTES); // convert quotation marks in the product title to html entity code.  This way, the quotation marks won't cause trouble in the javascript function call ( href='javascript:confirmDel ...' ) below.  

			$tblRows = $tblRows."<tr><td><a href=$URL>$AnchorText</a></td>
								 <td>$CategoryName </td>
							     <td><a href='admin_form.php?lid=$LID'>Edit</a> | <a href='javascript:confirmDel(\"$AnchorText_js\",$LID)'>Delete</a> </td></tr>";
		}
		
		$output = "
        <table class='itemList'>\n
		<tr><th>Title <span><a href='?sort=title&order=a' title='Sort by title ascending'>&darr;</a></span> <span><a href='?sort=title&order=d' title='Sort by title descending'>&uarr;</a></span></th><th>Category <span><a href='?sort=CategoryID&order=a' title='Sort by category ascending'>&darr;</a></span> <span><a href='?sort=CategoryID&order=d' title='Sort by category descending'>&uarr;</a></span></th><th>Options</th></tr>\n".$tblRows.
		"</table>\n";
					
		$stmt->close();
	} else {

		$output = "Query to retrieve product information failed.";
	
	}

	$conn->close();
?>
	
		

<div class='flexboxContainer'>
    <div>
        <h2>Product List</h2>
        <?php echo $output ?>
    </div>
</div>
</main>

<?php print $PageFooter; ?>

</body>
</html>
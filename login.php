<?php
session_start();

if (isset($_GET['logout'])){
    $_SESSION['access'] = false;
}

include("dbconn.inc.php"); // database connection 
include("shared.php");
$conn = dbConnect();

// check to see if there's a form submission of user name and password
if (isset($_POST['username']) && isset($_POST['password']) && !empty($_POST['username']) && !empty($_POST['password'])){

    $username = $_POST['username'];
    $password = $_POST['password'];
    
	$sql = "SELECT `UID`, `UserName`, `Password`, `ForumAcess`, `Admin`, `TrustedUser` FROM `Users`";

	$stmt = $conn->stmt_init();
	
	$found = false;

	if ($stmt->prepare($sql)) {
		
		
		/* execute statement */
		$stmt->execute();

		/* bind result variables */
		$stmt->bind_result($UID, $UserName, $Password, $ForumAcess, $Admin, $TrustedUser);

		/* fetch values */
		while ($stmt->fetch()) { // there should be only one record, therefore, no need for a while loop
		//echo ("UID: $UID, UN: $UserName, P: $Password, $ForumAcess, $Admin");
    		
            if($username == $UserName && $Password==$password){
                
    		    $_SESSION['access'] = true;
    			$_SESSION['UID'] = $UID;
    			$_SESSION['Admin'] = $Admin;
    			$_SESSION['TUser'] = $TrustedUser;
    			$found = true;
    			
            	if(!$Admin){header("Location: admin_threadList.php");}
            	else{header("Location: unapproved.php");}
            	exit;
    		}
        
            $UNfound = 0;
        
            if($username == $UserName){
               $UNfound = 1;
    
    		} 
    		 
    	
		}
		
		if($UNfound == 0){	$message = "Your username is found but your password appears to be incorrect. Please try to fix it.<br>";}
		else{ $message = "This login is not found<br>";}
		
		$message .=  "If you need your username or password reset please contact our user support or come by the Living Science Center in person.";
		
	} else {
		print ("<div class='error'>Query failed</div>");
	}

    
    /*if ($username == "ctec" && $password == "4321") {
        // grant access
        $_SESSION['access'] = true;
    	header('Location: admin_productList.php');
    	exit;
        //$message = "Login Sucess";
    } else {
        // error message
        $message = "<div class='error'>The user name and password you provided are incorrect.  Please try again.</div>";
    }*/

} else if (isset($_POST['username']) || isset($_POST['password'])){
    $message = "<div class='error'>Please enter both the user name and password to log in.</div>";
    
}else {
    $message = "<div class=\"center\">Please use the form below to log in to the forum</div>";
    
}
?>    
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<script src="https://kit.fontawesome.com/d56145ddde.js">
	</script>
	
	<body>

	<main>

    <div class="title">
		<h2  class="title">Log In</h2>
	</div>

    <div class="center">
        <?= $message ?>
    <form action="" method="post">
        User name: <input type='text' name="username"> <br>
        Password: <input type='password' name="password">
        <br>
        <input type="submit" name="submit" value="Log in">        
    </form>
    <p>If you don't have an account please come visit us at 703 NW Green Oaks Blvd, Arlington, TX 76006 any time 9am-5pm. All users must visit in person to register for security reasons.</p>
    </div>
    
    </main>
    </body>



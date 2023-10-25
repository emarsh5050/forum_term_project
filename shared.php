<?php
// store shared information in this file, such as headers, menu, and footers

//HTML Header
$HTMLHeader = 

<<<OUT
<!DOCTYPE html>
<html>
<head>
	<title>CTEC4321 Code Example</title>
	<link rel='stylesheet' href='https://cyjang.utasites.cloud/ctec4321/lab/productList2/style.css' type='text/css'>
</head>

    <style>
	    .nav {
	        list-style-type: none;
	        display: inline-block;
	    }
	    .center{
	        margin: auto;
	        width: 80%;
	    }
	    
	    #textboxid
        {
            height:50px;
            font-size:14pt;
            resize:both;
        }
	</style>
<body>

OUT;

//Course identifier
$course = "<div class='course'>CTEC 4321 Code Example</div>";

// Page title
$SubTitle_Admin = "<h1>Admin Link Management</h1>";

$admin_nav = 
        <<<OUTPUT
	<header>
		<nav>
			<a class="navbar-brand" href="index.php">words</a> <button class="navbar-toggler" data-target="#main-navigation" type="button"><i class="fas fa-hamburger"></i></button>
			<ul class="navbar" id="main-navigation">
				<li>
					<a href='NewThreadForm.php' title="Add a new thread">Add a new Thread</a>
				</li>
				
				<li class="dropdown">
					<a href="#" title="Visit">Users</a>
					<ul>
						<li>
							<a href='newUserForm.php' title="Add a new user">Add a new User</a>
						</li>
					    <li>
							<a href='admin_userList.php' title="Add a new user">List all users</a>
						</li>
					</ul>
				</li>
				
				<li>
					<a href='admin_threadList.php' title="Add a new thread">List all approved or rejected/ removed threads</a>
				</li>
				
		    	<li>
						<a href='unapproved.php' title="Home">Pending Approval</a>
				</li>
				
				<li>
					<a href="editMe.php" title="Calender">Edit My Info</a>
				</li>
			    
				<li>
					<a href="login.php?logout" title="Calender">Log out</a>
				</li>
			</ul>
		</nav>
	</header>
	
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<script src="https://kit.fontawesome.com/d56145ddde.js">
	</script>
OUTPUT;

    

    $normal_nav = 
<<<OUTPUT
	<header>
		<nav>
			<a class="navbar-brand" href="index.php">words</a> <button class="navbar-toggler" data-target="#main-navigation" type="button"><i class="fas fa-hamburger"></i></button>
			<ul class="navbar" id="main-navigation">
				<li>
					<a href='NewThreadForm.php' title="Add a new thread">Add a new Thread</a>
				</li>

				<li>
					<a href='admin_threadList.php' title="Add a new thread">Home</a>
				</li>
				<li>
					<a href='admin_userList.php' title="Add a new user">List all users</a>
				</li>
				<li>
					<a href="editMe.php" title="Calender">Edit My Info</a>
				</li>
				<li>
					<a href="login.php?logout" title="Calender">Log out</a>
				</li>
			</ul>
		</nav>
	</header>
	
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<script src="https://kit.fontawesome.com/d56145ddde.js">
	</script>
OUTPUT;
                




//Page Footer
$PageFooter = "
<footer>
	<a href='http://cyjang.utasites.cloud/ctec4321/'>Back to the course site</a>
</footer>
";



$Nav = 
        <<<OUTPUT
	<header>
		<nav>
			<a class="navbar-brand" href="index.php">words</a> <button class="navbar-toggler" data-target="#main-navigation" type="button"><i class="fas fa-hamburger"></i></button>
			<ul class="navbar" id="main-navigation">
				<li class="dropdown">
					<a href="#" title="about">About</a>
					<ul>
						<li>
							<a href="index.php" title="Partners">Partners</a>
						</li>
						<li>
							<a href="index.php" title="Directors">Directors</a>
						</li>
						<li>
							<a href="index.php" title="Staff">Staff</a>
						</li>
					</ul>
				</li>
			
			    <li>
					<a href="index.php" title="Calender">Calender</a>
				</li>
			
				<li class="dropdown">
					<a href="#" title="Visit">Visit</a>
					<ul>
						<li>
							<a href="index.php" title="PhotographyPolicy">Photography Policy</a>
						</li>
						<li>
							<a href="index.php" title="GroupVisits">Group Visits</a>
						</li>
						<li>
							<a href="index.php" title="AcornsGiftShop">Acorns Gift Shop</a>
						</li>
						<li>
							<a href="index.php" title="ParkInformation">Park Information</a>
						</li>
						<li>
							<a href="index.php" title="DiscoveryRoom">Discovery Room</a>
						</li>
						<li>
							<a href="index.php" title="Exibit">Exibits</a>
						</li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" title="Visit">Education</a>
					<ul>
						<li>
							<a href="index.php" title="Nature School">Nature School</a>
						</li>
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" title="Contact">Contact</a>
					<ul>
						<li>
							<a href="index.php" title="Contact">Contact</a>
						</li>
						<li>
							<a href="index.php" title="Jobs">Jobs</a>
						</li>
						<li>
							<a href="index.php" title="Newsletter">Newsletter</a>
						</li>
					</ul>
				</li>
                <li class="dropdown">
					<a href="#" title="supportUs">Support Us</a>
					<ul>
						<li>
							<a href="index.php" title="Donate">Donate</a>
						</li>
						<li>
							<a href="index.php" title="VolunteerSignUp">Volunteer Sign Up</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="index.php" title="VenueRentals">Venue Rentals</a>
				</li>
				<li>
					<a href="index.php" title="Blog">Blog</a>
				</li>
				<li>
					<a href="login.php" title="Forum">Forum</a>
				</li>
				<li>
				    <a class="btn btn-secondary" href="" target="_blank">Donate today!</a>
				</li>
			</ul>
		</nav>
	</header>
OUTPUT;

$Footer = 
        <<<OUTPUT
    <footer>
     <div class="row">
			<div class="col-xs-12">
				<p class="text-center">This site is produced as a class project. It has no affiliation with the River Legacy Living Science Center.</p>
			</div>
		</div>
    </footer>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js">
	</script> 
	<script src="js/app.js">
	</script>
OUTPUT;


?>
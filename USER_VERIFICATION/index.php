<?php 
	require_once '../CONTROLLERS/authorise.php'; 
	
	// VERIFY USER USING TOKEN
	if (isset($_GET['token'])) {
		$token = $_GET['token'];
		verifyUser($token);
	}
	
	// IF USER IS LOGGED IN - ACCESS GRANTED
	if (!isset($_SESSION['id'])) {
		header('location: ../home.html');
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>@Nossie - Welcome</title>
	<link rel="stylesheet" type="text/css" href="../CSS/styles.css">
</head>
<body>
	<header id="main-header">		
		<div class="container">
			<div class="header-nossie">
				<img src="../images/nossie50.png" alt="Logo" />
				<h1><a href="../home.html">@Nossie</a></h1>	
			</div>
		</div>		
	</header>
	
	<nav id="navbar">
		<div class="container">
			<ul id="navigation">
				<li><a href="login.php">Welcome, <?php echo $_SESSION['username']; ?></a></li>
				<li class="button-box"><a id="register" href="index.php?logout=1">Logout</a></li>
			</ul>	
			<ul id="about">
				<li><a href="#">About</a></li>
				<li><a href="#">Save a Nossie</a></li>
			</ul>	
			
		</div>		
	</nav>
	
	<div class="container-login">
		<!-- BASIC USER INPUT -->			
		<form class="user-registration"  action="index.php" method="POST">
			<h2>Verification</h2>		
			
			<?php if(isset($_SESSION['message'])): ?>
				<div class="alert-warning">
					<p><?php 
						echo $_SESSION['message']; 
						unset($_SESSION['message']);
					?></p>
				</div>
			<?php endif; ?>	
			
			<?php if(!$_SESSION['verified']): ?>
				<div class="form-group">
					<p id="Verify">You need to verify your account!  Sign in to your email account and click on the verification link we just emailed you at <strong><?php echo $_SESSION['email']; ?></strong></p>
				</div>	
			<?php endif; ?>
			
			<?php if($_SESSION['verified']): ?>
				<input class="button" type="button" value="I am Verified!" name="btnVerify">	
			<?php endif; ?>			
		</form>
	</div>	
	
</body>
</html>
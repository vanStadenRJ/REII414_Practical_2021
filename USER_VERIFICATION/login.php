<?php
	require_once '../CONTROLLERS/authorise.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>@Nossie - Login Page</title>
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
				<li>Don't have an account?</li>
				<li class="button-box"><a id="register" href="signup.php">Register</a></li>
			</ul>	
			<ul id="about">
				<li><a href="#">About</a></li>
				<li><a href="#">Save a Nossie</a></li>
			</ul>	
			
		</div>		
	</nav>



	<!-- BASIC USER INPUT -->	
	<div class="container-login">
		<!-- BASIC USER INPUT -->			
		<form class="user-registration"  action="login.php" method="POST">
			<h2>Sign In</h2>
			
			<?php if(count($errors) > 0): ?>
				<div class="alert-warning">
					<?php foreach($errors as $error): ?>
						<li><?php echo $error ?></li>
					<?php endforeach; ?>
				</div>				
			<?php endif; ?>	
			
			<div class="form-group">
				<label>Username or Email: </label>
				<input type="text" value="<?php echo $username; ?>" name="txtUsername">
			</div>	
			<div class="form-group">
				<label>Password: </label>
				<input type="password" name="txtPassword">
			</div>
			<input class="button" type="submit" value="Sign In" name="btnLogin">
		</form>
	</div>
	
	<!-- NO FAULTY RESUBMISSION ERRORS -->
	<script>
		if ( window.history.replaceState ) {
			window.history.replaceState( null, null, window.location.href );
		}
	</script>
</body>
</html>
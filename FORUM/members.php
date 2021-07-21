<?php 
	// USER VERIFICATION 
	require_once '../CONTROLLERS/authorise.php'; 

	// SESSION MANAGEMENT
	require '../CONTROLLERS/session.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>@Nossie - Members Page</title>
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
				<li><?php echo " <form id='linkform' method='post' action='profile.php' class='inline'>
									<input type='hidden' name='id' value='".$_SESSION['id']."'>
									<button type='submit' name='submit_param' value='submit_value' id='link-buttons'>Welcome, ".$_SESSION['username']."</button>
								</form> "; ?></li>
				<li class="button-box"><a id="register" href="../USER_VERIFICATION/index.php?logout=1">Logout</a></li>
			</ul>	
			<ul id="about">
				<li><?php require '../FORUM/header.php'; ?></li>
			</ul>	
		</div>		
	</nav>
	
	<!-- MEMBER FUNCTIONALITY -->		
	
	<div class = "container2" >
		<div class = "container-login" >
			
			<?php
				
				echo "<center>";
				echo "<h1>Members List</h2>";
				// Check if user is registered!		
				$sql = "SELECT * FROM users";
				$res = mysqli_query($mysqli, $sql);
				$rows = mysqli_num_rows($res);
				
				
				while ($row = mysqli_fetch_assoc($res)) {
					$id = $row['user_ID'];
					echo "	<form id='linkform' method='post' action='profile.php' class='inline'>
					</br>
								<input type='hidden' name='id' value='".$id."'>
								<button type='submit' name='submit_param' value='submit_value' class='link-button'>".$row['username']."</button>
							</form> ";
					
					
					
					//<a href='profile.php?id=$id'>""</br>";
				}
				
				echo "</center>"
			?>
			
			
		</div> 
		
	</div> 
	</br></br></br>
	
		
</body>
</html>
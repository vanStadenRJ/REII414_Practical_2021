<?php 
	// USER VERIFICATION 
	require_once '../CONTROLLERS/authorise.php'; 

	// SESSION MANAGEMENT
	require '../CONTROLLERS/session.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>@Nossie - Forum Page</title>
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
	
	<!-- USER VERIFIED - TAKE TO HOMEPAGE! -->	
	<?php if($_SESSION['verified']): ?>
		
		<html>
			<head>
				
			</head>
			<body>				
				<!-- REDIRECT TO POST PAGE -->
				
				<center>
					<br/>
					<a href="../FORUM/create_forum.php"><button class = 'button_topic'>Create a New Forum</button></a>
					
					<br/><br/>
					<?php echo '<table border="1px;" class = "tables">' ?>
						<tr>
							<td style="text-align: center" ><span>FORUM</span></td>
							<td style="text-align: center;">NAME</td>
							<td style="text-align: center">TOPICS</td>
							<td style="text-align: center;">CREATOR</td>
							<td style="text-align: center;">DATE</td>
						</tr>
					
					<?php
						// POPULATE TABLE WITH EXISTING TOPICS!
						$sql = mysqli_query($mysqli, "SELECT * FROM forum_tbl");
						
						if(mysqli_num_rows($sql) != 0) {
							
							while($row = mysqli_fetch_assoc($sql)) {
								echo "<tr>";
								
								// FORUM ID
								$forum_id = $row['forum_ID'];
								echo "<td style='text-align: center'>".$forum_id."</td>";
								
								// FORUM NAME
								$forum_type = $row['type_id'];
								$query = mysqli_query($mysqli, "SELECT * FROM forum_type_tbl WHERE type_id = '$forum_type'");
								$r = mysqli_fetch_assoc($query);
								echo "<td style='text-align: center'><a href='../FORUM/index_topic.php?id=$forum_id'> &#x".$r['type_emoji']." ".$row['forum_name']."</a></td>";
								
								// .$row['forum_name']."
								
								// NR OF TOPICS UNDER FORUM
								$query = mysqli_query($mysqli, "SELECT count(*) count FROM threads_tbl WHERE forum_id = '$forum_id'");
								$r = mysqli_fetch_assoc($query);
								
								echo "<td style='text-align: center'>".$r['count']."</td>";
								
								// FORUM CREATORS
								$forum_creator = $row['forum_creator'];
								$query = mysqli_query($mysqli, "SELECT username FROM users WHERE user_ID = '$forum_creator'");
								$creator = mysqli_fetch_assoc($query);
								
								echo "<td style='text-align: center'>".$creator['username']."</td>";
								
								// DATE POSTED
								echo "<td style='text-align: center'>".gmdate("Y-m-d  H:i:s", $row['forum_date'])."</td>";
							}							
						}
						
						echo '</table>';
					?>
				</center>
				
			</body>
		</html>		
		
	<?php endif; ?>	
	
	<!-- USER NOT YET VERIFIED - ASK TO VERIFY -->	
	<?php if(!$_SESSION['verified']): ?>
		<div class = "container2">
			<div class="container-login">		
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
				</form>		
			</div>	
		</div>
	<?php endif; ?>		
</body>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
</br>
<footer id="main-footer">
		<p>Copyright &copy; 2021 Nossie Forum</p>
</footer>
</html>
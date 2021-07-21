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
	<title>@Nossie - Home Page</title>
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
				<li><?php echo '<a href="../FORUM/profile.php?id=' . $_SESSION['id'] . '">Welcome, ' . $_SESSION['username'] . '</a>'; ?></li>
				<li class="button-box"><a id="register" href="index.php?logout=1">Logout</a></li>
			</ul>	
			<ul id="about">
				<li><a href="#">About</a></li>
				<li><a href="#">Save a Nossie</a></li>
			</ul>	
			
		</div>		
	</nav>
	
	<!-- USER VERIFIED - TAKE TO HOMEPAGE! -->	
	<?php if($_SESSION['verified']): ?>
		
		<html>
			<head>
				
			</head>
			<body>
				<?php require '../FORUM/header.php'; ?>
				
				<!-- REDIRECT TO POST PAGE -->
				
				<center>
					<br/>
					<a href="../FORUM/post.php"><button>Post Topic</button></a>
					
					<br/><br/>
					<?php echo '<table border="1px;">' ?>
						<tr>
							<td><span>THREAD</span></td>
							<td width="400px;" style="text-align: center;">TOPIC</td>
							<td width="80px;" style="text-align: center">VIEWS</td>
							<td width="200px;" style="text-align: center;">CREATORS</td>
							<td width="200px;" style="text-align: center;">DATE</td>
						</tr>
					
					<?php
						// POPULATE TABLE WITH EXISTING TOPICS!
						$sql = mysqli_query($mysqli, "SELECT * FROM threads_tbl");
						
						if(mysqli_num_rows($sql) != 0) {
							
							while($row = mysqli_fetch_assoc($sql)) {
								echo "<tr>";
								
								// TOPIC ID
								$topic_id = $row['topic_id'];
								echo "<td style='text-align: center'>".$topic_id."</td>";
								
								// THREAD NAME
								echo "<td style='text-align: center'><a href='../FORUM/topic.php?id=$topic_id'>".$row['topic_name']."</a></td>";
								
								// TOTAL CLICKS
								echo "<td style='text-align: center'>".$row['topic_views']."</td>";
								
								// CREATORS
								$topic_creator = $row['topic_creator'];
								$query = mysqli_query($mysqli, "SELECT username FROM users WHERE user_ID = '$topic_creator'");
								$creator = mysqli_fetch_assoc($query);
								
								echo "<td>".$creator['username']."</td>";
								
								// DATE POSTED
								$timestamp=1333699439;
								echo "<td>".gmdate("Y-m-d  H:i:s", $row['topic_date'])."</td>";
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
	<div class="container2">
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
</html>
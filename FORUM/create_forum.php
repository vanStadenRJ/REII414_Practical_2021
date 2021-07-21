<?php 
	// USER VERIFICATION 
	require_once '../CONTROLLERS/authorise.php'; 

	// SESSION MANAGEMENT
	require '../CONTROLLERS/session.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>@Nossie - Create New Forum</title>
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
	
	<div class="container2">
		
		<div class = "container-login">
			<!-- USER VERIFIED - TAKE TO HOMEPAGE! -->	
			<?php if($_SESSION['verified']): ?>
				
				<!-- POST FORM -->
				<form id="smileys" action="create_forum.php" method="post">
					<center>
						</br>
						<h3>New Forum Name:</h3> <input type="text" name="forum_name" style="width: 400px"></br>
						</br>
						<h3>Choose a Forum-Moji:</h3>
						<input type="radio" name="smiley" value="1" class="smile">
						<input type="radio" name="smiley" value="2" class="heart">
						<input type="radio" name="smiley" value="3" class="tree" >
						<input type="radio" name="smiley" value="4" class="rhino" checked="checked"></br></br>
						</br><input type="submit" name="submit" value="Create Forum" class = "button-box" style="width: 400px;">
						</br>
						</br>
						</br>
					</center>
				</form>


				<?php
					if(isset($_POST['submit'])) {
						// VERIFY THAT A FORUM NAME HAS BEEN INSERTED
						if(isset($_POST['forum_name'])) {
							// VERIFY LENGTH OF TOPIC Name
							if(strlen($_POST['forum_name']) >= 1 && strlen($_POST['forum_name']) <= 25){
								$sql = "INSERT INTO forum_tbl(forum_name, forum_creator, forum_date, type_id) VALUES ('".addslashes($_POST['forum_name'])."', '".addslashes($_SESSION['id'])."', UNIX_TIMESTAMP(), '".addslashes($_POST['smiley'])."')";
								$res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
								if($res) {
									echo "Success!";
									header ('location: ../FORUM/index_forum.php');
								}
							}
							else {
								echo "Forum name must be between 1 and 25 characters!";
							}
						}				
					}
				?>
				
			<?php endif; ?>	
			
			<!-- USER NOT YET VERIFIED - ASK TO VERIFY -->	
			<?php if(!$_SESSION['verified']): ?>
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
			<?php endif; ?>	
		</div>
	</div>	
</body>

<footer id="main-footer">
		<p>Copyright &copy; 2017 Nossie Forum</p>
</footer>

</html>
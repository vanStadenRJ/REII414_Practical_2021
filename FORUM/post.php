 <?php 
	// USER VERIFICATION 
	require_once '../CONTROLLERS/authorise.php'; 

	// SESSION MANAGEMENT
	require '../CONTROLLERS/session.php';
	
	if(isset($_GET['poll']) || isset($_GET['choices'])) {
		$topic_name = $_GET['topic_name'];
		$topic_content = $_GET['content'];
	}
	else {
		$topic_name = "";
		$topic_content = "";
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
	
	<div class = "container2" >
		<div class = "container-login" >
			<!-- USER VERIFIED - TAKE TO HOMEPAGE! -->	
			<?php if($_SESSION['verified']): ?>
				
				<html>
					<head>
						
					</head>
					<body>
						<!-- POST FORM -->
						<form action="post.php" method="get">
							<center>
								</br>
								Topic Name: </br>
								<input type="text" value="<?php echo $topic_name; ?>" name="topic_name" style="width: 70%"></br>
								</br>
								Content: </br>
								<textarea name="content" style="resize: none; width: 70%; height: 300px;"><?php echo $topic_content; ?></textarea>
								</br>
								<input type="hidden" step="1" name="forum_id" value="<?php echo $_GET['id']; ?>">
								<input type="hidden" step="1" name="id" value="<?php echo $_GET['id']; ?>">								
								
								<?php
									if(!((isset($_GET['poll'])) || (isset($_GET['choices'])))) {
										?>
										</br>
										<input type="submit" name="poll" value="Create Poll" class = "button_topic" style = "width: 71.5%">
										</br> </br>
										<input type="submit" name="submit" value="Post Topic" class = "button_topic" style = "width: 71.5%"> </br></br>
										<?php
									}
									
									if(isset($_GET['poll'])) {
										?>
										Nr of Choices: </br>
										<input type="hidden" step="1" name="id" value="<?php echo $_GET['id']; ?>">
										<input type="number" name="poll_choices" min="1" max="5" value="1"></br> </br>							
										<input type="submit" name="choices" value="Configure Poll" class = "button_topic" style = "width:71.5%">
										<?php
									}
									
									if(isset($_GET['choices'])) {
										?>
										<br/>
										Start Date: </br>
										<input type="date" value="<?php echo date('Y-m-d'); ?>" name="start">
										</br>
										End Date: </br>
										<input type="date" value="<?php echo date('Y-m-d'); ?>" name="end">
										</br></br>
										Poll Question: </br>
										<input type="text" name="poll_question" style="width: 70%"></br>
										</br>
										<?php
											for ($x = 1; $x <= $_GET['poll_choices']; $x++) {
												echo "<br>Choice ".$x."  ";
												echo "</br><input type='text' name=$x style='width: 70%'></br>";
											}
										?>								
										<input type="hidden" step="1" name="poll_choices" value="<?php echo $_GET['poll_choices']; ?>">
										<input type="hidden" step="1" name="forum_id" value="<?php echo $_GET['id']; ?>">
										</br>
										<input type="submit" name="submit" value="Post Topic" class = "button_topic" style = "width: 71.5%;" >
										</br>
										<?php
									}
								?>
								
														
								
							</center>
						</form>
					</body>
				</html>	

				<?php			
					if(isset($_GET['submit'])) {
						// VERIFY THAT TOPICS HAVE BEEN ENTERED!
						if(isset($_GET['topic_name']) && isset($_GET['content'])) {
							// VERIFY LENGTH OF TOPIC Name
							$topic_name = $_GET['topic_name'];
							$topic_content = $_GET['content'];
							if(strlen($_GET['topic_name']) >= 10 && strlen($_GET['topic_name']) <= 100) {
								// VERIFY LENGTH OF CONTENT
								if(strlen($_GET['content']) > 1) {							
									$sql = "INSERT INTO threads_tbl(topic_name, topic_content, topic_creator, topic_date, forum_id) VALUES ('".addslashes($_GET['topic_name'])."', '".addslashes($_GET['content'])."', '".addslashes($_SESSION['id'])."', UNIX_TIMESTAMP(), '".addslashes($_GET['forum_id'])."')";
									$res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
									if($res) {
										// Redirect to List of Topics
										//echo "Success!";
										
										// CHECK IF POLL IS ADDED
								
										if(!$_GET['poll_question'] == '') {									
											$x = 1;
											$y = 0;
											while($x <= $_GET['poll_choices']) {
												if($_GET[$x] == '') {
													$y = 1;
												}										
												$x = $x + 1;
											}
											if($y != 1){
												// GET post_ID of most recent post
												$sql = "SELECT topic_id FROM threads_tbl ORDER BY topic_id DESC LIMIT 1";
												$res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
												$row = mysqli_fetch_assoc($res);
												
												// INSERT POLL QUESTION IN TABLE
												$sql = "INSERT INTO POLLS(poll_Question, poll_Start, poll_End, user_ID, post_ID) VALUES ('".addslashes($_GET['poll_question'])."', '".addslashes($_GET['start'])."', '".addslashes($_GET['end'])."', '".addslashes($_SESSION['id'])."', '".addslashes($row['topic_id'])."')";
												$res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
												if($res) {
													// INSERT POLL CHOICES IN TABLE
													$sql = "SELECT poll_ID FROM POLLS ORDER BY poll_ID DESC LIMIT 1";
													$res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
													$row = mysqli_fetch_assoc($res);
													for($x = 1; $x <= $_GET['poll_choices']; $x++) {
														$sql = "INSERT INTO POLL_OPTIONS(poll_ID, option_Choice) VALUES ('".addslashes($row['poll_ID'])."', '".addslashes($_GET[$x])."')";
														$res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
													}	
													//header ('location: ../FORUM/index_topic.php?id='.$_GET['forum_id'].'');
													//exit;											
												}									
											}
										}										
										header ('location: ../FORUM/index_topic.php?id='.$_GET['forum_id'].'');
										exit;
									}
									else {
										echo "Fail DB!";
									}
								}
								else {
									echo "No content added!";
								}
							}
							else {
								echo "Topic Name must be between 10 and 100 characters long!";
							}
						}
						else {
							echo "Ensure topic name and content fields are specified!";
						}
					}
					else {
						//echo "Fail!";
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
</html>
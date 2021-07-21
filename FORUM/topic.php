<?php 
	// USER VERIFICATION 
	require_once '../CONTROLLERS/authorise.php'; 

	// SESSION MANAGEMENT
	require '../CONTROLLERS/session.php';
?>

<!DOCTYPE html>
<html>
<head>
	<?php
		$topic_ID = $_GET['id'];
		
		// UPDATE THE VIEWS OF THE THREAD
		$sql = mysqli_query($mysqli, "UPDATE threads_tbl SET topic_views = topic_views + 1 WHERE topic_id = '$topic_ID'");		
		
		$sql = mysqli_query($mysqli, "SELECT * FROM threads_tbl WHERE topic_ID = '$topic_ID'");
		$row = mysqli_fetch_assoc($sql);
		$topic_Name = $row['topic_name'];
		$user_ID = $row['topic_creator'];
	?>
	<title>(<?php echo $topic_ID; ?>) <?php echo $topic_Name; ?></title>
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
		<body>
		
		<center>
			
			<!-- UPDATE TOPIC VIEWS -->
			
			
			
			<!-- DISPLAY TOPIC NAME -->
			<?php
				$topic_ID = $_GET['id'];
				$sql = mysqli_query($mysqli, "SELECT * FROM threads_tbl WHERE topic_ID = '$topic_ID'");
				$row = mysqli_fetch_assoc($sql);
				
				$topic_Name = $row['topic_name'];				
				$topic_Content = $row['topic_content'];
				$topic_Creator = $row['topic_creator'];
				$topic_Views = $row['topic_views'];
				
				// Display all Related Replies		
			?>
			
			<div class="container3">
				<section class="container3-large">
					<h1 id="topicName"><?php echo $topic_Name; ?></h1>
					<p id="topicContent" align="justify"> <?php echo $topic_Content; ?> </p>
				
					
					<?php
						// GET ALL REPLIES
						$sql = mysqli_query($mysqli, "SELECT count(*) ans FROM threads_tbl WHERE reply_ID = '$topic_ID'");
						$row = mysqli_fetch_assoc($sql);
						for($x = 0; $x < $row['ans']; $x++) {
							// Get user who posted reply!						
							$sql = mysqli_query($mysqli, "SELECT * FROM threads_tbl WHERE reply_ID = '$topic_ID' LIMIT $x,1");
							$r = mysqli_fetch_assoc($sql);
							$nameID = $r['topic_creator'];
							$content = $r['topic_content'];
							$replyID = $r['topic_id'];
							
							$sql = mysqli_query($mysqli, "SELECT username FROM users WHERE user_ID = '$nameID'");
							$p = mysqli_fetch_assoc($sql);
							$name = $p['username'];							
							
							$sql = mysqli_query($mysqli, "SELECT count(*) ans FROM attachments WHERE post_ID = '$replyID'");
							$a = mysqli_fetch_assoc($sql);
							if($a['ans'] != 0) {
								$sql = mysqli_query($mysqli, "SELECT * FROM attachments WHERE post_ID = '$replyID'");
								$v = mysqli_fetch_assoc($sql);
								$file = $v['attach_path'];
								echo "<p id='topicReply' align='justify'>".$name." (".gmdate("Y-m-d  H:i:s", $r['topic_date']).") : ".$content." - <a href='$file'>See Attachment</a></p>";
							}
							else{
								echo "<p id='topicReply' align='justify'>".$name." (".gmdate("Y-m-d  H:i:s", $r['topic_date']).") : ".$content."</p>";
							}					

							
						}
					?>
					</br>
					<?php echo "<a href='../FORUM/reply.php?id=".$topic_ID."'><button class = 'button_topic' style = 'width: 25%' >Post Reply</button></a>";""?>
					
					
				</section>		
					
				<div class="container3-small">
					
					<?php
						// RETRIEVE USER INFORMATION TO BE SHOWN NEXT TO POST
						$sql = mysqli_query($mysqli, "SELECT * FROM users WHERE user_ID = '$user_ID'");
						$row = mysqli_fetch_assoc($sql);
						
						$user_pic = $row['profile_pic'];
						// DISPLAY USER PROFILE PICTURE
						echo "<img id='profile_pic' src=".$user_pic." width='100' height='100'>";
						
						$user_name = $row['username'];
						echo "<p align='justify'>".$user_name."</p>";
						
						$user_joined = $row['reg_date'];
						echo "<p align='justify'>Member Since: ".$user_joined."</p>";
						
						$user_pic = $row['profile_pic'];						
						
						// RETRIEVE NUMBER OF POSTS MADE BY USER
						$sql = mysqli_query($mysqli, "SELECT count(*) count FROM threads_tbl WHERE topic_creator = '$user_ID'");
						$row = mysqli_fetch_assoc($sql);
						$user_posts = $row['count'];
						
						echo "<p align='justify']]]]>Total Posts:  ".$user_posts."</p>";
							
					?>
					
					
				</div>
				
				
					
						<?php
							// Display attached Poll Result
							$viewer = $_SESSION['id'];
							
							// Verify if a POLL exists
							$sql = mysqli_query($mysqli, "SELECT count(*) ans FROM polls WHERE user_ID = '$user_ID' AND post_ID = '$topic_ID'");
							$row = mysqli_fetch_assoc($sql);
										
							if($row['ans'] != 0) {
								echo "<div class = 'container3-small'>";
								// POLL exists, get poll_ID and pollQuestion
								$sql = mysqli_query($mysqli, "SELECT poll_ID, poll_Question FROM polls WHERE user_ID = '$user_ID' AND post_ID = '$topic_ID'");
								$row = mysqli_fetch_assoc($sql);
								$poll_ID = $row['poll_ID'];
								$poll_Question = $row['poll_Question'];		
								echo "<h2>".$poll_Question."</h2>";
								// DETERMINE IF USER HAS ALREADY VOTED
								$sql = mysqli_query($mysqli, "SELECT count(*) ans FROM poll_results WHERE user_ID = '$viewer' AND poll_ID = '$poll_ID'");
								$row = mysqli_fetch_assoc($sql);
								if($row['ans'] == 0){
								// USER RESULT DOES NOT EXIST
								$sql = mysqli_query($mysqli, "SELECT count(*) ans FROM POLL_OPTIONS WHERE poll_ID = '$poll_ID'");
								$options = mysqli_fetch_assoc($sql);
											
								// CREATE A FORM OF RADIO BUTTONS THAT CAN BE USED TO VOTE!
						?>
						<form action="topic.php" method="GET">
							<center>
									</br>PLEASE PLACE YOUR VOTE: </br></br>
									<?php
										for ($x = 0; $x < $options['ans']; $x++) {
											$sql = mysqli_query($mysqli, "SELECT * FROM POLL_OPTIONS WHERE poll_ID = '$poll_ID' LIMIT $x,1");
											$res = mysqli_fetch_assoc($sql);								
											$choice = $res['option_ID'];
											if($x == 0){
															echo "<input type='radio' name='choice' value='$choice' checked='true'>";
															echo "<label for=$x>".$res['option_Choice']."</label></br>";
														}
														else{
															echo "<input type='radio' name='choice' value='$choice'>";
															echo "<label for='one'>".$res['option_Choice']."</label></br>";
														}									
													}
									?>
									<input type="hidden" step="1" name="id" value="<?php echo $_GET['id']; ?>">
									<input type="hidden" step="1" name="topic_id" value="<?php echo $_GET['id']; ?>">
									<input type="hidden" step="1" name="poll_ID" value="<?php echo $poll_ID; ?>">
									</br><input type="submit" name="submit" value="Vote!"  class = "button_topic" >
							</center>
						</form>
						<?php	
									}
							else {
							// USER RESULT DOES EXIST
							// GO THROUGH RESULT, AND TALLY UP VOTES
							$sql = mysqli_query($mysqli, "SELECT count(*) ans FROM POLL_RESULTS WHERE poll_ID = '$poll_ID'");
							$res = mysqli_fetch_assoc($sql);
							$total_res = $res['ans'];	
											
							$sql = mysqli_query($mysqli, "SELECT count(*) ans FROM POLL_OPTIONS WHERE poll_ID = '$poll_ID'");
							$options = mysqli_fetch_assoc($sql);
											
						?><center>
						<table border="1px;" class = "tables" >
						<?php							
											
							for ($x = 0; $x < $options['ans']; $x++) {
									$sql = mysqli_query($mysqli, "SELECT * FROM POLL_OPTIONS WHERE poll_ID = '$poll_ID' LIMIT $x,1");
									$res = mysqli_fetch_assoc($sql);
									$made_choice = $res['option_ID'];
									$name_choice = $res['option_Choice'];
													
									$sql = mysqli_query($mysqli, "SELECT count(*) ans FROM POLL_RESULTS WHERE option_ID = '$made_choice'");
									$res = mysqli_fetch_assoc($sql);
									$answer = $res['ans'];

									$percentage = round(($answer/$total_res)*100, 2);
													
						?><tr>
						<td><span><?php echo $name_choice; ?></span></td>
						<td><?php echo $percentage."%"; ?></td>
						</tr><?php
								}				
						echo '</table></center>';
								}
								echo"</div>";
								}
								
								if (isset($_GET['submit'])){
									// VERIFY THAT USER-RESPONSE HAS NOT BEEN RECORDED!
									$check_user = $_SESSION['username'];
									echo $topic_ID;
									$sql = mysqli_query($mysqli, "SELECT count(*) ans FROM poll_results WHERE user_ID = '$check_user' AND poll_ID = '$poll_ID'") or die(mysqli_error($mysqli));
									$row = mysqli_fetch_assoc($sql);
									echo $row['ans'];
									if($row['ans'] == 0) {
										//echo "ADDED!";
										$sql = "INSERT INTO POLL_RESULTS(user_ID, poll_ID, option_ID) VALUES ('".$_SESSION['id']."', '".$_GET['poll_ID']."', '".$_GET['choice']."')";
										$res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
										if($res) {
											echo "ADDED!";
											header ('location: ../FORUM/topic.php?id='.$topic_ID.'');
											exit;	
										}
									}			
								}
								
							?>
					
				
			</div>
			
			
			<h2><h2>
		</center>
		
		
		
		</body>	
		
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
</body>

<footer id="main-footer">
		<p>Copyright &copy; 2021 Nossie Forum</p>
</footer>
</html>
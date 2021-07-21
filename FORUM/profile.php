<?php 
	// USER VERIFICATION 
	require_once '../CONTROLLERS/authorise.php'; 

	// SESSION MANAGEMENT
	require '../CONTROLLERS/session.php';
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
	</br>
	
	<!-- MEMBER FUNCTIONALITY -->	
	<div class = " fullview" >
		<div class = "container2" >
			<div class = "container-login" >
				<?php
					echo "<center>";
					// CHECK WHETHER VALID ID
					$sql = "SELECT * FROM users WHERE user_ID = '".$_POST['id']."'";
					$res = mysqli_query($mysqli, $sql);
					$rows = mysqli_num_rows($res);
					
					if($rows != 0) {
						$row = mysqli_fetch_assoc($res);
						?><form action="profile.php" method="post" enctype="multipart/form-data"><?php
							echo "<h1>".$row['username']."</h1>";?>
							<?php if($_SESSION['id'] == $_POST['id']):?>					
								<label id="lblFile" for="file_input_id"><img id="profile_pic" src="<?php echo $row['profile_pic']; ?>" width='100' height='100'></label></br>
								<input type="file" name="file_input_id" id="file_input_id">	
							<?php else: ?>
								<img id="profile_pic" src="<?php echo $row['profile_pic']; ?>" width='100' height='100'></br>
							<?php endif; ?>					
							<input type="hidden" name="id" value="<?php echo $_POST['id']; ?>"> <?php
							echo "<b>Member Since: </b>".$row['reg_date']."</br>";			
							echo "<b>Email: </b>".$row['email']."</br>";
							//echo "<b>Replies: </b>".$row['nr_replies']."</br>";
							//echo "<b>Topics Created: </b>".$row['nr_topics']."</br>";
							//echo "<b>User Status: </b>".$row['user_score']."</br></br>";
							
							// Get number of posts
							$sql = "SELECT count(*) ans FROM threads_tbl WHERE topic_creator='".$_POST['id']."'";
							$res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
							$r = mysqli_num_rows($res);
							
							echo $r['ans'];
							
							echo "<b>Number of Posts: ".$r['ans']."</br>";
							
							if($_SESSION['id'] == $_POST['id']):?>					
								<input type="submit" value="Apply Changes" name="submit"></br>		
							<?php endif; ?>	
						</form> <?php
						
						if(isset($_POST["submit"])) {
							$target_dir = "uploads_profile/";	
							$target_file = $target_dir . basename($_FILES["file_input_id"]["name"]);
				
							$uploadOk = 1;
							$file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
							$target_file = $target_dir . hash_file('sha256', $_FILES["file_input_id"]["tmp_name"] . $_POST['id']) . '.' . $file_type;
							
							//$check = getimagesize($_FILES["file_input_id"]["tmp_name"]);
							// CHECK IF FILE IS AN IMAGE:
							//if($check !== false) {					
								//echo "File is an image - " . $check["mime"] . ".";					
								//$uploadOk = 1;
							//} 
							//else {
								//echo "File is not an image.";
								//$uploadOk = 0;
							//}
							
							// CHECK IF FILE ALREADY EXISTS
							if (file_exists($target_file)) {
								//echo "Sorry, file already exists.";
								$uploadOk = 0;
							}

							// VERIFY FILE SIZE
							//if ($_FILES["file_input_id"]["size"] > 5000000) {
								//echo "Sorry, your file is too large.";
								//$uploadOk = 0;
							//}

							// ONLY ALLOW CERTAIN FORMATS
							//if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
								//echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
								//$uploadOk = 0;
							//}

							// IF FILE UPLOAD IS APPROVED - PROCEED
							if ($uploadOk == 0) {
								echo "Sorry, your file was not uploaded.";
							} 
							else {
								if (move_uploaded_file($_FILES["file_input_id"]["tmp_name"], $target_file)) {
									//echo "The file ". htmlspecialchars( basename( $_FILES["file_input_id"]["name"])). " has been uploaded.";
									// DELETE THE OLD PROFILE PICTURE
									if(!is_null($row['profile_pic'])) {
										unlink($row['profile_pic']);
									}
									
									// UPDATE NEW PROFILE PICTURE
									$sql = "UPDATE users SET profile_pic = '".$target_file."' WHERE user_ID = '".$_POST['id']."'";
									$res = mysqli_query($mysqli, $sql);
									
									// REFRESH PAGE
									?>
									
									<script>
										document.getElementById('profile_pic').src='<?php echo $target_file; ?>';
									</script>
									<?php
									//header("Refresh:0");
								} 
								else {
									echo "Sorry, there was an error uploading your file.";
								}
							}
						}
							
					}
					else {
						//header('location: ../home.html');
						echo "YEET ".$_POST['id'];
					}
					echo "</center>";
				?>
				</br>
				</br>
				</br>
				</br>
			</div>
		</div>
	</div>
	
	
		
</body>
<footer id="main-footer">
		<p>Copyright &copy; 2021 Nossie Forum</p>
</footer>
</html>
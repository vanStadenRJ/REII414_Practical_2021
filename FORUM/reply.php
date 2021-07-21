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
	
	<script>
		function _(el){
			return document.getElementById(el);
		}
		function uploadFile(){
			var file = _("file_to_upload").files[0];
			//alert(file.name+" | "+file.size+" | "+file.type);
			var formdata = new FormData();
			formdata.append("file_to_upload", file);
			var ajax = new XMLHttpRequest();
			ajax.upload.addEventListener("progress", progressHandler, false);
			ajax.addEventListener("load", completeHandler, false);
			ajax.addEventListener("error", errorHandler, false);
			ajax.addEventListener("abort", abortHandler, false);
			ajax.open("POST", "file_upload.php");
			ajax.send(formdata);
		}
		function progressHandler(event){
			var percent = (event.loaded / event.total) * 100;
			_("progressBar").value = Math.round(percent);
			_("status").innerHTML = Math.round(percent)+"% uploaded... please wait";
		}
		function completeHandler(event){
			_("status").innerHTML = event.target.responseText;
			_("progressBar").value = 0;
		}
		function errorHandler(event){
			_("status").innerHTML = "Upload Failed";
		}
		function abortHandler(event){
			_("status").innerHTML = "Upload Aborted";
		}
</script>
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
						
						<!-- UPLOAD FORM -->
						<form id="upload_form" enctype="multipart/form-data" method="post">
						  <input type="file" name="file_to_upload" id="file_to_upload"><br>
						  <input type="button" value="Upload File" onclick="uploadFile()">
						  <progress id="progressBar" value="0" max="100" style="width:300px;"></progress>
						  <h3 id="status"></h3>
						  <p id="loaded_n_total"></p>
						</form>
						
						<!-- POST FORM -->
						
						<form action="reply.php" method="get">
							<center>
								</br>
								User Reply: </br></br>
								<textarea name="content" style="resize: none; width: 70%; height: 300px;"><?php echo $topic_content; ?></textarea>
								</br></br>
								<input type="hidden" step="1" name="id" value="<?php echo $_GET['id']; ?>">
								<input type="submit" name="submit" value="Post Reply" class = "button_topic" style="width: 70%;">
								</br></br>					
							</center>
						</form>
					</body>
				</html>	

				<?php			
					if(isset($_GET['submit'])) {
						// VERIFY THAT TOPICS HAVE BEEN ENTERED!
						
						
						
						if(isset($_GET['content'])) {
							// VERIFY LENGTH OF TOPIC Name
							$topic_content = $_GET['content'];
							// VERIFY LENGTH OF CONTENT
							if(strlen($_GET['content']) > 1) {						
								$sql = "INSERT INTO threads_tbl(topic_name, topic_content, topic_creator, topic_date, forum_id, reply_ID) VALUES ('reply', '".addslashes($_GET['content'])."', '".$_SESSION['id']."', UNIX_TIMESTAMP(), '0', '".$_GET['id']."')";
								$res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
								if($res) {
									// Upload Attachment
									if(isset($_SESSION['file_path'])){
										$target_file = $_SESSION['file_path'];
										$u = $_SESSION['id'];
										
										$sql = "SELECT topic_id FROM threads_tbl ORDER BY topic_id DESC LIMIT 1";
										$result = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
										$row = mysqli_fetch_assoc($result);
										$e = $row['topic_id'];										

										$sql = "INSERT INTO attachments (attach_path, user_ID, post_ID) VALUES ('$target_file', '$u', '$e')";
										$result = mysqli_query($mysqli, $sql);

										unset ($_SESSION['file_path']);
									}
									header ('location: ../FORUM/topic.php?id='.$_GET['id'].'');
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

<footer id="main-footer">
		<p>Copyright &copy; 2017 Nossie Forum</p>
</footer>
</html>
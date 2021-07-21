<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

	// SESSION
	session_start();
	
	// CONNECT TO DATABSE
	require '../CONFIG/connect.php';
	require_once 'email_verification.php';

	// INITIALIZE GLOBAL ERROR ARRAY
	$errors = array();
	$username = "";
	$email = "";
	$topic_name = "";
	$topic_content = "";
	
	$target_dir = "yeet";
	
	
	
	// HAS USER CLICKED ON SIGN UP BUTTON
	if (isset($_POST['btnSubmit'])){
		$username = $_POST['txtUsername'];
		$email = $_POST['txtEmail'];
		$password = $_POST['txtPassword'];
		$confirm = $_POST['txtConfirm'];
	
	
		// VALIDATION
		if (empty($username)){											// Is Username Entered
			$errors['username'] = "Username Required";
		}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {				// Is Email Address Valid
			$errors['email'] = "Email Address is Invalid";
		}
		if (empty($email)){												// Is Email Address Entered
			$errors['email'] = "Email Required";
		}

		if (empty($password)){											// Is Password Entered
			$errors['password'] = "Password Required";
		}	
		if (1 !== preg_match('~[0-9]~', $password)) {
			$errors['username'] = "Password must contain at least one number";
		}	
		if (1 !== preg_match("/[a-zA-Z]/i", $password)) {
			$errors['password'] = "Password must contain at least one letter";
		}
		if (1 !== preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) {
			$errors['password'] = "Password must contain a special character";
		}
		if (strlen($password) <= 5) {
			$errors['password'] = "Password must contain at least 6 characters";
		}

		if ($password != $confirm) {									// Is Password Confirmed Entered
			$errors['password'] = "Passwords Do Not Match";
		}

		$emailQuery = "SELECT * FROM users WHERE email=? LIMIT 1";
		$stmt = $mysqli->prepare($emailQuery);
		$stmt->bind_param('s', $email);
		if($stmt->execute()) {
			$result = $stmt->get_result();
			$userCount = $result->num_rows;
			$stmt->close();
		
			if($userCount > 0) {
				$errors['email'] = "Email already exists";
			}
		}
		else {
			$errors['db_error'] = "Error description: " . $mysqli -> error;
		}
		
		
		// ENTER THE USER INTO DATABASE
		if (count($errors) === 0) {
			$password = password_hash($password, PASSWORD_DEFAULT);
			$token = bin2hex(random_bytes(50));
			$verified = 0;
			
			$sql = "INSERT INTO users (username, email, verified, token, password) VALUES ('$username', '$email', '$verified', '$token', '$password')";
			$res = mysqli_query($mysqli, $sql);

			//$stmt = $mysqli->prepare($sql);
			//$stmt->bind_param('ssbss', $username, $email, $, $token, $password);
			
			if ($res) {
				// LOGIN USER
				$user_id = $mysqli->insert_id;
				$_SESSION['id'] = $user_id;
				$_SESSION['username'] = $username;
				$_SESSION['email'] = $email;
				$_SESSION['verified'] = $verified;
				
				// EMAIL VERIFICATION
				sendVerificationEmail($email, $token);
				
				// FLASH MESSAGE
				$_SESSION['message'] = "You are now logged in!";
				$_SESSION['alert-class'] = "alert-success";
				header('location: ../FORUM/index_forum.php');
				exit();
			}
			else{
				$errors['db_error'] = "Error description: " . $mysqli -> error;
			}
		}
	}	
	
	// HAS USER CLICKED ON LOG-IN BUTTON
	if (isset($_POST['btnLogin'])){
		$username = $_POST['txtUsername'];
		$password = $_POST['txtPassword'];	
	
		// VALIDATION
		$bCheck = true;
		if (empty($username)){											// Is Username Entered
			$errors['username'] = "Username Required";
			$bCheck = false;
		}

		if (empty($password)){											// Is Password Entered
			$errors['password'] = "Password Required";
			$bCheck = false;
		}
		
		if ($bCheck === true){
			// LOG IN QUERY
			$sql = "SELECT * FROM users WHERE email=? OR username=? LIMIT 1";
			$stmt = $mysqli->prepare($sql);
			$stmt->bind_param('ss', $username, $umail);
			$stmt->execute();
			$res = $stmt->get_result();
			$user = $res->fetch_assoc();
			
			if (password_verify($password, $user['password'])) {
				// LOGIN SUCCESS
				$_SESSION['id'] = $user['user_ID'];
				$_SESSION['username'] = $user['username'];
				$_SESSION['email'] = $user['email'];
				$_SESSION['verified'] = $user['verified'];
				
				$_SESSION['session_id'] = hash('sha256', time().$_SESSION['id']);
				
				$sql = "INSERT INTO sessions(session_ID, user_ID, session_time) VALUES ('".$_SESSION['session_id']."', '".$_SESSION['id']."', unix_timestamp())";
				$res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
					
				// FLASH MESSAGE
				$_SESSION['message'] = "You are now logged in!";
				$_SESSION['alert-class'] = "alert-success";
				header('location: ../FORUM/index_forum.php');
				exit();
			}
			else {
				$errors['login_fail'] = "Wrong Credentials";
			}
		}		
	}
		
	// IF USER HAS LOGGED OUT
	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['id']);
		unset($_SESSION['username']);
		unset($_SESSION['email']);
		unset($_SESSION['verified']);
		
		header('location: ../home.html');
	}
	
	// VERIFY USER USING TOKEN
	function verifyUser($token)
	{
		global $mysqli;
		$sql = "SELECT * FROM users WHERE token='$token' LIMIT 1";
		$res = mysqli_query($mysqli, $sql);
		
		// IF USER IN TABLE
		if (mysqli_num_rows($res) > 0) {
			$user = mysqli_fetch_assoc($res);
			$update_query = "UPDATE users SET verified=1 WHERE token='$token'";
			
			if (mysqli_query($mysqli, $update_query)) {
				// LOGIN SUCCESS
				$_SESSION['id'] = $user['user_ID'];
				$_SESSION['username'] = $user['username'];
				$_SESSION['email'] = $user['email'];
				$_SESSION['verified'] = 1;
				$_SESSION['session_id'] = hash('sha256', time().$_SESSION['id']);
				
				$sql = "INSERT INTO sessions(session_ID, user_ID, session_time) VALUES ('".$_SESSION['session_id']."', '".$_SESSION['id']."', unix_timestamp())";
				$res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
					
				// FLASH MESSAGE
				$_SESSION['message'] = "Your email address was successfully verified!";
				$_SESSION['alert-class'] = "alert-success";
				header('location: ../FORUM/index_forum.php');
				exit();
			}
			else {
				// User Does Not Exist!
			}
		}
	}	
?>
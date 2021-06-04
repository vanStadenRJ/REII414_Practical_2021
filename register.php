<?php
	// STILL TO-DO:
	/*
		- Email VERIFICATION				
		- PasswordID + SessionID storage
		- Login Page
		- Hashed Passwords
	*/
	
	
	session_start();
	
	// GET INFORMATION
	$strUsername = @$_POST['txtUsername'];
	$strPassword = @$_POST['txtPassword'];
	$strRePassword = @$_POST['txtConfirm'];
	$strEmail = @$_POST['txtEmail'];
	$strDate = date("Y-m-d");
	$bInputChecks = false;
	
	// CONNECT TO DATABASE
	require('connect.php');
		
	if(isset($_POST['btnSubmit'])){	
		// INSERTED ALL INFORMATION
		if($strUsername && $strPassword && $strEmail && $strRePassword){
			// USERNAME CHECKS
			if(strlen($strUsername) >= 5){
				if(strlen($strUsername) <25){
					// EMAIL CHECKS
					if(strpos($strEmail, "@") !== false && strpos($strEmail, " ") == false){
						// PASSWORD CHECKS
						if(1 === preg_match('~[0-9]~', $strPassword)){
							# Contains a number
							if(preg_match("/[a-zA-Z]/i", $strPassword)){
								# Contains an Alphabet Letter
								if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $strPassword)) {
									// CHECK IF PASSWORDS MATCH
									if ($strPassword == $strRePassword) {
										$bInputChecks = true;										
									}
									else {
										echo "Passwords does not match up!";
										$strPassword = '';
										$strRePassword = '';
									}
								}
								else {
									echo "Password must contain a special character";
									$strPassword = '';
									$strRePassword = '';
								}
							}
							else {
								echo "Password must contain a letter";
								$strPassword = '';
								$strRePassword = '';
							}
						}
						else {
							echo "Password must contain a number";
							$strPassword = '';
							$strRePassword = '';
						}
					}
					else{
						echo "Please enter a valid email address";
						$strEmail = '';
					}	
				}
				else {
					echo "Maximum username length: 25 characters";
					$strUsername = '';
				}
			}
			else{
				echo "Minimum username length: 5 characters!";
				$strUsername = '';
			}
		}	
		else {
			echo "Please fill in all the fields.";
		}
		// ONLY IF ALL INPUT FIELDS ARE CORRECT
		if ($bInputChecks == true) {
			// SANATIZE FORM DATA
			$strUsername = $mysqli->real_escape_string($strUsername);
			$strPassword = $mysqli->real_escape_string($strPassword);
			$strEmail = $mysqli->real_escape_string($strEmail);
			
			// ENCRYPT USER PASSWORD
			$strPassword = password_hash($strPassword, PASSWORD_DEFAULT);			
	
			// GENERATE VERIFICATION KEY
			$vkey = md5(time().$strUsername);
			
			// Insert Query to insert values into database
			//$q = "INSERT INTO users_tbl (USERNAME, PASSWORD, EMAIL, REG_DATE) VALUES ('".$strUsername."', '".$hashPW."', '".$strEmail."', '".$strDate."')";
			//$res = mysqli_query($db, $q);
			$res = $mysqli->query("INSERT INTO users_tbl (USERNAME, PASSWORD, EMAIL, VKEY, REG_DATE) VALUES ('".$strUsername."', '".$strPassword."', '".$strEmail."', '".$vkey."', '".$strDate."')");
			if($res){
				echo "NEW USER CREATED";
				$strPassword = '';
				$strRePassword = '';
				$strEmail = '';
				$strUsername = '';
			}	
			else {
				echo "SYSTEM FAILURE";
			}
		}				
	}		
?>

<!DOCTYPE html>
<html>
<head>
	<title>Register an Account</title>
</head>

<body>
	<!-- BASIC USER INPUT -->	
	<form action="register.php" method="POST">
		<table>
			<tr>
				<td>Username:</td>
				<td><input type="text" name="txtUsername" value="<?php echo $strUsername; ?>"></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="txtPassword" value="<?php echo $strPassword; ?>"></td>
			</tr>
			<tr>
				<td>Confirm Password:</td>
				<td><input type="password" name="txtConfirm" value="<?php echo $strRePassword; ?>"></td>
			</tr>
			<tr>
				<td>Email Address:</td>
				<td><input type="text" name="txtEmail" value="<?php echo $strEmail; ?>"></td>
			</tr>
			<tr>
				<td><input type="submit" name="btnSubmit" value="Register"></td>
			</tr>
		</table>
	</form>
	
	<!-- NO FAULTY RESUBMISSION ERRORS -->
	<script>
		if ( window.history.replaceState ) {
			window.history.replaceState( null, null, window.location.href );
		}
	</script>
</body>
</html>
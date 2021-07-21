<?php
	// VERIFY USER USING TOKEN
	if (isset($_GET['token'])) {
		$token = $_GET['token'];
		verifyUser($token);
	}
	
	// IF USER IS LOGGED IN - ACCESS GRANTED
	if (!isset($_SESSION['id'])) {
		header('location: ../home.html');
	}
	else {
		// TEST IF SESSION HAS EXPIRED
		$login_user = $_SESSION['id'];
		$query = mysqli_query($mysqli, "SELECT * FROM sessions WHERE user_ID = '$login_user' ORDER BY session_time DESC LIMIT 1");
		$row = mysqli_fetch_assoc($query);
		
		if (time() - $row['session_time'] > 3600) {
			// If user inactive for more than an hour...
			header('location: ../USER_VERIFICATION/index.php?logout=1');
		}
		else {
			// If user is active, update the sessiontime ensuring user not logged out after an hour
			$id = $_SESSION['session_id'];
			$sql = "UPDATE sessions SET session_time=unix_timestamp() WHERE user_ID = '$login_user' AND session_ID = '$id'";
			$res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
		}
	}
?>
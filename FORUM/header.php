<?php 
	require_once '../CONTROLLERS/authorise.php'; 
	
	// IF USER IS LOGGED IN - ACCESS GRANTED
	if (!isset($_SESSION['id'])) {
		header('location: ../home.html');
	}
?>

<a href="../FORUM/index_forum.php">Forums</a> &nbsp &nbsp &nbsp <a href="../FORUM/members.php">Members</a>
<?php
	//exit;
?>
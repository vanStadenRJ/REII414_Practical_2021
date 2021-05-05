<html>
<head>
	<title>Register an Account</title>
</head>

<body>
	<form action="main.php" method="POST">
		<table>
			<tr>
				<td>Username:</td>
				<td><input type="text" name="txtUsername"></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="txtPassword"></td>
			</tr>
			<tr>
				<td>Confirm Password:</td>
				<td><input type="password" name="txtConfirm"></td>
			</tr>
			<tr>
				<td>Email Address:</td>
				<td><input type="text" name="txtEmail"></td>
			</tr>
			<tr>
				<td><input type="submit" name="btnSubmit" value="Register"></td>
			</tr>
		</table>
	</form>
	<?php
		$strUsername = @$_POST['txtUsername'];
		$strPassword = @$_POST['txtPassword'];
		$strRePassword = @$_POST['txtConfirm'];
		$strEmail = @$_POST['txtEmail'];
		
		if(isset($_POST['btnSubmit'])){
			echo "<br />Username - " .$strUsername;
			echo "<br />Password - " .$strPassword;
			echo "<br />Confirm - " .$strRePassword;
			echo "<br />Email - " .$strEmail;
		}
	?>
</body>
</html>
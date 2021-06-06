<?php
	
	require_once '../vendor/autoload.php';

	// Create the Transport
	$transport = (new Swift_SmtpTransport('smtp.gmail.com', 465, 'ssl'))
	  ->setUsername('savethenossies@gmail.com')
	  ->setPassword('Nossies1234#')
	;

	// Create the Mailer using your created Transport
	$mailer = new Swift_Mailer($transport);
	
	function sendVerificationEmail($email, $token)
	{
		global $mailer;
		
		$body = '
	<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Verify Email</title>
		</head>
		<body>
			<div class="wrapper">
				<p>
					Thank you for signing up to @NOSSIE, where each post made, helps us raise money to save the global rhino population!  Please click the link to verify your email!
				</p>
				<a href="http://127.0.0.1/REII414_Practical_2021/USER_VERIFICATION/index.php?token=' .$token. '">Verify your email address!</a>
			</div>
		</body>
	</html>';
		
		// Create a message
		$message = (new Swift_Message('Verify your email address'))
			->setFrom(['savethenossies@gmail.com' => 'Nossie Renoster'])
			->setTo($email)
			->setBody($body, 'text/html')
			;

		// Send the message
		$result = $mailer->send($message);
	}

?>
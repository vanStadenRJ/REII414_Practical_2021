<?php

	require '../CONFIG/connect.php';
	session_start();
	
	$fileTmpLoc = $_FILES["file_to_upload"]["tmp_name"];
	$target_dir = "testupload/";	
	$target_file = $target_dir . basename($_FILES["file_to_upload"]["name"]);
	
	$uploadOk = 1;
	$file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	$target_file = $target_dir . hash('sha256', $target_file . $_SESSION['id'] . time()) . '.' . $file_type;
		
	if (!$fileTmpLoc) { // if file not chosen
		echo "ERROR: Please browse for a file before clicking the upload button.";
    exit();
}

	if(move_uploaded_file($_FILES['file_to_upload']['tmp_name'], $target_file))
	{
		echo "The file ". basename($_FILES['file_to_upload']['name']). " is uploaded";
	}
	else {
		echo "Problem uploading file";
	}
	
	$_SESSION['file_path'] = $target_file;	
 ?>
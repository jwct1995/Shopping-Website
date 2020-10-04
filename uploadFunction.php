<?php

	//0 = no file
	//2 = error
	//else return file location+name
	$txt=0;
	$now = DateTime::createFromFormat('U.u', microtime(true));
	$d=$now->format("YmdHisu");
	
if($_FILES['img_file']['name'])
{
	if($_FILES['img_file']['size']>0)
	{
		$sourcePath = $_FILES['img_file']['tmp_name']; // Storing source path of the file in a variable
		$name=$_FILES['img_file']['name'];
		$ext= pathinfo($name, PATHINFO_EXTENSION);
		$targetPath = "img/".$d.".".$ext;
		if(move_uploaded_file($sourcePath,$targetPath))  // Moving Uploaded file
			$txt=$targetPath;
		else 
			$txt=2;
	}
	else
		$txt=0;

	echo json_encode($txt);
}



?>
<?php
$POST['payload']="[]";
if(true || isset($_POST['payload']))
{
	echo "Attemping Payload\n";
	$data = json_decode($_POST['payload']);
	if (json_last_error() == JSON_ERROR_NONE) {
		//file_put_contents('logs/github.txt',print_r($data,TRUE));
		echo "Attempt Executing Script\n";
		echo shell_exec('git pull origin master 2>&1')."\n\n";			
		echo "Payload Exit";	
	} else "JSON Error : ".json_last_error();
}
else 
	echo "Invalid Payload";
?>


<?php
/* Defining Variables */
$host="8.8.8.8";
$port="80";
$account_key="werwerwe"; //Nexmo API Key
$account_password="23fer45rdf"; // Nexmo API Secret
$alertto="+00000000";	//Number to Alert To
$alertfrom="Server Status"; 
$alerttext="Server $host is down at "; // We will concatenate date later
$logfile="log.txt";			//This should be writable
$timezone="Asia/Kathmandu";
/* Stop Editing Here */

date_default_timezone_set($timezone);
include ( "NexmoMessage.php" );
$alert = new NexmoMessage ($account_key,$account_password);
$connection = @fsockopen($host,$port);
if (!is_resource($connection)) {
	if(checkstatus()){
	$alerttext .=date(DATE_RFC822);
	$info = $alert->sendText($alertto,$alertfrom,$alerttext);
	/* For Debugging */
	echo $alert->displayOverview($info);
	echo $info;
	}
	else{
	$fh = fopen($logfile,'w');
	fwrite($fh, "Down");
	fclose($fh);
	}
	echo "Server Down";
	
}
else {
	echo "Server Up\n";
	fclose($connection);
	if(file_exists($logfile)){
	unlink($logfile);
	}
}
function checkstatus(){
	if(file_exists($logfile)){
	$fh = fopen($logfile, 'r');
	$status = fread($fh, '4');
	fclose($fh);
	if($status=="Down")
	return false;//Server is already down
	}
	return true;
}
?>
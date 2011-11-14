#!/usr/bin/php -q
<?php
/* Defining Variables */

$host="8.8.8.8";
$port="80";
$account_key="werwer234"; //Nexmo API Key
$account_password="wefrewr23"; // Nexmo API Secret
$alertto="+00000000";	//Number to Alert To
$alertfrom="Server Status"; 
$alerttext="Server $host is down at ";
$logfile="log.txt";			//This should be writable
$checkinterval=120;  //This will check every 2 minutes
$timezone="Asia/Kathmandu";
/* Stop Editing Here */
date_default_timezone_set($timezone);
include ( "NexmoMessage.php" );
$alert = new NexmoMessage ($account_key,$account_password);
while(true){
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
}
sleep($checkinterval);
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
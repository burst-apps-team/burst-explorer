<?php
/*
# Burst Apps Team contribution -https://twitter.com/BurstAppsTeam
# Devolped by Zoh - https://twitter.com/Zoh63392187
# Donations: BURST-NMEA-GRHZ-BRFE-5SG6P
*/
// Secure IP to execute server scripts, functions and menu content
// This will show menu's if you are browsing from this IP.
$secureIP = '';

// Database login details
$db_server = ''; // ip:port
$db_user = ''; // Database username
$db_pass = ''; // Database password
$db_db = ''; // Database name

$db_link = @mysqli_connect($db_server, $db_user, $db_pass) or die('('.mysqli_connect_errno().')');
@mysqli_select_db($db_link, $db_db) or die('(2)');
mysqli_set_charset($db_link,"utf8");

// Memcached
$memcached = new Memcached(); 
$memcached->addServer("localhost", 11211); 

// Mail headers for sending mail();
$mail_headers = "MIME-Version: 1.0" . "\r\n";
$mail_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$mail_headers .= 'From: no-reply@burstcoin.dk' . "\r\n"; //Enter your reply e-mail address

// To flush memcache
if($_GET['action']=='flush_mem' && $_SERVER['REMOTE_ADDR']==$secureIP){
	$memcached->flush();
	header('Location: https://explorer.burstcoin.network'); // Path to redirect to after memcached flush
}

define('SITE_NAME','https://explorer.burstcoin.network'); //The name of your site
?>
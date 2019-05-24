<?php
/*
# Burst Apps Team contribution -https://twitter.com/BurstAppsTeam
# Devolped by Zoh - https://twitter.com/Zoh63392187
# Donations: BURST-NMEA-GRHZ-BRFE-5SG6P
*/

//if($_SERVER['REMOTE_ADDR'] != '8.8.4.4' && $_SERVER['REMOTE_ADDR']!='' )die('Not allowed');
if($_SERVER['REMOTE_ADDR']!='' )die('Not allowed');

// Runs every 20 minute
ini_set('max_execution_time', 1200);

$t1=time();
// Database
$db_server = '127.0.0.1:3306';
$db_user = 'burstwallet';
$db_pass = 'burstwallet';
$db_db = 'brs_master';

$db_link = @mysqli_connect($db_server, $db_user, $db_pass) or die('('.mysqli_connect_errno().')');
@mysqli_select_db($db_link, $db_db) or die('(2)');

$memcached = new Memcached(); 
$memcached->addServer("localhost", 11211);

$peers = get_peers();
foreach($peers as $ip){
	$pos = strpos($ip['address'], ':');
	if ($pos !== false) { 
		$ip['address'] = substr($ip['address'], 0, $pos);
	}
	if(pingDomain($ip['address'])){
		update_peer($ip['address']);
	}
}

query_execute('INSERT INTO peer_char (`address`) SELECT address FROM peer where address not in (select address from peer_char)');
query_execute('delete from peer_char where peer_time <= now() - interval 3 month');

//$peers = get_peers();
foreach($peers as $ip){
	if(!($original_array = $memcached->get('geoip_'.gethostbyname($ip['address'])))){
		$full_link = "http://www.geoplugin.net/php.gp?ip=".gethostbyname($ip['address']);
		$curl_handle=curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,$full_link);
		curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
		$result = curl_exec($curl_handle);
		curl_close($curl_handle);
		$original_array=unserialize($result);
		if(!$original_array['geoplugin_countryCode'])continue;
		$memcached->set('geoip_'.$ip['address'],$original_array);
		$original_array = $memcached->get('geoip_'.$ip['address']);
		sleep(2);
	}
}

$t2= (time() - $t1);
file_put_contents('logs/cron_peers',$t2." seconds\n",FILE_APPEND);

// ------------------------------------------- Functions --------------------------------------//
function call_api($url){
	$full_link = $url;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$full_link);
	$result=curl_exec($ch);
	curl_close($ch);
	$json_feed = json_decode($result, true);
	
	return $json_feed;
}
function query_execute_unsafe($sql){
	global $db_link;
	$t1=time();
	$qq = mysqli_query($db_link, $sql);
	$result = mysqli_fetch_assoc($qq);
	$t2= (time() - $t1);
	if($t2>2)file_put_contents('logs/sql_log',$t2." seconds: ".$sql."\n",FILE_APPEND);
	return $result;
}
function update_peer($ip){
	global $db_link;
	$sql = ("update peer_char set peer_time=now() where address='".mysqli_real_escape_string($db_link,$ip)."'");
	mysqli_query($db_link,$sql);	
	return true;
}
function get_peers(){
	$result = query_to_array('select * from peer_char');
	return $result;	
}
function pingDomain($domain){
    if(@fsockopen($domain, 8123, $errno, $errstr, 0.3)){
		fclose($file);
        return true;
	}    
	return false;
}

function query_execute($sql){
	global $db_link;
	$t1=time();
	$qq = mysqli_query($db_link, mysqli_real_escape_string($db_link,$sql));
	$result = mysqli_fetch_assoc($qq);
	$t2= (time() - $t1);
	if($t2>2)file_put_contents('logs/sql_log',$t2." seconds: ".$sql."\n",FILE_APPEND);
	return $result;
}

function query_to_array($sql){
	global $db_link;
	$query = mysqli_query($db_link, mysqli_real_escape_string($db_link,$sql));
	while ($array = mysqli_fetch_assoc($query)) {   
		$sql_array[] = $array;
	}
	return $sql_array;
}
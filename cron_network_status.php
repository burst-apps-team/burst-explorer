<?php
/*
# Burst Apps Team contribution -https://twitter.com/BurstAppsTeam
# Devolped by Zoh - https://twitter.com/Zoh63392187
# Donations: BURST-NMEA-GRHZ-BRFE-5SG6P
*/
include_once dirname(__FILE__).'/log.php';
$writeDebug = true;

$time = time();
if($time % (12*60*60) != 0){  // 每12小时执行一次
	exit();
}
log::cron_network_status('cron_network_status执行',$writeDebug);

//if($_SERVER['REMOTE_ADDR'] != '8.8.4.4' && $_SERVER['REMOTE_ADDR']!='' )die('Not allowed');
if((isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']!='') )die('Not allowed');

ini_set('max_execution_time', 3600);
gc_enable();
$memory_limit = ini_get('memory_limit');
ini_set('memory_limit', '8192M');

$t1=time();

// Database login details
$db_server = '127.0.0.1:3306'; // ip:port
$db_user = 'root'; // Database username
$db_pass = ''; // Database password
$db_db = 'burstwallet'; // Database name

$db_link = @mysqli_connect($db_server, $db_user, $db_pass) or die('('.mysqli_connect_errno().')');
@mysqli_select_db($db_link, $db_db) or die('(2)');

$memcached = new Memcached(); 
$isMemcached = $memcached->addServer("127.0.0.1", 11211);
if($isMemcached){
	log::cron_network_status('memcached 连接成功',$writeDebug);
}

$blocks = query_to_array('select total_fee, timestamp, height, base_target from block where 1');
$first_run=0;
$avg_time=0;
$fees=0;
$reward=0;
$count=0;
$base_target=0;
$total_amount=0;
$last_time=false;
$TB = (4398046511104/240);
$avg_TB=0;
$last_count_amount=0;

$burstmined = '';
$burst_avg_mined = '';
$difficulty = '';
$network_size = '';
$blockchainsize = '';
$tw = '';
$wd = '';
log::cron_network_status('查询数据完毕，开始循环blocks！',$writeDebug);
foreach($blocks as $block){	
	$time = ($block['timestamp']+ 1407722400);  // 2014-08-11 10:00:00
	$date = date("Y-m-d", $time);
	if($date == date("Y-m-d", $last_time) || $last_time==''){
		log::cron_network_status('!last_time:'.$last_time,$writeDebug);
		$reward = $reward + block_reward($block['height']);
		$amount = ($block['total_fee']/100000000);
		$fees = $fees+$amount;
		$avg_time = $avg_time+($time-$last_time);
		$base_target=($base_target + $block['base_target']);
		$avg_TB = $avg_TB+($TB/$block['base_target']);
		$count++;
	} elseif(isset($last_time)) {
		$last_time = strtotime("-1 month", $last_time);
		$java_date = date("Y,m,d", $last_time);
		log::cron_network_status('is last_time:'.$last_time,$writeDebug);
		if(!($count_trans = $memcached->get('count_trans_'.$block['height']))){
			log::cron_network_status('$count_trans:'.$count_trans,$writeDebug);
			$count_trans = query_execute('select count(db_id) as count from transaction where height <'.($block['height']-1));
			$memcached->set('count_trans_'.$block['height'], $count_trans,0);
			$count_trans = $memcached->get('count_trans_'.$block['height']);
		}
		
		if(!($count_acc = $memcached->get('count_acc_'.$block['height']))){
			log::cron_network_status('$count_acc:'.$count_acc,$writeDebug);
			$count_acc = query_execute('select count(db_id) as count from account where creation_height <'.($block['height']-1));
			$memcached->set('count_acc_'.$block['height'], $count_acc,0);
			$count_acc = $memcached->get('count_acc_'.$block['height']);
		}
		
		$total = (0.0005 * $count_trans['count'])+(0.000255 * $count_acc['count'])+(0.0005 * ($block['height']-1));
		log::cron_network_status('$total:'.$total,$writeDebug);
		if(!($account_amounts = $memcached->get('creation_height_'.$block['height']))){
			log::cron_network_status('$account_amounts:'.$account_amounts,$writeDebug);
			$account_amounts = query_execute_unsafe('select count(db_id) as count from account where latest="1" and public_key!="" and creation_height<='.$block['height']);
			$memcached->set('creation_height_'.$block['height'], $account_amounts,0);
			$account_amounts = $memcached->get('creation_height_'.$block['height']);
		}
		
		$total_amount = $total_amount + ($account_amounts['count']-$last_count_amount);
		log::cron_network_status('$total_amount:'.$total_amount,$writeDebug);
		if($first_run!=1){
			$burstmined .= ',[new Date('.$java_date.'), '.$reward.',undefined,'.$fees.']'."\n";
			$burst_avg_mined .= '[new Date('.$java_date.'), 240]'."\n";
			$difficulty .= '[new Date('.$java_date.'), '.(int)($base_target/$count).']'."\n";
			$network_size .= '[new Date('.$java_date.'), '.($avg_TB/$count).']'."\n";
			$blockchainsize .= '[new Date('.$java_date.'),'.(0.0005 * $count_trans['count']).','.(0.000255 * $count_acc['count']).','.(0.0005 * ($block['height']-1)).','.$total.']'."\n";
			$wd .= '[new Date('.$java_date.'), '.($account_amounts['count']-$last_count_amount).']'."\n";
			$tw .= '[new Date('.$java_date.'),'.$total_amount.']'."\n";
			$first_run=1;
		}else{
			$burstmined .= ',[new Date('.$java_date.'), '.$reward.',undefined,'.$fees.']'."\n";
			$burst_avg_mined .= ',[new Date('.$java_date.'), '.$avg_time/$count.']'."\n";
			$difficulty .= ',[new Date('.$java_date.'), '.(int)($base_target/$count).']'."\n";
			$network_size .= ',[new Date('.$java_date.'), '.($avg_TB/$count).']'."\n";
			$blockchainsize .= ',[new Date('.$java_date.'),'.(0.0005 * $count_trans['count']).','.(0.000255 * $count_acc['count']).','.(0.0005 * ($block['height']-1)).','.$total.']'."\n";
			$wd .= ',[new Date('.$java_date.'), '.($account_amounts['count']-$last_count_amount).']'."\n";
			$tw .= ',[new Date('.$java_date.'),'.$total_amount.']'."\n";
		}
		$last_count_amount = $account_amounts['count'];
		$fees=0;
		$reward=0;
		$count=0;
		$avg_time=0;
		$base_target=0;
		$avg_TB=0;
	}
	$last_time = $time;
}

file_put_contents('section/chart/feed/burstmined',$burstmined);
file_put_contents('section/chart/feed/burst_avg_mined',$burst_avg_mined);
file_put_contents('section/chart/feed/difficulty',$difficulty);
file_put_contents('section/chart/feed/network_size',$network_size);
file_put_contents('section/chart/feed/blockchainsize',$blockchainsize);
file_put_contents('section/chart/feed/tw',$tw);
file_put_contents('section/chart/feed/wd',$wd);
unset($blocks);
echo 'Block Charts done<br>';
log::cron_network_status('Block Charts done',$writeDebug);
$first_run=0;
$trans_count_total=0;
$transactions = query_to_array('select amount, timestamp, height from transaction where 1 order by timestamp asc');
log::cron_network_status('select transaction ',$writeDebug);
foreach($transactions as $tran){
	$time_date = ($tran['timestamp']+1407722400);
	$date = date("Y-m-d", $time_date);
	if($date == date("Y-m-d", $last_date) || $last_date==''){
		$tran_amount = $tran_amount+$tran['amount'];
		$trans_count++;
		$trans_count_total++;
	} else {
		$last_month = strtotime("-1 month", $last_date);
		$java_date = date("Y,m,d", $last_month);
		$avg_trans_ablock = ($trans_count_total/$tran['height']);
		if($first_run!=1){
			$tt .= '[new Date('.$java_date.'), '.(int)($trans_count_total).']'."\n";
			$tpd .= '[new Date('.$java_date.'), '.(int)($trans_count).']'."\n";
			$atpb .= '[new Date('.$java_date.'), '.(int)($avg_trans_ablock).']'."\n";
			$trans_spent_used .= '[new Date('.$java_date.'), '.(int)($tran_amount/100000000).']'."\n";
			$first_run=1;
		} else {
			$tt .= ',[new Date('.$java_date.'), '.(int)($trans_count_total).']'."\n";
			$tpd .= ',[new Date('.$java_date.'), '.(int)($trans_count).']'."\n";
			$atpb .= ',[new Date('.$java_date.'), '.(int)($avg_trans_ablock).']'."\n";
			$trans_spent_used .= ',[new Date('.$java_date.'), '.(int)($tran_amount/100000000).']'."\n";
		}
		$tran_amount = $tran['amount'];
		$trans_count=1;
		$trans_count_total++;
	}
	$last_date = $time_date;
}
file_put_contents('section/chart/feed/tt',$tt);
file_put_contents('section/chart/feed/tpd',$tpd);
file_put_contents('section/chart/feed/atpb',$atpb);
file_put_contents('section/chart/feed/trans_spent_used',$trans_spent_used);
echo 'Transactions chart<br>';
log::cron_network_status('Transactions chart',$writeDebug);
ini_set('memory_limit', $memory_limit);

// ------------------------------------------- Functions --------------------------------------//
$t2= (time() - $t1);
file_put_contents('logs/cron_network_status',$t2." seconds\n",FILE_APPEND);
function block_reward($block_height){
	$month = (int)($block_height/10800);
	$block_reward = (int) (pow(0.95, $month) * 10000);
	return (int)$block_reward;
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
function query_execute_unsafe($sql){
	global $db_link;
	$t1=time();
	$qq = mysqli_query($db_link, $sql);
	$result = mysqli_fetch_assoc($qq);
	$t2= (time() - $t1);
	if($t2>2)file_put_contents('logs/sql_log',$t2." seconds: ".$sql."\n",FILE_APPEND);
	return $result;
}
<?php
/*
# Burst Apps Team contribution -https://twitter.com/BurstAppsTeam
# Devolped by Zoh - https://twitter.com/Zoh63392187
# Donations: BURST-NMEA-GRHZ-BRFE-5SG6P
*/
// This schedule / cronjob should run every minute

include_once dirname(__FILE__).'/log.php';

/*$time = time();
if($time % 60 != 0){  // 每60秒执行一次
    log::debug('cron_执行停止');
    exit();
}*/

//if($_SERVER['REMOTE_ADDR'] != '8.8.4.4' && $_SERVER['REMOTE_ADDR']!='' )die('Not allowed');
if((isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']!='') )die('Not allowed');

// Set max execution time to insure that its no longer than 60 seconds before new cron job start.
//ini_set('max_execution_time', 55);
$t1=time();

// Database login details
$db_server = '127.0.0.1:3306'; // ip:port
$db_user = 'root'; // Database username
$db_pass = ''; // Database password
$db_db = 'burstwallet'; // Database name

$db_link = @mysqli_connect($db_server, $db_user, $db_pass) or die('('.mysqli_connect_errno().')');
@mysqli_select_db($db_link, $db_db) or die('(2)');
log::debug('mysqli连接成功！');
$memcached = new Memcached();
$memcached->addServer("127.0.0.1", 11211);
log::debug('memcached连接成功！');

$mail_headers = "MIME-Version: 1.0" . "\r\n";
$mail_headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$mail_headers .= 'From: xkjscmlxj@163.com' . "\r\n"; //Enter your reply e-mail address

// Collect new peers for the Chart
query_execute('INSERT INTO peer_char (`address`) SELECT address FROM peer where address not in (select address from peer_char)');
$peers = query_to_array('select * from peer_char where 1');
foreach($peers as $peer){
		$result = call_api('localhost:8125/burst?requestType=getPeer&peer='.$peer['address']);
		if(
            (isset($result['errorCode'])&&$result['errorCode']==5) ||
            (isset($result['version'])&& $result['version'] =='v0.0.0') ||
            (isset($result['version'])&& $result['version']=='')
        )continue;
		query_execute_unsafe('update peer_char set brs_version="'.ltrim($result['version'],'v').'" where address="'.$peer['address'].'"');
}
echo 'peerversionupdated<br>';

// Search for Multi-Out transactions and put it in the database   搜索多出事务并将其放入数据库
$last_block = query_execute('select height from block where 1 order by height desc limit 1');
$blocks = query_to_array('select db_id, id, attachment_bytes from transaction where type=0 and subtype=1 and height>='.($last_block['height']-50));
foreach($blocks as $block){
	if($block['attachment_bytes']){
		$transaction_id = $block['id'];
		$multiout_attachment = parseMultiOut($block['attachment_bytes']);
		foreach($multiout_attachment as $ma){
			query_execute_unsafe('INSERT INTO parseMultiOut(recipient_id, transaction_id, amount, db_id)VALUES ('.fromUnsignedLong($ma[0]).','.$transaction_id.','.$ma[1].','.$block['db_id'].')');
		}
	}
}
// Search for Multi-Out-Same transactions and put it in the database
$transaction = query_to_array('select * from transaction where type=0 and subtype=2 and height>='.($last_block['height']-50));
foreach($transaction as $trans){
	if($trans['attachment_bytes']){
		$transaction_id = $trans['id'];
		$multiout_attachment = parseMultiOutSame($trans['attachment_bytes']);
		foreach($multiout_attachment as $ma){
			$numbers_of_rewards=count($multiout_attachment);
			$reward = ($trans['amount']/$numbers_of_rewards);
			query_execute_unsafe("INSERT INTO parseMultiOutSame(recipient_id, transaction_id, amount,db_id)VALUES ('".fromUnsignedLong($ma)."','".$transaction_id."','".$reward."',".$trans['db_id'].")");
		}
	}
}
// Find the block generator and recipient of the forged block and put it in the database
$pool_blocks = query_execute('select height from block_forger where 1 order by height desc limit 1');
if($pool_blocks['height']>=1)$block_height = $pool_blocks['height'];
else $block_height = 1;

$state = false;
while($state==false){
	$pool_blocks = query_execute('select generator_id from block where height='.$block_height);
	if($pool_blocks['generator_id']!=''){
		$reward_assignment = query_to_array('select * from transaction where type=20 and height<= '.$block_height.' and sender_id='.$pool_blocks['generator_id'].' order by height asc');
		$reward_assignment_count = query_execute('select count(*) as count from transaction where type=20 and height<= '.$block_height.' and sender_id='.$pool_blocks['generator_id']);
		$last_ra =0;
		$current_count = 1;
		if(!$reward_assignment){
			add_block_forger($pool_blocks['generator_id'],$pool_blocks['generator_id'],$block_height);
		}
		foreach($reward_assignment as $ra){
			if($block_height>=($ra['height']+3) && $reward_assignment_count['count']!=$current_count){
				$last_ra = $ra['recipient_id'];
				$current_count++;
				continue;
			}
			if($block_height>=($ra['height']+3) && $reward_assignment_count['count']==$current_count){
				$last_ra = $ra['recipient_id'];
			}
			if($last_ra==$pool_blocks['generator_id']){
				add_block_forger($pool_blocks['generator_id'],$pool_blocks['generator_id'],$block_height);
			}else{
				add_block_forger($pool_blocks['generator_id'],$last_ra,$block_height);
			}
			$current_count++;
			break;
		}
		$current_count=1;
		$block_height++;
	} else {
		$state=true;
	}
}

// Monitor if new account (welcome mail)
$result = query_to_array_unsafe("select * from monitor where welcome='0'");
foreach($result as $accounts){
    if($accounts){
        query_to_array_unsafe("update monitor set welcome='1' where welcome='0'");
        mail($accounts['email'],'Burst Notification System','Welcome to Burst Notification System<br><br>If you did not subscribe for e-mail notifications please click:<a href="https://explorer.burstcoin.network/?action=monitor&hash='.$accounts['passphrase'].'">Unsubscribe e-mail notifications</a>',$mail_headers);
        query_execute('update monitor set send_mails=send_mails+1 where db_id='.$accounts['db_id']);
    }
}

// Monitor if we have an account who forged a block og have balance change
$last_monitored_height = query_execute('select last_height from monitor_block')['last_height'];
$block_to_scan = query_to_array('select * from block_forger where height >'.($last_monitored_height) .' order by height asc');
$max_height = query_execute('select max(height) as height from block_forger')['height'];
query_execute('update monitor_block set last_height='.$max_height);
foreach($block_to_scan as $block){
	$accounts = query_to_array('select * from monitor where account_id='.$block['generator_id']);
	foreach($accounts as $act){
		mail($act['email'],'Burst Notification System',"<b>Block Forged!</b><br><b>Account:</b> <a href='https://explorer.burstcoin.network/?action=account&account=".toUnsignedLong($act['account_id'])."&submenu=blocks'>".RS_encode(toUnsignedLong($act['account_id']))."</a><br><br><i>This e-mail is provided to you by <a href='https://burstcoin.network'>explorer.burstcoin.network</a><br><a href='https://explorer.burstcoin.network/?action=monitor&hash=".$act['passphrase']."'>Unsubscribe</a> e-mail notifications",$mail_headers);
		query_execute('update monitor set send_mails=send_mails+1 where db_id='.$act['db_id']);
	}
}

// Monitor a given account
$result = query_to_array_unsafe("select * from monitor");
foreach($result as $accounts){
	$db_balance = get_account_balance($accounts['account_id']);
	$monitor_balance = $accounts['balance'];
	if($db_balance<$monitor_balance){
		//withdraw
		$amount = $monitor_balance-$db_balance;
		query_execute_unsafe('update monitor set balance='.$db_balance.' where db_id='.$accounts['db_id']);	
		mail($accounts['email'],'Burst Notification System','<b>New balance:</b> '.get_burst_amount($db_balance,2).' Burst (<font color="red">-'.get_burst_amount($amount,2).'</font>)<br><b>Account:</b> <a href="https://explorer.burstcoin.network/?action=account&account='.toUnsignedLong($accounts['account_id']).'&submenu=at">'.RS_encode(toUnsignedLong($accounts['account_id'])).'</a><br><br><i>This e-mail is provided to you by <a href="https://explorer.burstcoin.network">explorer.burstcoin.network</a><br><a href="https://explorer.burstcoin.network/?action=monitor&hash='.$accounts['passphrase'].'">Unsubscribe</a> e-mail notifications',$mail_headers);
		query_execute('update monitor set send_mails=send_mails+1 where db_id='.$accounts['db_id']);
	}
	if($db_balance>$monitor_balance){
		//deposite
		$amount = $db_balance-$monitor_balance;
		query_execute_unsafe('update monitor set balance='.$db_balance.' where db_id='.$accounts['db_id']);
		mail($accounts['email'],'Burst Notification System','<b>New balance:</b> '.get_burst_amount($db_balance,2).' Burst (<font color="green">+'.get_burst_amount($amount,2).'</font>)<br><b>Account:</b> <a href="https://explorer.burstcoin.network/?action=account&account='.toUnsignedLong($accounts['account_id']).'&submenu=at">'.RS_encode(toUnsignedLong($accounts['account_id'])).'</a><br><br><i>This e-mail is provided to you by <a href="https://explorer.burstcoin.network">explorer.burstcoin.network</a><br><a href="https://explorer.burstcoin.network/?action=monitor&hash='.$accounts['passphrase'].'">Unsubscribe</a> e-mail notifications',$mail_headers);
		query_execute('update monitor set send_mails=send_mails+1 where db_id='.$accounts['db_id']);
	}
}

//Update BRS version on peers active on the Chart
$peers = query_to_array('select * from peer_char where 1');
foreach($peers as $peer){
		$result = call_api('localhost:8125/burst?requestType=getPeer&peer='.$peer['address']);
		if(
            (isset($result['errorCode']) && $result['errorCode']==5) ||
            (isset($result['version'])&& $result['version']=='v0.0.0') ||
            (isset($result['version']) && $result['version']=='')
        )continue;
		query_execute_unsafe('update peer_char set brs_version="'.ltrim($result['version'],'v').'" where address="'.$peer['address'].'"');
}
echo 'Done';
log::debug('Done！');
$t2= (time() - $t1);
if($t2>30)file_put_contents('logs/cron_',$t2." seconds: \n",FILE_APPEND);
//-------------------------------------------------- functions -------------------------------------------------//
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
function get_burst_amount($burst,$decimals,$bypass = 0) {
	$amount = ($burst/100000000);
	if($amount <= 0) return 0;
	if($bypass==1)return ($amount);
	return number_format($amount,$decimals);
}
function get_account_balance($account_id){
	$db_balance = query_execute_unsafe("select balance from account where latest=1 and id=".$account_id);
	return $db_balance['balance'];
}
function get_monitor_account_balance($account_id){
	$monitor_balance = query_execute_unsafe("select balance from monitor where account_id=".$account_id);
	return $monitor_balance['balance'];
}
function query_execute_unsafe($sql){
	global $db_link;
	$t1=time();
	$qq = mysqli_query($db_link, $sql);
    if(is_object($qq)){
        $result = mysqli_fetch_assoc($qq);
        $t2= (time() - $t1);
        if($t2>2)file_put_contents('logs/sql_log',$t2." seconds: ".$sql."\n",FILE_APPEND);
        return $result;
    }
}
function query_to_array_unsafe($sql){
	global $db_link;
	$query = mysqli_query($db_link, $sql);
    $sql_array = [];
	while ($array = mysqli_fetch_assoc($query)) {   
		$sql_array[] = $array;
	}
	return $sql_array;
}
function add_block_forger($generator_id,$recipient_id,$height){
	query_execute('INSERT INTO block_forger(generator_id, recipient_id, height)VALUES ('.$generator_id.','.$recipient_id.','.$height.')');
}

/**
 * @param $attachment string The attachment data as a string
 * @return array 2 dimensional array - First array contains each transfer, second array contains [account_id, amount_transferred]
 * @ array[0] = recipient
 * @ array[1] = burst amount
 */
function parseMultiOut($attachment) {
    $header = unpack("C*", substr($attachment, 0, 2));
    $version = $header[1];
    $numberOfRecipients = $header[2];
    $data = unpack("P*", substr($attachment, 2, strlen($attachment)-2));
    $result = array();
    for ($i = 1; $i <= $numberOfRecipients; $i++) {
        array_push($result, array(toUnsignedLong($data[2*$i-1]), $data[2*$i]));
    }
    return $result;
}

/**
 * @param $attachment string The attachment data as a string
 * @return array Array of accounts that the amount was transferred to
 */
function parseMultiOutSame($attachment) {
    $header = unpack("C*", substr($attachment, 0, 2));
    $version = $header[1];
    $numberOfRecipients = $header[2];
    $data = unpack("P*", substr($attachment, 2, strlen($attachment)-2));
    for ($i = 1; $i <= sizeof($data); $i++) {
        $data[$i] = toUnsignedLong($data[$i]);
    }
    return $data;
}

/**
 * @param $input string the unsigned long ID
 * @return string the signed long ID
 * @convert back to database format
 */
function fromUnsignedLong($input) {
	$input = (string)$input;
    if (bccomp(bcsub($input, bcpow("2", "63")), "0") >= 0) {
        return bcsub($input, bcpow(2,64));
    } else return $input;
}

/**
 * @param $input string the signed long ID
 * @return string The unsigned long ID
 * input from database
 */
function toUnsignedLong($input) {
    $input = (string)$input;
	if(bccomp($input, "0") <= 0){
        return bcadd(bcpow("2", "64"), $input);
    } else return $input;
}

function query_execute($sql){
	global $db_link;
	$t1=time();
	$qq = mysqli_query($db_link, mysqli_real_escape_string($db_link,$sql));
    if(is_object($qq)){
        $result = mysqli_fetch_assoc($qq);
        $t2= (time() - $t1);
        if($t2>2)file_put_contents('logs/sql_log',$t2." seconds: ".$sql."\n",FILE_APPEND);
        return $result;
    }
}

function query_to_array($sql){
	global $db_link;
    $sql_array=[];
	$query = mysqli_query($db_link, mysqli_real_escape_string($db_link,$sql));
    if(is_object($query)){
        while ($array = mysqli_fetch_assoc($query)) {
            $sql_array[] = $array;
        }
    }

	return $sql_array;
}

/**
 * Ported from BRS by harry1453
 * Licensed under GPLv3 - ported from https://github.com/burst-apps-team/burstcoin/blob/master/src/brs/crypto/ReedSolomon.java
 */

/**
 * @param $input string The input string
 * @param $pos int The position of the character you want to get
 * @return string The char at that position
 */
function RS_charAt($input, $pos) {
    return substr($input, $pos, 1);
}

/**
 * @param $length int The size of the array
 * @return array An empty integer array
 */
function RS_emptyArray($length) {
    return array_fill(0, $length, 0);
}

/**
 * @param $plain string The numeric ID to encode
 * @return string The RS encoding of the ID in the form BURST-XXXX-XXXX-XXXX-XXXXX
 */
function RS_encode($plain) {
    $initial_codeword = array(1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $codeword_map = array(3, 2, 1, 0, 7, 6, 5, 4, 13, 14, 15, 16, 12, 8, 9, 10, 11);
    $alphabet = "23456789ABCDEFGHJKLMNPQRSTUVWXYZ";

    $base_32_length = 13;
    $base_10_length = 20;

    $length = strlen($plain);
    $plain_string_10 = RS_emptyArray($base_10_length);
    for ($i = 0; $i < $length; $i++) {
        $plain_string_10[$i] = ((int)RS_charAt($plain, $i)) - (int)'0';
    }

    $codeword_length = 0;
    $codeword = RS_emptyArray(sizeof($initial_codeword));

    do {  // base 10 to base 32 conversion
        $new_length = 0;
        $digit_32 = 0;
        for ($i = 0; $i < $length; $i++) {
            $digit_32 = $digit_32 * 10 + $plain_string_10[$i];
            if ($digit_32 >= 32) {
                $plain_string_10[$new_length] = $digit_32 >> 5;
                $digit_32 &= 31;
                $new_length += 1;
            } else if ($new_length > 0) {
                $plain_string_10[$new_length] = 0;
                $new_length += 1;
            }
        }
        $length = $new_length;
        $codeword[$codeword_length] = $digit_32;
        $codeword_length += 1;
    } while($length > 0);

    $p = array(0, 0, 0, 0);
    for ($i = $base_32_length - 1; $i >= 0; $i--) {
        $fb = $codeword[$i] ^ $p[3];
        $p[3] = $p[2] ^ RS_gmult(30, $fb);
        $p[2] = $p[1] ^ RS_gmult(6, $fb);
        $p[1] = $p[0] ^ RS_gmult(9, $fb);
        $p[0] =         RS_gmult(17, $fb);
    }

    for ($i = 0; $i < sizeof($initial_codeword) - $base_32_length; $i++) {
        $codeword[$i+$base_32_length] = $p[$i];
    }

    $cypher_string_builder = "";
    for ($i = 0; $i < 17; $i++) {
        $codework_index = $codeword_map[$i];
        $alphabet_index = $codeword[$codework_index];
        $cypher_string_builder .= RS_charAt($alphabet, $alphabet_index);

        if (($i & 3) == 3 && $i < 13) {
            $cypher_string_builder .= '-';
        }
    }
    return "BURST-" . $cypher_string_builder;
}

/**
 * @param $cypher_string string the RS encoded address in the form BURST-XXXX-XXXX-XXXX-XXXXX
 * @return string The numeric ID of the account
 * @throws Exception If the encoded address fails to decode / is not valid.
 */
function RS_decode($cypher_string) {
    if (substr($cypher_string, 0, 6) === "BURST-") {
        $cypher_string = substr($cypher_string, 6, strlen($cypher_string) - 6);
    }

    $initial_codeword = array(1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
    $codeword_map = array(3, 2, 1, 0, 7, 6, 5, 4, 13, 14, 15, 16, 12, 8, 9, 10, 11);
    $alphabet = "23456789ABCDEFGHJKLMNPQRSTUVWXYZ";

    $base_32_length = 13;

    $codeword = RS_emptyArray(sizeof($initial_codeword));

    for ($i = 0; $i < sizeof($initial_codeword); $i++) {
        $codeword[$i] = $initial_codeword[$i];
    }

    $codeword_length = 0;
    for ($i = 0; $i < strlen($cypher_string); $i++) {
        $position_in_alphabet = strpos($alphabet, RS_charAt($cypher_string, $i));

        if ($position_in_alphabet <= -1 || $position_in_alphabet > strlen($alphabet)) {
            continue;
        }

        if ($codeword_length > 16) {
            throw new Exception("Codeword too long");
        }

        $codework_index = $codeword_map[$codeword_length];
        $codeword[$codework_index] = $position_in_alphabet;
        $codeword_length += 1;
    }

    if ($codeword_length != 17 || !RS_is_codeword_valid($codeword)) {
        throw new Exception("Codeword invalid");
    }

    $length = $base_32_length;
    $cypher_string_32 = RS_emptyArray($length);
    for ($i = 0; $i < $length; $i++) {
        $cypher_string_32[$i] = $codeword[$length - $i - 1];
    }

    $plain_string_builder = "";
    do { // base 32 to base 10 conversion
        $new_length = 0;
        $digit_10 = 0;

        for ($i = 0; $i < $length; $i++) {
            $digit_10 = $digit_10 * 32 + $cypher_string_32[$i];

            if ($digit_10 >= 10) {
                $cypher_string_32[$new_length] = intdiv($digit_10, 10);
                $digit_10 %= 10;
                $new_length += 1;
            } else if ($new_length > 0) {
                $cypher_string_32[$new_length] = 0;
                $new_length += 1;
            }
        }
        $length = $new_length;
        $plain_string_builder .= ($digit_10 + (int)'0');
    } while ($length > 0);

    return strrev($plain_string_builder);
}

function RS_gmult($a, $b) {
    $gexp = array(1, 2, 4, 8, 16, 5, 10, 20, 13, 26, 17, 7, 14, 28, 29, 31, 27, 19, 3, 6, 12, 24, 21, 15, 30, 25, 23, 11, 22, 9, 18, 1);
    $glog = array(0, 0, 1, 18, 2, 5, 19, 11, 3, 29, 6, 27, 20, 8, 12, 23, 4, 10, 30, 17, 7, 22, 28, 26, 21, 25, 9, 16, 13, 14, 24, 15);

    if ($a == 0 || $b == 0) {
        return 0;
    }

    $idx = ($glog[$a] + $glog[$b]) % 31;

    return $gexp[$idx];
}

function RS_is_codeword_valid($codeword) {
    $gexp = array(1, 2, 4, 8, 16, 5, 10, 20, 13, 26, 17, 7, 14, 28, 29, 31, 27, 19, 3, 6, 12, 24, 21, 15, 30, 25, 23, 11, 22, 9, 18, 1);
    $sum = 0;

    for ($i = 1; $i < 5; $i++) {
        $t = 0;

        for ($j = 0; $j < 31; $j++) {
            if ($j > 12 && $j < 27) {
                continue;
            }

            $pos = $j;
            if ($j > 26) {
                $pos -= 14;
            }

            $t ^= RS_gmult($codeword[$pos], $gexp[($i * $j) % 31]);
        }

        $sum |= $t;
    }

    return $sum == 0;
}
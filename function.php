<?php
/*
# Burst Apps Team contribution -https://twitter.com/BurstAppsTeam
# Devolped by Zoh - https://twitter.com/Zoh63392187
# Donations: BURST-NMEA-GRHZ-BRFE-5SG6P
*/
function format_big_numbers($number, $delimiter = ",") {
	$len = strlen($number);
	if ($len > 3){
		if ($len % 3 == 0) {
			$split = str_split($number, 3);
			$number_with_commas = implode("$delimiter", $split);
			return $number_with_commas;
		}
		else if ($len % 3 == 1) {
			$front = substr($number, 0, 1);
			$split = substr($number, 1, $len - 1);
			$split = str_split($split, 3);
			$number_with_commas = implode("$delimiter", $split);
			$number_with_commas = $front . "$delimiter" . $number_with_commas;
			return $number_with_commas;
		}
		else {
			$front = substr($number, 0, 2);
			$split = substr($number, 2, $len - 2);
			$split = str_split($split, 3);
			$number_with_commas = implode("$delimiter", $split);
			$number_with_commas = $front . "$delimiter" . $number_with_commas;
			return $number_with_commas;
		}
	}
	else {
		return $number;
	}
}
//----------------------------------- Assets -----------------------------------------------
function asset_quantity($quantity,$decimals){
	$dec=$decimals;
	while($decimals>0){
		$quantity = bcdiv($quantity,10);
		$decimals--;
	}
	return format_big_numbers($quantity);
}

function get_assets($asset_id = 0,$page = 0){
	if($page<=1)$assets = query_to_array("select * from asset where 1 order by db_id desc limit 50");
	if($page>=2) $assets = query_to_array("select * from asset where 1 order by db_id desc limit ".(($page-1)*50) .",50");
	if($asset_id >= 1)$assets = query_execute("select * from asset where db_id=".$asset_id);
	return $assets;
}

function get_asset_count(){
	$count = query_execute('select count(*) as count from asset where 1');
	return $count['count'];
}

function get_asset_transfer($asset_id){
	global $memcached;
	if(!($asset = $memcached->get('get_asset_transfer_'.$asset_id))){
		$asset = query_to_array("select * from asset_transfer where asset_id=".$asset_id.' order by db_id desc');
		$memcached->set('get_asset_transfer_'.$asset_id, $asset,240);
		$asset = $memcached->get('get_asset_transfer_'.$asset_id);
	}	
	return $asset;
}

function get_account_asset_transfer($account_id,$page=0){
	if($page<=1)$asset = query_to_array("select * from asset_transfer where recipient_id=".$account_id." order by db_id desc limit 50");
	if($page>=2)$asset = query_to_array("select * from asset_transfer where recipient_id=".$account_id." order by db_id desc limit ".(($page-1)*50) .",50");
	return $asset;
}

function get_asset_transfer_count($asset_id){
	global $memcached;
	if(!($asset = $memcached->get('get_asset_transfer_count_'.$asset_id))){
		$asset = query_to_array("select count(*) as count from asset_transfer where asset_id=".$asset_id);
		$memcached->set('get_asset_transfer_count_'.$asset_id, $asset,3600);
		$asset = $memcached->get('get_asset_transfer_count_'.$asset_id);
	}
	return $asset;
}

function get_account_asset_transfer_count($account_id){
	global $memcached;
	if(!($asset = $memcached->get('get_account_asset_transfer_count_'.$account_id))){
		$asset = query_execute("select count(*) as count from asset_transfer where sender_id=".$account_id.' OR recipient_id='.$account_id);
		$memcached->set('get_account_asset_transfer_count_'.$account_id, $asset,240);
		$asset = $memcached->get('get_account_asset_transfer_count_'.$account_id);
	}
	return $asset['count'];
}

function get_asset_trades($asset_id){
	global $memcached;
	if(!($asset = $memcached->get('get_asset_trades_'.$asset_id))){
		$asset = query_to_array("select * from trade where asset_id=".$asset_id.' order by db_id desc');
		$memcached->set('get_asset_trades_'.$asset_id, $asset,240);
		$asset = $memcached->get('get_asset_trades_'.$asset_id);
	}
	return $asset;
}

function count_asset_holders($asset_id){
	global $memcached;
	if(!($asset = $memcached->get('count_asset_holders_'.$asset_id))){
		$asset = query_execute("select count(db_id) as count from account_asset where asset_id=".$asset_id.' and latest=1 order by db_id desc');
		$memcached->set('count_asset_holders_'.$asset_id, $asset,3600);
		$asset = $memcached->get('count_asset_holders_'.$asset_id);
	}
	return $asset['count'];
}

function get_asset_holders($asset_id){
	$asset = query_to_array("select * from account_asset where asset_id=".$asset_id.' and latest=1 order by db_id desc');
	return $asset;
}

function get_account_asset($account_id){
	$asset = query_to_array("select * from account_asset where account_id=".$account_id.' and latest=1 order by db_id desc');
	return $asset;
}

function count_holders_asset($account_id){
	$asset = query_execute("select count(*) as count from account_asset where account_id=".$account_id.' and latest=1 order by db_id desc');
	return $asset['count'];
}

function asset_pct_owned($Owned_asset_quantity,$Owned_asset_quantity_dec,$produced_asset_quantity,$produced_asset_quantity_dec){
	while($Owned_asset_quantity_dec>0){
		$Owned_asset_quantity = bcdiv($Owned_asset_quantity,10);
		$Owned_asset_quantity_dec--;
	}

	while($produced_asset_quantity_dec>0){
		$produced_asset_quantity = bcdiv($produced_asset_quantity,10);
		$produced_asset_quantity_dec--;
	}
	$result = bcdiv(bcmul($Owned_asset_quantity,100),$produced_asset_quantity);
	if($result<=0.009)$result=0;
	
	return $result;
}

function get_asset_info($asset_id){
	global $memcached;
	if(!($assets_name = $memcached->get('get_asset_info_'.$asset_id))){
		$assets_name = query_execute("select * from asset where id=".$asset_id." order by db_id desc");
		$memcached->set('get_asset_info_'.$asset_id, $assets_name,240);
		$assets_name = $memcached->get('get_asset_info_'.$asset_id);
	}
	return $assets_name;
}

// --------------------------- BLOCKS ------------------------------------
function get_forged_blocks_account($account_id,$type = 0,$page=0){
	if($type==1){
		if($page<=1)$forged_blocks = query_to_array("select * from block_forger where generator_id =".$account_id." order by db_id desc limit 50");
		if($page>=2)$forged_blocks = query_to_array("select * from block_forger where generator_id =".$account_id." order by db_id desc limit ".(($page-1)*50) .",50");
		return $forged_blocks;
	}
	if($type==3){
		if($page<=1)$rewarded_blocks = query_to_array("select * from block_forger where recipient_id =".$account_id." order by db_id desc limit 50");
		if($page>=2)$rewarded_blocks = query_to_array("select * from block_forger where recipient_id =".$account_id." order by db_id desc limit ".(($page-1)*50) .",50");
		return $rewarded_blocks;
	}
}

function get_forged_blocks($account_id,$type = 0, $account_id2 = 0){
	if($type==1){
		$forged_blocks = query_to_array("select * from block_forger where generator_id =".$account_id." order by db_id desc");
		return $forged_blocks;
	}
	if($type==2){
		$forged_blocks_count = query_execute("select count(*) as count from block_forger where generator_id =".$account_id);
		return $forged_blocks_count['count'];
	}
	if($type==3){
		$rewarded_blocks = query_to_array("select * from block_forger where recipient_id =".$account_id." order by db_id desc");
		return $rewarded_blocks;
	}
	if($type==4){
		$rewarded_blocks_count = query_execute("select count(*) as count from block_forger where generator_id=".$account_id." and recipient_id =".$account_id);
		return $rewarded_blocks_count['count'];
	}
	if($type==5){
		$rewarded_blocks = query_to_array("select * from block_forger where generator_id= ".$account_id." and recipient_id =".$account_id);
		foreach($rewarded_blocks as $rb){
			$reward = $reward + block_reward($rb['height']);
			$fees = $fees + get_block_fee($rb['height']);
		}
		if($fees>=1){
			$total = $reward + ($fees/100000000);
		} else $total = $reward;
		return array($reward,get_burst_amount($fees,2,1),$total);
	}
	if($type==6){
		$rewarded_blocks = query_to_array("select * from block_forger where recipient_id !=".$account_id." and generator_id=".$account_id);
		foreach($rewarded_blocks as $rb){
			$reward = $reward + block_reward($rb['height']);
			$fees = $fees + get_block_fee($rb['height']);
		}
		if($fees>=1){
			$total = $reward + ($fees/100000000);
		} else $total = $reward;
		return array($reward,get_burst_amount($fees,2,1),$total);
	}
	if($type==7){
		$rewarded_blocks = query_to_array("select * from block_forger where recipient_id =".$account_id." and generator_id!=".$account_id);
		foreach($rewarded_blocks as $rb){
			$reward = $reward + block_reward($rb['height']);
			$fees = $fees + get_block_fee($rb['height']);
		}
		if($fees>=1){
			$total = $reward + ($fees/100000000);
		} else $total = $reward;
		return array($reward,get_burst_amount($fees,2,1),$total);
	}
	if($type==8){
		$rewarded_blocks_count = query_execute("select count(*) as count from block_forger where generator_id!=".$account_id." and recipient_id =".$account_id);
		return $rewarded_blocks_count['count'];
	}
	if($type==9){
		$forged_blocks_count = query_execute("select count(*) as count from block_forger where generator_id =".$account_id);
		return $forged_blocks_count['count'];
	}
	if($type==10){
		$forged_blocks_count = query_execute("select count(*) as count from block_forger where generator_id =".$account_id.' and recipient_id='.$account_id2);
		return $forged_blocks_count['count'];
	}
			
}

function get_block_fee($height){
	global $memcached;
	if(!($total_fee = $memcached->get('get_block_fee_'.$height))){
		$total_fee = query_to_array("select total_fee from block where height=".$height);
		$memcached->set('get_block_fee_'.$height, $total_fee);
		$total_fee = $memcached->get('get_block_fee_'.$height);
	}
	return $total_fee[0]['total_fee'];
}

function get_blocks($page = 0){	
	if($page<=1)$blocks = query_to_array("select * from block where 1 order by db_id desc limit 50");
	if($page>=2) $blocks = query_to_array("select * from block where 1 order by db_id desc limit ".(($page-1)*50) .",50");
	return $blocks;
}

function get_blocks_count(){
	$count = query_execute('select count(*) as count from block where 1');
	return $count['count'];
}

function block_reward($block_height){
	$month = (int)($block_height/10800);
	$block_reward = (int) (pow(0.95, $month) * 10000);
	return (int)$block_reward;
}

function get_block_size($Size, $Levels = 2) {
	$Size = (double) $Size;
	$Steps = 0;
	while($Size>=1024) {
		$Steps++;
		$Size=$Size/1024;
	}
	if ($Steps==0) { return $Size.' B'; }
	elseif ($Steps==1) { return number_format($Size,$Levels).' KB'; }
	elseif ($Steps==2) { return number_format($Size,$Levels).' MB'; }
}

function number_of_transactions($block_height){
	global $memcached;
	if(!($number_of_transactions = $memcached->get('nr_transaction_'.$block_height))){
		$number_of_transactions = query_execute('select count(*) as count from transaction where height='.$block_height);
		$memcached->set('nr_transaction_'.$block_height, $number_of_transactions);
		$number_of_transactions = $memcached->get('nr_transaction_'.$block_height);
	}	
	return $number_of_transactions['count'];
}

function show_block_transactions($block_height){
	global $memcached;
	if(!($show_block_transactions = $memcached->get('show_block_transactions_'.$block_height))){
		$show_block_transactions = query_to_array("select * from transaction where height=".$block_height." order by timestamp desc");
		$memcached->set('show_block_transactions_'.$block_height, $show_block_transactions);
		$show_block_transactions = $memcached->get('show_block_transactions_'.$block_height);
	}
	return $show_block_transactions;
}

function get_block_info($block_height){
	global $memcached;
	if(!($block = $memcached->get('block_info_'.$block_height))){
		$block = query_to_array("select * from block where height=".$block_height);
		$memcached->set('block_info_'.$block_height, $block);
		$block = $memcached->get('block_info_'.$block_height);
	}	
	return $block[0];
}

function get_sold_goods($goods_id){
	global $memcached;
	if(!($sold_goods = $memcached->get('get_sold_goods_'.$goods_id))){
		$sold_goods = query_to_array("select * from purchase where goods_id=".$goods_id." order by db_id desc");
		$memcached->set('get_sold_goods_'.$goods_id, $sold_goods,240);
		$sold_goods = $memcached->get('get_sold_goods_'.$goods_id);
	}	
	return $sold_goods;
}

// ------------------------------- Account ------------------------------------
function get_multiout($recipient_id){
	global $memcached;
	if(!($result_count = $memcached->get('get_multiout_'.$recipient_id))){
		$result_count = query_execute('select count(*) as ct, sum(amount) as burst from parseMultiOut where recipient_id='.$recipient_id);
		$memcached->set('get_multiout_'.$recipient_id, $result_count,240);
		$result_count = $memcached->get('get_multiout_'.$recipient_id);
	}
	return $result_count;
}
function get_multiout_same($recipient_id){
	global $memcached;
	if(!($result_count = $memcached->get('get_multiout_same'.$recipient_id))){
		$result_count = query_execute('select count(*) as ct, sum(amount) as amount from parseMultiOutSame where recipient_id='.$recipient_id);
		$memcached->set('get_multiout_same'.$recipient_id, $result_count,240);
		$result_count = $memcached->get('get_multiout_same'.$recipient_id);
	}
	return $result_count;
}
function get_multiout_same_transaction($recipient_id,$page=0){
	if($page<=1)$parseMultiOutSame = query_to_array('select * from parseMultiOutSame where recipient_id='.$recipient_id.' order by db_id desc limit 50');
	if($page>=2)$parseMultiOutSame = query_to_array('select * from parseMultiOutSame where recipient_id='.$recipient_id.' order by db_id desc limit '.(($page-1)*50) .',50');
	return $parseMultiOutSame;
}
function get_multiout_transaction($recipient_id,$page=0){
	if($page<=1)$parseMultiOut = query_to_array('select * from parseMultiOut where recipient_id='.$recipient_id.' order by db_id desc limit 50');
	if($page>=2)$parseMultiOut = query_to_array('select * from parseMultiOut where recipient_id='.$recipient_id.' order by db_id desc limit '.(($page-1)*50) .',50');
	return $parseMultiOut;
}

function show_account($account_id){
	global $memcached;
	if(!($result = $memcached->get('show_account_'.$account_id))){
		$result = query_execute('select * from account where latest=1 AND id='.$account_id);	
		$memcached->set('show_account_'.$account_id, $result,240);
		$result = $memcached->get('show_account_'.$account_id);
	}
	return $result;
}

function account_transactions_sent($account_id){
	$account_transactions = query_to_array('select sum(amount) as amount, count(*) as count from transaction where sender_id='.$account_id.' group by sender_id;');
	return $account_transactions;
}

function account_transactions_recived($account_id){
	$account_transactions = query_execute('select sum(amount) as amount, count(*) as count from transaction where recipient_id='.$account_id.' group by recipient_id;');
	return $account_transactions;
}

function account_transactions_fee($account_id){
	$account_transactions = query_execute('select sum(fee) as fee from transaction where sender_id='.$account_id.' group by sender_id;');
	return $account_transactions['fee'];
}

function account_transactions_list($account_id,$page=0){
	if($page<=1)$account_transactions = query_to_array("select * from transaction where sender_id=".$account_id." OR recipient_id=".$account_id." order by db_id desc limit 50");
	if($page>=2)$account_transactions = query_to_array("select * from transaction where sender_id=".$account_id." OR recipient_id=".$account_id." order by db_id desc limit ".(($page-1)*50) .",50");
	return $account_transactions;
}

function count_account_transactions($account_id){
	$count_account_transactions = query_execute('select count(*) as count from transaction where sender_id='.$account_id.' OR recipient_id='.$account_id.' order by db_id desc');
	return $count_account_transactions;
}

function get_transactions($transaction_id){
	$get_transactions = query_execute('select * from transaction where id='.$transaction_id);
	return $get_transactions;
}

// --------------------- generalt functions ------------------------------
function monitor_burst($burst_adr,$b_id=0){
	?>
<!-- Button trigger modal -->
<a href="#" data-toggle="modal" data-target="#exampleModal<?=$b_id?>"><i class="fas fa-envelope"></i></a>
<!-- Modal -->
<div class="modal fade" id="exampleModal<?=$b_id?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Monitor Burst Account</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form action="?action=monitor_submit" method="post">
					<i>Monitor balance changes, mined blocks and more</i><br>
					<b>Burst account
					<?$url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];?>
					<input type="hidden" name="burst_adr" value="<?=$burst_adr?>">
					<input type="hidden" name="return_url" value="<?echo "https://".$url?>">
					<input type="text" name="burst_adr_show" class="form-control" disabled value="<?=$burst_adr?>" placeholder="BURST-____-____-____-____"><br>
					Enter your e-mail</b>
					<input type="text" name="burst_mail" class="form-control" placeholder="E-mail">
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
					<a href="https://explorer.burstcoin.dk/?action=network_notification_system">Burst Notification System</a>
				</form>
			</div>
		</div>
	</div>
</div>
	<?
}
function get_peers(){
	$result = query_to_array('select * from peer_char where peer_time >= now() - interval 24 hour');
	return $result;	
}
function get_peers_count_all(){
	$result = query_execute('select count(*) as count from peer_char');
	return $result['count'];
}
function get_peers_count_hour($hour){
	$result = query_execute('select count(*) as count from peer_char where peer_time >= now() - interval '.$hour.' hour');
	return $result['count'];
}
function get_first_peer_active(){
	$result = query_execute('select min(peer_time) as peer_time from peer_char');
	return $result['peer_time'];	
}
function search($str){
	global $db_link;
	if(is_numeric($str)){
		$result = query_execute('select id from block where height='.$str);
		if($result){
			return array('type' => 'block','height' => $str);
		}
		$result = query_execute('select height from transaction where id='.fromUnsignedLong($str));
		if($result){
			return array('type' => 'transaction', 'height' => $result['height']);
		}
	}
	if(RS_decode($str)!=''){
		$adr = RS_decode($str);
		if(query_execute('select id from account where latest=1 and id='.fromUnsignedLong($adr)))return array('type' => 'account', 'ID' => $adr);
	}

	$result = query_to_array_unsafe('select id from account where latest=1 and name like "%'.mysqli_real_escape_string($db_link,$str).'%" limit 10');
	if($result){
		return array('type' => 'name', 'ID' => $result, 'txt' => $str);
	}
	return 'nothing: '.$str;
}

function at_info($at_id){
	$result = query_execute('select * from at_state where at_id='.$at_id.' and latest=1');
	return $result;
}
function automated_transactions($id=0,$page){
	if($page<=1)$at = query_to_array('select * from at where latest=1 order by db_id desc limit 50');
	if($page>=2)$at = query_to_array("select * from at where latest=1 order by db_id desc limit ".(($page-1)*50) .",50");
	return $at;
}

function automated_transactions_count(){
	$count = query_execute('select count(*) as count from at where 1');
	return $count['count'];
}

function get_market_info($id=0,$page){
	if($page<=1)$goods = query_to_array('select * from goods where delisted=0 and latest=1 order by db_id desc limit 50');
	if($page>=2)$goods = query_to_array("select * from goods where delisted=0 and latest=1 order by db_id desc limit ".(($page-1)*50) .",50");
	return $goods;
}

function get_market_count(){
	$count = query_execute('select count(*) as count from goods where 1');
	return $count['count'];
}

function get_pool_members($account_id,$page=0){
	if($page<=1)$pool_members = query_to_array("select * from reward_recip_assign where recip_id = ".$account_id." and account_id!=".$account_id." and latest=1 limit 50");
	if($page>=2)$pool_members = query_to_array("select * from reward_recip_assign where recip_id = ".$account_id." and account_id!=".$account_id." and latest=1 limit ".(($page-1)*50) .",50");
	return $pool_members;
}

function count_pool_members($account_id){
	$count_pool_members = query_execute('select count(*) as count from reward_recip_assign where recip_id = '.$account_id.' and account_id!='.$account_id.' and latest=1');
	return $count_pool_members;
}

function get_reward_recip_assign($account_id){
	$get_reward_recip_assign = query_execute('select * from reward_recip_assign where account_id = '.$account_id.' and latest=1');
	return $get_reward_recip_assign;
}

function convert_time($block_time){
	return date('Y-m-d H:i:s', ($block_time + 1407722400));
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'min',
        's' => 'sec',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function transactions_type($type,$subtype){
	$str = 'Transaction not defined:<br>Type:'.$type.'<br>Subtype'.$subtype;
	if($type==0 && $subtype==0)$str='Ordinary Payment';
	if($type==0 && $subtype==1)$str='Multi-Out Payment';
	if($type==0 && $subtype==2)$str='Multi-Out Payment';
	if($type==1 && $subtype==0)$str='Arbitrary Message';
	if($type==1 && $subtype==1)$str='Alias Assignment';
	if($type==1 && $subtype==5)$str='Account Update';
	if($type==1 && $subtype==6)$str='Alias sell';
	if($type==1 && $subtype==7)$str='Alias buy';
	if($type==2 && $subtype==0)$str='Asset issuance';
	if($type==2 && $subtype==1)$str='Asset Transfer';
	if($type==2 && $subtype==2)$str='Ask Order Placement';
	if($type==2 && $subtype==3)$str='Bid Order Placement';
	if($type==2 && $subtype==4)$str='Ask order cancellation';
	if($type==2 && $subtype==5)$str='Bid order cancellation';
	if($type==3 && $subtype==0)$str='Marketplace Listing';
	if($type==3 && $subtype==1)$str='Marketplace Removal';
	if($type==3 && $subtype==2)$str='Marketplace Price Change';
	if($type==3 && $subtype==3)$str='Quantity change';
	if($type==3 && $subtype==4)$str='Marketplace Purchase';
	if($type==3 && $subtype==5)$str='Marketplace Delivery';
	if($type==3 && $subtype==6)$str='Marketplace Feedback';
	if($type==3 && $subtype==7)$str='Marketplace Refund';
	if($type==4 && $subtype==0)$str='Effective balance leasing';
	if($type==20 && $subtype==0)$str='Reward Assignment';
	if($type==21 && $subtype==3)$str='Subscription Subscribe';
	if($type==21 && $subtype==5)$str='Subscription Payment';
	if($type==22 && $subtype==0)$str='AT Creation';
	return $str;
}

function debug($val,$var=false){
	if($val===false)return false;
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	if(file_get_contents('logs/sql_log'))echo"<pre>Warning: SLOW SQL's</pre>";
	if($var[0])$var=$var[0];
	return print_r('<pre>').print_r($var).print_r('</pre>');
}

function str2bin($str) { 
  return strtoupper(bin2hex($str));
}

function burst_value_api($burst){
	global $memcached;
	if(!($result = $memcached->get('burst_value'))){
		$full_link = "https://api.coinmarketcap.com/v1/ticker/burst/?convert=EUR";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL,$full_link);
		$result=curl_exec($ch);
		curl_close($ch);
		$coinprice = json_decode($result, true);
		
		$EURvalue = $coinprice[0]['price_eur'];
		$USDvalue = $coinprice[0]['price_usd'];
		$BTCvalue = $coinprice[0]['price_btc'];
		
		$burst_value = array('price_eur' => $EURvalue,'price_usd' => $USDvalue,'price_btc' => $BTCvalue);
				
		$memcached->set('burst_value', $burst_value,60);
		
		$result = $memcached->get('burst_value');
	}		
	return array('price_eur' => number_format($burst*$result['price_eur'],2),'price_usd' => number_format($burst*$result['price_usd'],2),'price_btc' => number_format($burst*$result['price_btc'],4));
}

function get_burst_amount($burst,$decimals,$bypass = 0) {
	$amount = ($burst/100000000);
	if($amount <= 0) return 0;
	if($bypass==1)return ($amount);
		
	return number_format($amount,$decimals);
}

function burst_value($burst){
	if($burst==0)return 0;
	$burst_print = number_format($burst,2);
	$burst_print = rtrim($burst_print, '0');
	$burst_print = rtrim($burst_print, '.');
	return '<span title="'.burst_value_api($burst)['price_eur'].' € / '.burst_value_api($burst)['price_usd'].' $ / '.burst_value_api($burst)['price_btc'].' BTC">'.$burst_print.'</span>';
}

function show_account_id_name_and_or_rs($account_id,$both_id_and_name = 0,$height = 0,$solo_miner = 0){
	global $memcached;
	if(!($block_forger = $memcached->get('block_forger_'.$height))){
		$block_forger = query_execute('select generator_id, recipient_id from block_forger where height='.$height);
		$memcached->set('block_forger_'.$height, $block_forger);
		$block_forger = $memcached->get('block_forger_'.$height);
	}
	if(display_str(show_account($account_id)['name'])!='')$account_name = display_str(show_account($account_id)['name']);
	elseif($block_forger['recipient_id']!='')$account_name = RS_encode(toUnsignedLong($block_forger['generator_id']));
	else $account_name = '';
	
	$scammers = array('3011184961392690771','1606939141091290673','3463450404564580757');
	if(in_array($account_id,$scammers))$account_name = RS_encode(toUnsignedLong($block_forger['generator_id']));

	$reward_name = display_str(show_account($block_forger['recipient_id'])['name']) ? display_str(show_account($block_forger['recipient_id'])['name']) : RS_encode(toUnsignedLong($block_forger['recipient_id']));
	
	$name_or_RS = show_account($account_id)['name'] ? show_account($account_id)['name'] : RS_encode(toUnsignedLong($account_id));
	if(in_array($account_id,$scammers))$name_or_RS = RS_encode(toUnsignedLong($account_id));

	if($solo_miner==1 && $block_forger['recipient_id']==$block_forger['generator_id'])return '<span class="negativ_nr">(Solo miner)</span>';
	if($both_id_and_name==1)return blocks_short_name($account_name,26);
	if($both_id_and_name==2)return blocks_short_name($reward_name,26);
	if($both_id_and_name==3)return toUnsignedLong($block_forger['recipient_id']);
	if($both_id_and_name==4)return toUnsignedLong($block_forger['generator_id']);
	if($both_id_and_name==5)return 'reward_name_'.$block_forger['recipient_id'];
	if($both_id_and_name==6)return RS_encode(toUnsignedLong($account_id));
	if($both_id_and_name==7)return toUnsignedLong($account_id);
	if($both_id_and_name==8)return fromUnsignedLong($account_id);
	if($both_id_and_name==9)return blocks_short_name(display_str(show_account($account_id)['name']),26);
	if($both_id_and_name==10)return display_str($name_or_RS);
	
}

function blocks_short_name($str,$lenth){
	$new_str = strlen($str) > $lenth ? substr($str,0,$lenth)."..." : $str;
	return $new_str;
}

function show_pages($page,$action,$count=0){
	if($count>=1){
		$max_page = ceil($count/50);
	}
	if($max_page==1 || $count==0)return false;
	
	if(!$page)$page=1;
	$pre_page = $page-3;
	$last_page = $page+4;
		
	$str = '<ul class="pagination pagination-sm">';
	while($pre_page <= $last_page){		
		if($pre_page<=0){
			$pre_page++;
			continue;
		}
		if($pre_page == $page){
			$str .='
			<li class="page-item">
				<li class="page-item active"><a class="page-link" href="#">'.$pre_page.'</a></li>
			</li>
		'	;
		} else {
			if(!$first_page_set && $page>=2){
				$str .='<li><li class="page-item"><a class="page-link" href="/?action='.$action.'&page=1">|&laquo;</a></li>';
				$str .='
				<li>
					<li class="page-item"><a class="page-link" href="/?action='.$action.'&page='.($page - 1).'">&laquo;</a></li>
				</li>
				';
				$first_page_set=true;
			}
			if(!$last_page_set && $pre_page==$last_page && ($pre_page-3) <= $max_page) {
				$str .='
				<li>
					<li class="page-item"><a class="page-link" href="/?action='.$action.'&page='.($page + 1).'">&raquo;</a></li>
				</li>
				';
				$str .='<li><li class="page-item"><a class="page-link" href="/?action='.$action.'&page='.($max_page).'">&raquo;|</a></li></li>';
				$last_page_set=true;
			} else {
				if($pre_page <= $max_page){
					$str .='
					<li>
						<li class="page-item"><a class="page-link" href="/?action='.$action.'&page='.$pre_page.'">'.$pre_page.'</a></li>
					</li>
					';
				}
			}
		}
		$pre_page++;
	}	
	$str .= '</ul>';
	return $str;
}

function display_str($Str) {
	if (empty($Str)) {
		return '';
	}
	if ($Str!='' && !is_number($Str)) {
		$Str=make_utf8($Str);
		$Str=preg_replace("/&(?![A-Za-z]{0,4}\w{2,3};|#[0-9]{2,5};)/m","",$Str);

		$Replace = array(
			"'",'"',"<",">",",",
			'&#128;','&#130;','&#131;','&#132;','&#133;','&#134;','&#135;','&#136;','&#137;','&#138;','&#139;','&#140;','&#142;','&#145;','&#146;','&#147;','&#148;','&#149;','&#150;','&#151;','&#152;','&#153;','&#154;','&#155;','&#156;','&#158;','&#159;'
		);

		$With=array(
			'&#39;','&quot;','&lt;','&gt;',' ',
			'&#8364;','&#8218;','&#402;','&#8222;','&#8230;','&#8224;','&#8225;','&#710;','&#8240;','&#352;','&#8249;','&#338;','&#381;','&#8216;','&#8217;','&#8220;','&#8221;','&#8226;','&#8211;','&#8212;','&#732;','&#8482;','&#353;','&#8250;','&#339;','&#382;','&#376;'
		);	
		$Str=str_replace($Replace,$With,$Str);
	}
	return $Str;
}

function is_number($Str) {
	if ($Str < 0) { $Return = false; }
	$Return = ($Str == strval(intval($Str)) ? true : false);
	return $Return;
}

function make_utf8($Str) {
	if ($Str!="") {
		if (is_utf8($Str)) { $Encoding="UTF-8"; }
		if (empty($Encoding)) { $Encoding=mb_detect_encoding($Str,'UTF-8, ISO-8859-1'); }
		if (empty($Encoding)) { $Encoding="ISO-8859-1"; }
		if ($Encoding=="UTF-8") { return $Str; }
		else { return @mb_convert_encoding($Str,"UTF-8",$Encoding); }
	}
}

function is_utf8($Str) {
	return preg_match('%^(?:
		[\x09\x0A\x0D\x20-\x7E]			 // ASCII
		| [\xC2-\xDF][\x80-\xBF]			// non-overlong 2-byte
		| \xE0[\xA0-\xBF][\x80-\xBF]		// excluding overlongs
		| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} // straight 3-byte
		| \xED[\x80-\x9F][\x80-\xBF]		// excluding surrogates
		| \xF0[\x90-\xBF][\x80-\xBF]{2}	 // planes 1-3
		| [\xF1-\xF3][\x80-\xBF]{3}		 // planes 4-15
		| \xF4[\x80-\x8F][\x80-\xBF]{2}	 // plane 16
		)*$%xs', $Str
	);
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

function query_execute($sql){
	global $db_link;
	$t1=time();
	$qq = mysqli_query($db_link, mysqli_real_escape_string($db_link,$sql));
	$result = mysqli_fetch_assoc($qq);
	$t2= (time() - $t1);
	if($t2>2)file_put_contents('logs/sql_log',$t2." seconds: ".$sql."\n",FILE_APPEND);
	return $result;
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

function query_to_array($sql){
	global $db_link;
	$query = mysqli_query($db_link, mysqli_real_escape_string($db_link,$sql));
	while ($array = mysqli_fetch_assoc($query)) {   
		$sql_array[] = $array;
	}
	return $sql_array;
}

function query_to_array_unsafe($sql){
	global $db_link;
	$query = mysqli_query($db_link, $sql);
	while ($array = mysqli_fetch_assoc($query)) {   
		$sql_array[] = $array;
	}
	return $sql_array;
}

function call_api($url){
	$full_link = $url;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$full_link);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0.5);
	curl_setopt($ch, CURLOPT_TIMEOUT, 2);
	$result=curl_exec($ch);
	curl_close($ch);
	$json_feed = json_decode($result, true);
	
	return $json_feed;
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
            return false;
			//throw new Exception("Codeword too long");
        }

        $codework_index = $codeword_map[$codeword_length];
        $codeword[$codework_index] = $position_in_alphabet;
        $codeword_length += 1;
    }

    if ($codeword_length != 17 || !RS_is_codeword_valid($codeword)) {
        return false;
		//throw new Exception("Codeword invalid");
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
?>

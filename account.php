<?php 
$account = show_account(fromUnsignedLong($_GET['account']));
$multiout_array = get_multiout(fromUnsignedLong($_GET['account']));
$multiout_same = get_multiout_same(fromUnsignedLong($_GET['account']));
$sent = account_transactions_sent($account['id']);
$recived = account_transactions_recived($account['id']);
$count_holders_asset = count_holders_asset($account['id']);
$count_account_asset_transfers = get_account_asset_transfer_count($account['id']);
$transaction_count = count_account_transactions($account['id'])['count'];
$pool_member_count = count_pool_members($account['id'])['count'];
$account_forged_block_count = ((get_forged_blocks($account['id'],2))+get_forged_blocks($account['id'],8));
?>
<ul class="nav nav-tabs small" id="myTab" role="tablist">
	<li class="nav-item">
		<a class="nav-link <?if($_GET['submenu']=='')echo 'active';?>" href="?action=account&account=<?=$_GET['account']?>" role="tab">Account</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?if($_GET['submenu']=='blocks')echo 'active';?>" href="?action=account&account=<?=$_GET['account']?>&submenu=blocks" role="tab"><?print_r((get_forged_blocks($account['id'],2))+get_forged_blocks($account['id'],8));?> Blocks</a>
	</li>
	<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle <?if($_GET['submenu']=='at' || $_GET['submenu']=='at_multi' || $_GET['submenu']=='at_multi_same')echo 'active';?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<?echo $transaction_count+$multiout_array['ct']+$multiout_same['ct'];?> Transaction
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<a class="dropdown-item <?if($_GET['submenu']=='at')echo 'active';?>" href="?action=account&account=<?=$_GET['account']?>&submenu=at" role="tab" ><?echo $transaction_count?> Transaction</a>
			<a class="dropdown-item <?if($_GET['submenu']=='at_multi')echo 'active';?>" href="?action=account&account=<?=$_GET['account']?>&submenu=at_multi" role="tab" ><?echo $multiout_array['ct']?>  MultiOut</a>
			<a class="dropdown-item <?if($_GET['submenu']=='at_multi_same')echo 'active';?>" href="?action=account&account=<?=$_GET['account']?>&submenu=at_multi_same" role="tab" ><?echo $multiout_same['ct']?>  MultiOutSame</a>
		</div>
	</li>
	<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle <?if($_GET['submenu']=='aat' || $_GET['submenu']=='aah')echo 'active';?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<?=$count_holders_asset?> Asset Holdings
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<a class="dropdown-item <?if($_GET['submenu']=='aah')echo 'active';?>" href="?action=account&account=<?=$_GET['account']?>&submenu=aah" role="tab" ><?=$count_holders_asset?> Asset Holdings</a>
			<a class="dropdown-item <?if($_GET['submenu']=='aat')echo 'active';?>" href="?action=account&account=<?=$_GET['account']?>&submenu=aat" role="tab" ><?=$count_account_asset_transfers?> Asset Transaction</a>
		</div>
	</li>
  <li class="nav-item">
    <a class="nav-link <?if($_GET['submenu']=='apm')echo 'active';?>" href="?action=account&account=<?=$_GET['account']?>&submenu=apm" role="tab"><?=$pool_member_count?> Pool members</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
<br>Account ID <b>#<?=$_GET['account']?></b><br><br>
	<?if($_GET['submenu']==''){?>
	<div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
		<table class="table table-striped">
			<tr>
				<td>
					Account
				<td>
				<td style="white-space:nowrap;">
					<?
					echo show_account_id_name_and_or_rs($account['id'],6);
					echo monitor_burst(show_account_id_name_and_or_rs($account['id'],6));
					?>
				<td>
			</tr>
			<tr>
				<td>
					Public Key
				<td>
				<td>
					<div class="word_break small"><?=str2bin($account['public_key'])?></div>
				<td>
			</tr>
			<tr>
				<td>
					Name
				<td>
				<td>
				<? $account_name = ($account['name'] != '') ? display_str($account['name']) : 'N/A'; echo $account_name;?>
				<td>
			</tr>
			<tr>
				<td>
					Balance
				<td>
				<td>
					<span class="positiv_nr">+<?=burst_value(get_burst_amount($account['balance'],2,1))?></span> Burst
				<td>
			</tr>
			<tr>
				<td>
					Received
				<td>
				<td>
					<span class="positiv_nr">+<?=burst_value(get_burst_amount(($recived['amount'])+$multiout_array['burst']+$multiout_same['amount'],2,1))?></span> Burst in <?echo ($recived['count'])+$multiout_array['ct']+$multiout_same['ct'];?> transactions
				<td>
			</tr>
			<tr>
				<td>
					Sent
				<td>
				<td>
					<span class="negativ_nr">-<?=burst_value(get_burst_amount($sent[0]['amount'],2,1))?></span> Burst in <?=($sent[0]['count']);?> transactions
				<td>
			</tr>
			<tr>
				<td>
					Transaction fees paid
				<td>
				<td>
					<span class="negativ_nr">-<?=burst_value(get_burst_amount(account_transactions_fee($account['id']),2,1))?></span> Burst
				<td>
			</tr>
			<tr>
				<td>
					Solo mined balance<br>
				<td>
				<td>
					<span class="positiv_nr">+<?=burst_value(get_forged_blocks($account['id'],5)[0])?></span> Burst in <?print_r(get_forged_blocks($account['id'],4));?> blocks<br>
					<span class="positiv_nr">+<?=burst_value(get_forged_blocks($account['id'],5)[1])?></span> in fees<br>					
					<span class="positiv_nr">+<?=burst_value(get_forged_blocks($account['id'],5)[2])?></span> Burst in total
				<td>
			</tr>
			<tr>
				<td>
					Pool mined balance<br>
				<td>
				<td>
					<span class="positiv_nr">+<?=burst_value(get_forged_blocks($account['id'],6)[0])?></span> Burst in <?print_r((get_forged_blocks($account['id'],2))-(get_forged_blocks($account['id'],4)));?> blocks<br>
					<span class="positiv_nr">+<?=burst_value(get_forged_blocks($account['id'],6)[1])?></span> in fees<br>					
					<span class="positiv_nr">+<?=burst_value(get_forged_blocks($account['id'],6)[2])?></span> Burst in total
				<td>
			</tr>
			<tr>
				<td>
					Pool reward balance<br>
				<td>
				<td>
					<span class="positiv_nr">+<?=burst_value(get_forged_blocks($account['id'],7)[0])?></span> Burst in <?print_r(get_forged_blocks($account['id'],8));?> blocks<br>
					<span class="positiv_nr">+<?=burst_value(get_forged_blocks($account['id'],7)[1])?></span> in fees<br>					
					<span class="positiv_nr">+<?=burst_value(get_forged_blocks($account['id'],7)[2])?></span> Burst in total
				<td>
			</tr>
			<tr>
				<td>
					Reward assignment
				<td>
				<td>
					<?
					if(get_reward_recip_assign($account['id'])['recip_id']==$account['id'])echo'Solo mining';
					elseif(get_reward_recip_assign($account['id'])['recip_id']!='') {
						echo '<a href="?action=account&account='.show_account_id_name_and_or_rs(get_reward_recip_assign($account['id'])['recip_id'],7).'">';
						echo show_account_id_name_and_or_rs(get_reward_recip_assign($account['id'])['recip_id'],6).'</a>';
						echo '<br>'.display_str(show_account_id_name_and_or_rs(get_reward_recip_assign($account['id'])['recip_id'],9));
						echo monitor_burst(show_account_id_name_and_or_rs(get_reward_recip_assign($account['id'])['recip_id'],6),1);
					}else echo 'N/A';
					?>
				<td>
			</tr>
		</table>
	</div>
	<?}?>
	<div>
		<?if($_GET['submenu']=='blocks')include 'section/account/account_blocks.php';?>
	</div>
	<div>
		<?if($_GET['submenu']=='at')include 'section/account/account_transaction.php';?>
	</div>
	<div>
		<?if($_GET['submenu']=='at_multi')include 'section/account/account_transaction_multiout.php';?>
	</div>
	<div>
		<?if($_GET['submenu']=='at_multi_same')include 'section/account/account_transaction_multiout_same.php';?>
	</div>
	<div>
		<?if($_GET['submenu']=='aat')include 'section/account/account_assets_transaction.php';?>
	</div>
	<div>
		<?if($_GET['submenu']=='aah')include 'section/account/account_asset_holdings.php';?>
	</div>
	<div>
		<?if($_GET['submenu']=='apm')include 'section/account/account_pool_members.php';?>
	</div>
</div>

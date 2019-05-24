<?php
// Get account informations from database
$account = show_account(fromUnsignedLong($_GET['account']));
?>
<table class="main_content table_top_margin">
	<tr class="tr_header_bot_border">
		<td colspan="2">
			Account #<?=$_GET['account']?>
		<td>
	</tr>
	<tr class="even">
		<td>
			Account
		<td>
		<td>
			<?=blocks_short_name(show_account_id_name_and_or_rs($account['id'],2),26)?>
		<td>
	</tr>
	<tr>
		<td>
			Public Key
		<td>
		<td class="td_small_txt">
			<?=str2bin($account['public_key'])?>
		<td>
	</tr>
	<tr class="even">
		<td>
			Name
		<td>
		<td>
			<?=$account['name'];?>
		<td>
	</tr>
	<tr>
		<td>
			Balance
		<td>
		<td>
			<?=get_burst_amount($account['balance'],2)?> Burst
		<td>
	</tr>
	<tr class="even">
		<td>
			Received
		<td>
		<td>
			<? $sent = account_transactions_recived($account['id'])?>
			Multiout? <span class="positiv_nr"><?=get_burst_amount($sent[0]['amount'],2);?></span> Burst in <?=($sent[0]['count']);?> transactions
		<td>
	</tr>
	<tr>
		<td>
			Sent
		<td>
		<td>
			<? $sent = account_transactions_sent($account['id'])?>
			<span class="negativ_nr"><?=get_burst_amount($sent[0]['amount'],2);?></span> Burst in <?=($sent[0]['count']);?> transactions
		<td>
	</tr>
	<tr class="even">
		<td>
			Transaction fees paid
		<td>
		<td>
			<span class="negativ_nr"><?=get_burst_amount(account_transactions_fee($account['id']),2)?></span> Burst
		<td>
	</tr>
	<tr>
		<td>
			Pool mined balance
		<td>
		<td>
			<span class="positiv_nr"><?=account_mined_solo_pool($account['id'],$account['forged_balance'],1)?></span> Burst in ? blocks
		<td>
	</tr>
	<tr class="even">
		<td>
			Solo mined balance
		<td>
		<td>
			<span class="positiv_nr"><?=account_mined_solo_pool($account['id'],$account['forged_balance'],2)?></span> Burst in ? blocks
		<td>
	</tr>
</table>

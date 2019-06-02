<?php
// Getting block info
$show_block_transactions = show_block_transactions((int)$_GET['height']);
$block['height'] = (int)$_GET['height'];
$block = get_block_info($block['height']);
?>
<table class="table table-striped table-sm">										
	<tr>
		<td>
			Height
		</td>
		<td>
			<?=$block['height']?>
		</td>
	</tr>
	<tr>
		<td>
			Transactions
		</td>
		<td>
			<?=number_of_transactions($block['height'])?>
		</td>
	</tr>
	<tr>
		<td>
			Burst sent
		</td>
		<td>
			<?=burst_value(get_burst_amount($block['total_amount'],2,1))?> Burst
		</td>
	</tr>
	<tr>
		<td>
			Timestamp
		</td>
		<td>
			<?=convert_time($block['timestamp'])?>
		</td>
	</tr>
	<tr>
		<td>
			Generator
		</td>
		<td>
			<a href="?action=account&account=<?=show_account_id_name_and_or_rs($block['generator_id'],4,$block['height'])?>"><?=show_account_id_name_and_or_rs($block['generator_id'],6,$block['height'])?></a><br>
			<?=blocks_short_name(show_account_id_name_and_or_rs($block['generator_id'],9),26)?>
		</td>
	</tr>
	<tr>
		<td>
			Pool
		</td>
		<td>
			<a href="?action=account&account=<?=show_account_id_name_and_or_rs($block['generator_id'],3,$block['height'])?>"><?=blocks_short_name(show_account_id_name_and_or_rs($block['generator_id'],2,$block['height']),26)?></a>
		</td>
	</tr>
	<tr>
		<td class="text-nowrap">
			Block Generation Time
		</td>
		<td>
			<?=convert_time($block['timestamp'])?>
		</td>
	</tr>
	<tr>
		<td>
			Base Target
		</td>
		<td>
			<?=$block['base_target']?>
		</td>
	</tr>
	<tr>
		<td>
			Size
		</td>
		<td>
			<?=get_block_size($block['payload_length'])?>
		</td>
	</tr>
	<tr>
		<td>
			Version
		</td>
		<td>
			<?=$block['version']?>
		</td>
	</tr>
	<tr>
		<td>
			Nonce
		</td>
		<td>
			<?=toUnsignedLong($block['nonce'])?>
		</td>
	</tr>
	<tr>
		<td>
			Block Reward
		</td>
		<td>
			<?=burst_value(block_reward($block['height']));?> Burst
		</td>
	</tr>
	<tr>
		<td>
			Block Reward Fees
		</td>
		<td>
			<?=burst_value(get_burst_amount($block['total_fee'],2,1))?> Burst
		</td>
	</tr>
	<tr>
		<td>
			Block Signature
		</td>
		<td>
			<div class="word_break small"><?=str2bin($block['block_signature'])?></div>
		</td>
	</tr>
	<tr>
		<td>
			Previous Block
		</td>
		<td>
			<a href="?action=block_inspect&height=<?=$block['height']-1?>"><?=$block['height']-1?></a>
		</td>
	</tr>
	<tr>
		<td>
			Next Block
		</td>
		<td>
		<?if($block['next_block_id']){?>
			<a href="?action=block_inspect&height=<?=$block['height']+1?>"><?=$block['height']+1?></a>
		<?}?>
		</td>
	</tr>
</table>
<table class="table table-striped table-sm small">
	<thead class="thead-light">
		<tr>
			<th>
				ID
			</th>
			<th>
				Type
			</th>
			<th>
				Sender
			</th>
			<th>
				Recipient
			</th>
			<th>
				Burst Amount
			</th>
			<th>
				Burst Fee
			</th>
			<th>
				Date
			</th>
		</tr>
	</thead>
	

<?
foreach ($show_block_transactions as $transaction){
?>
	<tr>
		<td>
			<a href="?action=transaction&id=<?=toUnsignedLong($transaction['id'])?>"><?=toUnsignedLong($transaction['id'])?></a>
		</td>
		<td>
			<?=transactions_type($transaction['type'],$transaction['subtype'])?>
		</td>
		<td>
			<a href="?action=account&account=<?=show_account_id_name_and_or_rs($transaction['sender_id'],7)?>"><?=show_account_id_name_and_or_rs($transaction['sender_id'],6,$block['height'])?></a><br>
			<?=blocks_short_name(show_account_id_name_and_or_rs($transaction['sender_id'],1),26)?>
		</td>
		<td>
			<?
			if($transaction['type'] == 0 && $transaction['subtype']==1){				
				$multiouts = parseMultiOut($transaction['attachment_bytes']);
			?>
				<!-- Button trigger modal -->
				<a href="#" data-toggle="modal" data-target="#exampleModal">
					View Multi-Out's
				</a>
				<!-- Modal -->
				<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog" role="document">
					<div class="modal-content">
					  <div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Multi-Out Payments</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
					  </div>
					  <div class="modal-body">
						<table class="table table-striped table-sm">
						<?foreach($multiouts as $recipient){?>
						<tr>
							<td>
								<a href="?action=account&account=<?=$recipient[0]?>"><?=show_account_id_name_and_or_rs($recipient[0],6,$block['height'])?></a><br>
							</td>
							<td>
								-> <span class="positiv_nr"><?echo '+'.get_burst_amount($recipient[1],2).'</span> Burst<br>';?>
							</td>
						</tr>
						<?}?>
						</table>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					  </div>
					</div>
				  </div>
				</div>
			<?} elseif(!$transaction['recipient_id']) {?>
				<?echo'/';?>
			<?} else {?>
				<a href="?action=account&account=<?=show_account_id_name_and_or_rs($transaction['recipient_id'],7)?>"><?=show_account_id_name_and_or_rs($transaction['recipient_id'],6,$block['height'])?></a>
			<?}?>
		</td>
		<td>
			<?=burst_value(get_burst_amount($transaction['amount'],2,1))?>
		</td>
		<td>
			<?=burst_value(get_burst_amount($transaction['fee'],2,1))?>
		</td>
		<td>
			<?=convert_time($transaction['timestamp'])?>
		</td>
	</tr>
<?}?>
</table>

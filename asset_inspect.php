<?php 
$event = $_GET['details'];
$asset_id = fromUnsignedLong($_GET['id']);
if($event=='transaction'){?>
	<table class="table table-striped small">
		<thead class="thead-light">
			<tr>
				<th>
					Since Block
				</th>
				<th>
					Sender
				</th>
				<th>
					Recipient
				</th>
				<th>
					Quantity
				</th>
				<th>
					Date
				</th>
			</tr>
		</thead>
	<?
	$asset_holders = get_asset_transfer($asset_id);
	$asset_info = get_asset_info($asset_id);
	$block = get_block_info($asset_info['height']);
	foreach ($asset_holders as $ah){
	?>
		<tr>
			<td>
				#<a href="#" data-toggle="modal" data-target="#myModal<?=$block['timestamp']?>"><?=$block['height']?></a>
				<div>
					<!-- Modal -->
					<div class="modal fade topmargin" id="myModal<?=$block['timestamp']?>" role="dialog">
						<div class="modal-dialog modal-md">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header"><span class="block_id_heeder">Block #</span>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body" class="">
									<table class="table table-striped">										
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
												<?=get_burst_amount($block['total_amount'],2)?>
											</td>
										</tr>
										<tr>
											<td>
												Transaction Fees
											</td>
											<td>
												<?=get_burst_amount($block['total_fee'],2)?>
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
												<a href="?action=account&account=<?=show_account_id_name_and_or_rs($block['generator_id'],4,$block['height'])?>"><?=show_account_id_name_and_or_rs($block['generator_id'],6,$block['height'])?></a>
												<?=blocks_short_name(show_account_id_name_and_or_rs($block['generator_id'],1,$block['height']),26)?>
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
												<?=block_reward($block['height']);?> Burst
											</td>
										</tr>
										<tr>
											<td>
												Block Reward Fees
											</td>
											<td>
												<?=get_burst_amount($block['total_fee'],2);?> Burst
											</td>
										</tr>
										<tr>
											<td>
												Block Signature
											</td>
											<td>
												<span class="word_break small"><?=str2bin($block['block_signature'])?></span>
											</td>
										</tr>
									</table>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</td>
			<td>
				<a href="?action=account&account=<?=toUnsignedLong($ah['sender_id'])?>"><?=blocks_short_name(show_account_id_name_and_or_rs($ah['sender_id'],6),26)?></a><br>
				<?=show_account_id_name_and_or_rs($ah['sender_id'],1)?>
			</td>
			<td>
				<a href="?action=account&account=<?=toUnsignedLong($ah['recipient_id'])?>"><?=show_account_id_name_and_or_rs($ah['recipient_id'],1)?></a>
			</td>
			<td>
				<?=asset_quantity($ah['quantity'],$asset_info['decimals'])?>
			</td>
			<td>
				<?=convert_time($ah['timestamp'])?>
			</td>
		</tr>
	<?
	}
	?>
	</table>
<?}
if($event=='holders'){
	$asset_holders = get_asset_holders($asset_id);
	?>
	<table class="table table-striped small">
		<thead class="thead-light">
			<tr>
				<th>
					Since Block
				</th>
				<th>
					Asset Name
				</th>
				<th>
					Asset owner
				</th>
				<th>
					Quantity
				</th>
				<th>
					Unconfirmed Quantity
				</th>
				<th>
					Share owned
				</th>
			</tr>
		</thead>
	<?

	foreach ($asset_holders as $ah){

	$asset_info = get_asset_info($ah['asset_id']);
	$block = get_block_info($ah['height']);
	?>
		<tr>
			<td>
				#<a href="#" data-toggle="modal" data-target="#myModal<?=$block['timestamp']?>"><?=$block['height']?></a>
				<div>
					<!-- Modal -->
					<div class="modal fade topmargin" id="myModal<?=$block['timestamp']?>" role="dialog">
						<div class="modal-dialog modal-md">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header"><span class="block_id_heeder">Block #</span>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body" class="">
									<table class="table table-striped">										
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
												<?=get_burst_amount($block['total_amount'],2)?>
											</td>
										</tr>
										<tr>
											<td>
												Transaction Fees
											</td>
											<td>
												<?=get_burst_amount($block['total_fee'],2)?>
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
												<a href="?action=account&account=<?=show_account_id_name_and_or_rs($block['generator_id'],4,$block['height'])?>"><?=show_account_id_name_and_or_rs($block['generator_id'],6,$block['height'])?></a>
												<?=blocks_short_name(show_account_id_name_and_or_rs($block['generator_id'],1,$block['height']),26)?>
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
												<?=block_reward($block['height']);?> Burst
											</td>
										</tr>
										<tr>
											<td>
												Block Reward Fees
											</td>
											<td>
												<?=get_burst_amount($block['total_fee'],2);?> Burst
											</td>
										</tr>
										<tr>
											<td>
												Block Signature
											</td>
											<td>
												<span class="word_break small"><?=str2bin($block['block_signature'])?></span>
											</td>
										</tr>
									</table>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</td>
			<td>
				<?=$asset_info['name']?>
			</td>
			<td>
				<a href="?action=account&account=<?=toUnsignedLong($ah['account_id'])?>"><?=show_account_id_name_and_or_rs($ah['account_id'],6,$block['height'])?></a><br>
				<?=blocks_short_name(show_account_id_name_and_or_rs($ah['account_id'],9),26)?>
			</td>
			<td>
				<?=asset_quantity($ah['quantity'],$asset_info['decimals'])?><br>
			</td>
			<td>
				<?=asset_quantity($ah['unconfirmed_quantity'],$asset_info['decimals'])?>
			</td>
			<td>
				<?=asset_pct_owned($ah['quantity'],$asset_info['decimals'],$asset_info['quantity'],$asset_info['decimals'])?>%
			</td>
		</tr>
	<?
	}
	?>
	</table>
	<div class="float-right">
	<button type="button" class="btn btn-secondary" onclick="window.history.back();">Back</button>
	</div>
<?}?>
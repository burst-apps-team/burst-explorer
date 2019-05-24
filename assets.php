<?php
if($_GET['details']){
	include 'asset_inspect.php';
	die;
}

// Show pagination
$pages = ($_GET['page'])?(int)$_GET['page']:0;
$assets = get_assets(0,$pages);
$nr = get_asset_count();
echo show_pages($pages,'assets',$nr);

?>
<div>
	<table class="table table-striped small">
		<tr class="tr_header_bot_border">
			<td>
				ID
			</td>
			<td>
				Height
			</td>
			<td>
				Asset name
			</td>
			<td>
				Created
			</td>
			<td>
				Issuer
			</td>
			<td>
				Quantity
			</td>
		</tr>
<?foreach($assets as $as){
	$block = get_block_info($as['height']);
	$asset_transfers = get_asset_transfer_count($as['id'])[0]['count'];
	$asset_holders = count_asset_holders($as['id']);
	?>
		<tr>
			<td>
				<a href="#" data-toggle="modal" data-target="#myModal<?=$as['db_id']?>"><?=$as['db_id']?></a>
				<div>
					<!-- Modal -->
					<div class="modal fade topmargin" id="myModal<?=$as['db_id']?>" role="dialog">
						<div class="modal-dialog modal-md">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header"><span class="block_id_heeder">Asset: <?=$as['name']?></span>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body" class="">
									<table class="table table-striped">										
										<tr>
											<td>
												ID
											</td>
											<td>
												<?=toUnsignedLong($as['id'])?>
											</td>
										</tr>
										<tr>
											<td>
												Name
											</td>
											<td>
												<?=display_str($as['name'])?>
											</td>
										</tr>
										<tr>
											<td>
												Description
											</td>
											<td>
												<div class="word_break"><?=display_str($as['description'])?></div>
											</td>
										</tr>
										<tr>
											<td>
												Block
											</td>
											<td>
												<?=$as['height']?>
											</td>
										</tr>
										<tr>
											<td>
												Issuer
											</td>
											<td>
												<a href="?action=account&account=<?=show_account_id_name_and_or_rs($as['account_id'],7)?>"><?=show_account_id_name_and_or_rs($as['account_id'],6)?></a><br>
												<?=blocks_short_name(show_account_id_name_and_or_rs($as['account_id'],1),26)?>
											</td>
										</tr>
										<tr>
											<td>
												Quantity
											</td>
											<td>
												<?=asset_quantity($as['quantity'],$as['decimals'])?>
											</td>
										</tr>
										<tr>
											<td>
												Asset Transactions
											</td>
											<td>
												<?if($asset_transfers>=1){?><a href="?action=assets&details=transaction&id=<?=toUnsignedLong($as['id'])?>"><?=$asset_transfers?></a>
												<?}else{?>
												0
												<?}?>
											</td>
										</tr>
										<tr>
											<td>
												Asset Holders
											</td>
											<td>
												<a href="?action=assets&details=holders&id=<?=toUnsignedLong($as['id'])?>"><?=$asset_holders?></a>
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
				<a href="#" data-toggle="modal" data-target="#myModal<?=$block['height']?>"><?=$block['height']?></a>
				<div>
					<!-- Modal -->
					<div class="modal fade topmargin" id="myModal<?=$block['height']?>" role="dialog">
						<div class="modal-dialog modal-md">
							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header"><span class="block_id_heeder">Block #<?=$block['height']?></span>
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
												<a href="?action=block_inspect&height=<?=$block['height']?>"><?=number_of_transactions($block['height'])?></a>
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
				<span title="<?=display_str($as['description'])?>"><?=display_str(blocks_short_name($as['name'],26))?></span>
			</td>			
			<td>
				<?$timestamp = get_block_info($as['height']);?>
				<?=convert_time($timestamp['timestamp'])?>
			</td>
			<td>
				<a href="?action=account&account=<?=toUnsignedLong($as['account_id'])?>"><?=blocks_short_name(show_account_id_name_and_or_rs($as['account_id'],6),26)?></a><br>
				<?=show_account_id_name_and_or_rs($as['account_id'],1)?>
			</td>
			<td>
				<?=asset_quantity($as['quantity'],$as['decimals'])?>
			</td>
		</tr>	
<?}?>
	</table>
</div>
<?echo show_pages($pages,'assets',$nr);?>

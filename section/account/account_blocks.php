<?
$account_id = (int)fromUnsignedLong($_GET['account']);
$account_blocks = get_forged_blocks_account($account_id,1,$_GET['page']);
$pool_blocks = get_forged_blocks_account($account_id,3,$_GET['page']);
if($account_blocks!='') $merged_blocks = $account_blocks;
if($account_blocks=='') $merged_blocks = $pool_blocks;
$nr=$account_forged_block_count;
$pages = ($_GET['page'])?(int)$_GET['page']:0;
echo show_pages($pages,'account&account='.$_GET['account'].'&submenu=blocks',$nr);
?>
<table class="table table-striped small">
	<thead class="thead-light">
		<tr>
		<th>
			Height
		</th>
		<th>
			Created
		</th>
		<th>
			Reward
		</th>
		<th>
			Fee
		</th>
		<th>
			#TXs
		</th>
		<th>
			Pool
		</th>
		<th>
			Forger
		</th>
		</tr>
	</thead>
<?
foreach ($merged_blocks as $block){

$reward = block_reward($block['height']);
$block_info = get_block_info($block['height']);
$tid = microtime(true)*10000;
?>
	<tr>
		<td>
			#<a href="#" data-toggle="modal" data-target="#myModal<?=$tid?>"><?=$block['height']?></a>
			<div>
				<!-- Modal -->
				<div class="modal fade topmargin" id="myModal<?=$tid?>" role="dialog">
					<div class="modal-dialog modal-md">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header"><span class="block_id_heeder">Block #<?=$block_info['height']?></span>
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
											<a href="?action=block_inspect&height=<?=$block_info['height']?>"><?=number_of_transactions($block['height'])?></a>
										</td>
									</tr>
									<tr>
										<td>
											Burst sent
										</td>
										<td>
											<?=burst_value(get_burst_amount($block_info['total_amount'],2,1))?> Burst
										</td>
									</tr>
									<tr>
										<td>
											Transaction Fees
										</td>
										<td>
											<?=burst_value(get_burst_amount($block_info['total_fee'],2,1))?> Burst
										</td>
									</tr>
									<tr>
										<td>
											Timestamp
										</td>
										<td>
											<?=convert_time($block_info['timestamp'])?>
										</td>
									</tr>
									<tr>
										<td>
											Generator
										</td>
										<td>
											<a href="?action=account&account=<?=show_account_id_name_and_or_rs($block_info['generator_id'],4,$block_info['height'])?>"><?=show_account_id_name_and_or_rs($block_info['generator_id'],6,$block_info['height'])?></a>
											<?=blocks_short_name(show_account_id_name_and_or_rs($block_info['generator_id'],1,$block_info['height']),26)?>
										</td>
									</tr>
									<tr>
										<td>
											Pool
										</td>
										<td>
											<a href="?action=account&account=<?=show_account_id_name_and_or_rs($block_info['generator_id'],3,$block_info['height'])?>"><?=blocks_short_name(show_account_id_name_and_or_rs($block_info['generator_id'],2,$block_info['height']),26)?></a>
										</td>
									</tr>
									<tr>
										<td class="text-nowrap">
											Block Generation Time
										</td>
										<td>
											<?=convert_time($block_info['timestamp'])?>
										</td>
									</tr>
									<tr>
										<td>
											Base Target
										</td>
										<td>
											<?=$block_info['base_target']?>
										</td>
									</tr>
									<tr>
										<td>
											Size
										</td>
										<td>
											<?=get_block_size($block_info['payload_length'])?>
										</td>
									</tr>
									<tr>
										<td>
											Version
										</td>
										<td>
											<?=$block_info['version']?>
										</td>
									</tr>
									<tr>
										<td>
											Nonce
										</td>
										<td>
											<?=toUnsignedLong($block_info['nonce'])?>
										</td>
									</tr>
									<tr>
										<td>
											Block Reward
										</td>
										<td>
											<?=burst_value(block_reward($block_info['height']));?> Burst
										</td>
									</tr>
									<tr>
										<td>
											Block Reward Fees
										</td>
										<td>
											<?=burst_value(get_burst_amount($block_info['total_fee'],2,1))?> Burst
										</td>
									</tr>
									<tr>
										<td>
											Block Signature
										</td>
										<td>
											<span class="word_break small"><?=str2bin($block_info['block_signature'])?></span>
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
			<span class="" title="<?=convert_time($block_info['timestamp'])?>"><?echo time_elapsed_string('@'.($block_info['timestamp']+1407722400), true);?></span>
		</td>
		<td>
			<?=burst_value(block_reward($block['height']));?>
		</td>
		<td>
			<?=burst_value(get_burst_amount($block_info['total_fee'],2))?>
		</td>
		<td>
			<?
			if(number_of_transactions($block_info['height'])>=1){?>
			<span><a href="?action=block_inspect&height=<?=$block['height']?>"><?=number_of_transactions($block['height'])?></a></span>
			<?}else{?>
			0
			<?}?>
		</td>
		<td>
			<a href="?action=account&account=<?=show_account_id_name_and_or_rs($block['generator_id'],3,$block['height'])?>"><?=show_account_id_name_and_or_rs($block['generator_id'],2,$block['height'],1)?></a>
		</td>
		<td>
			<a href="?action=account&account=<?=show_account_id_name_and_or_rs($block['generator_id'],4,$block['height'])?>"><?=blocks_short_name(show_account_id_name_and_or_rs($block['generator_id'],1,$block['height']),26)?></a>
		</td>
	</tr>
<?}?>
</table>		
<?echo show_pages($pages,'account&account='.$_GET['account'].'&submenu=blocks',$nr);?>
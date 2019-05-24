<?
// Show pagination
$pages = ($_GET['page'])?(int)$_GET['page']:0;
$nr = get_blocks_count();
echo show_pages($pages,'blocks',$nr);
// Get blocks
$blocks = get_blocks($pages);

debug(false,$blocks);
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
				Sent
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

foreach ($blocks as $block){
	if(!$pre_run){
		$ff = query_execute('select height from block_forger where height='.$block['height']);
		if(!$ff['height']){
		$pre_run = true;
		continue;
		}
		$pre_run = true;
	}

	$reward = block_reward($block['height']);
		
?>
		<tr>
			<td>
				#<a href="#" data-toggle="modal" data-target="#myModal<?=$block['height']?>"><?=$block['height']?></a>
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
												<?=show_account_id_name_and_or_rs($block['generator_id'],9,$block['height'])?>
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
												<?
												if($block['height']<=0)$show_height=false;
												else $show_height=($block['height']-1);
												?>
												<a href="#" data-dismiss="modal" data-toggle="modal" data-target="#myModal<?=$show_height?>"><?=$show_height?></a>
											</td>
										</tr>
										<tr>
											<td>
												Next Block
											</td>
											<td>
											<?if($block['next_block_id']){?>
												<a href="#" data-dismiss="modal" data-toggle="modal" data-target="#myModal<?=$block['height']+1?>"><?=$block['height']+1?></a>
											<?}?>
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
				<span><?=convert_time($block['timestamp'])?></span>
			</td>
			<td>
				<?=burst_value(block_reward($block['height'])+get_burst_amount($block['total_fee'],2,1));?>
			</td>
			<td>
				<?=burst_value(get_burst_amount($block['total_amount'],2,1))?>
			</td>
			<td>
				<?
				if(number_of_transactions($block['height'])>=1){?>
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
<?
}
?>
</table>		
<?echo show_pages($pages,'blocks',$nr);?>

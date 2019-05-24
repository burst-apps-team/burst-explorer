<?php 
$account_id = (int)fromUnsignedLong($_GET['account']);
$nr = $pool_member_count;
$pages = ($_GET['page'])?(int)$_GET['page']:0;
$pool_members = get_pool_members($account_id,$pages);
echo show_pages($pages,'account&account='.$_GET['account'].'&submenu=apm',$nr);
?>
<table class="table table-striped small">
	<thead class="thead-light">
		<tr>
			<th>
				Height
			</th>
			<th>
				Since Height
			</th>
			<th>
				Acount
			</th>
			<th>
				Forged
			</th>
		</tr>
	</thead>
<?
foreach($pool_members as $pm){
$block = get_block_info($pm['height']);
?>
	<tr>
		<td>
			#<a href="#" data-toggle="modal" data-target="#myModal<?=$pm['height']?>"><?=$pm['height']?></a>
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
									<tr>
										<td>
											Previous Block
										</td>
										<td>
											<a href="#" data-dismiss="modal" data-toggle="modal" data-target="#myModal<?=$block['height']-1?>"><?=$block['height']-1?></a>
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
			#<a href="#" data-toggle="modal" data-target="#myModal<?=$pm['from_height']?>"><?=$pm['from_height']?></a>
			<div>
				<!-- Modal -->
				<div class="modal fade topmargin" id="myModal<?=$block['from_height']?>" role="dialog">
					<div class="modal-dialog modal-md">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header"><span class="block_id_heeder">Block #<?=toUnsignedLong($block['id'])?></span>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body" class="">
								<table class="table table-striped">										
									<tr>
										<td>
											Height
										</td>
										<td>
											<?=$block['from_height']?>
										</td>
									</tr>
									<tr>
										<td>
											Transactions
										</td>
										<td>
											<?=number_of_transactions($block['from_height'])?>
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
									<tr>
										<td>
											Previous Block
										</td>
										<td>
											<a href="#" data-dismiss="modal" data-toggle="modal" data-target="#myModal<?=$block['height']-1?>"><?=$block['height']-1?></a>
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
			<a href="?action=account&account=<?=show_account_id_name_and_or_rs($pm['account_id'],7)?>"><?=blocks_short_name(show_account_id_name_and_or_rs($pm['account_id'],1,$pm['height']),26)?></a>	
		</td>
		<td>
			<?
			print_r(get_forged_blocks($pm['account_id'],10,$account_id))
			?>
		</td>
	</tr>
<?}?>
</table>		
<?echo show_pages($pages,'account&account='.$_GET['account'].'&submenu=apm',$nr);?>
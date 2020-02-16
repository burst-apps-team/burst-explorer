<?php 
$account_id = (int)fromUnsignedLong($_GET['account']);

// Show pagination
$nr = automated_transactions_count();
$pages = ($_GET['page'])?(int)$_GET['page']:0;
$automated_transactions = automated_transactions(0,$pages);
echo show_pages($pages,'ats',$nr);
?>
<table class="table table-striped small">
	<thead class="thead-light">
		<tr>
			<th>
				ID
			</th>
			<th>
				Block
			</th>
			<th>
				Name
			</th>
			<th>
				Creator
			</th>
			<th>
				Contract Address 
			</th>
		</tr>
	</thead>
<?

foreach ($automated_transactions as $at){
	//echo'<pre>';
	//print_r($at);die;
	//echo'</pre>';
	$block = get_block_info($at['height']);
	$at_info = at_info($at['id']);
	debug(false,$at);
	$tid = microtime(true)*10000;
	?>
	<tr>
		<td>
			<a href="#" data-toggle="modal" data-target="#myModal<?=$tid+1?>"><span><?=$at['db_id']?></span></a>
			<div>
				<!-- Modal -->
				<div class="modal fade topmargin" id="myModal<?=$tid+1?>" role="dialog">
					<div class="modal-dialog modal-md">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header"><span class="block_id_heeder"><?=blocks_short_name(display_str($at['name']),26)?></span>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body" class="">
								<table class="table table-striped">										
									<tr>
										<td>
											Name
										</td>
										<td>
											<?=display_str($at['name'])?>
										</td>
									</tr>
									<tr>
										<td>
											Description
										</td>
										<td>
											<div class="word_break"><?=display_str($at['description'])?></div>
										</td>
									</tr>
									<tr>
										<td>
											Creator
										</td>
										<td>
											<a href="?action=account&account=<?=show_account_id_name_and_or_rs($at['creator_id'],7)?>"><?=show_account_id_name_and_or_rs($at['creator_id'],6)?></a><br>
											<?=blocks_short_name(show_account_id_name_and_or_rs($at['creator_id'],1),26)?>
										</td>
									</tr>
									<tr>
										<td>
											Freeze when same balance
										</td>
										<td>
											<?
											if($at_info['freeze_when_same_balance']==0)echo 'No';
											else echo 'Yes';
											?>
										</td>
									</tr>
									<tr>
										<td>
											Minimum activate amount
										</td>
										<td>
											<?=burst_value(get_burst_amount($at_info['min_activate_amount'],2,1))?> Burst
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
			<a href="#" data-toggle="modal" data-target="#myModal<?=$tid?>"><?=$block['height']?></a>
			<div>
				<!-- Modal -->
				<div class="modal fade topmargin" id="myModal<?=$tid?>" role="dialog">
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
			<a href="#" data-toggle="modal" data-target="#myModal<?=$tid+2?>"><div class=""><?=blocks_short_name(display_str($at['name']),26)?></div></a>
			<div>
				<!-- Modal -->
				<div class="modal fade topmargin" id="myModal<?=$tid+2?>" role="dialog">
					<div class="modal-dialog modal-md">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header"><span class="block_id_heeder"><?=blocks_short_name(display_str($at['name']),26)?></span>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body" class="">
								<table class="table table-striped">										
									<tr>
										<td>
											Name
										</td>
										<td>
											<?=display_str($at['name'])?>
										</td>
									</tr>
									<tr>
										<td>
											Description
										</td>
										<td>
											<div class="word_break"><?=display_str($at['description'])?></div>
										</td>
									</tr>
									<tr>
										<td>
											Creator
										</td>
										<td>
											<a href="?action=account&account=<?=show_account_id_name_and_or_rs($at['creator_id'],7)?>"><?=show_account_id_name_and_or_rs($at['creator_id'],6)?></a><br>
											<?=blocks_short_name(show_account_id_name_and_or_rs($at['creator_id'],1),26)?>
										</td>
									</tr>
									<tr>
										<td>
											Freeze when same balance
										</td>
										<td>
											<?
											if($at_info['freeze_when_same_balance']==0)echo 'No';
											else echo 'Yes';
											?>
										</td>
									</tr>
									<tr>
										<td>
											Minimum activate amount
										</td>
										<td>
											<?=burst_value(get_burst_amount($at_info['min_activate_amount'],2,1))?> Burst
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
			<a href="?action=account&account=<?=show_account_id_name_and_or_rs($at['creator_id'],7)?>"><?=show_account_id_name_and_or_rs($at['creator_id'],6)?></a><br>
			<?=blocks_short_name(show_account_id_name_and_or_rs($at['creator_id'],1),26)?>
		</td>
		<td>
			<a href="?action=account&account=<?=show_account_id_name_and_or_rs($at['id'],7)?>"><?=show_account_id_name_and_or_rs($at['id'],6)?></a><br>
			<?=blocks_short_name(show_account_id_name_and_or_rs($at['id'],1),26)?>
		</td>
	</tr>
<?
}
?>
</table>
<?echo show_pages($pages,'ats',$nr);?>		
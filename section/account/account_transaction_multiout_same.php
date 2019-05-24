<?
$nr = $multiout_same['ct'];
$pages = ($_GET['page'])?(int)$_GET['page']:0;
$multiout = get_multiout_same_transaction($account['id'],$pages);
foreach($multiout as $mu){
	$trans = get_transactions($mu['transaction_id']);
	$trans['multi_amount'] = $mu['amount'];
	$trans['multi_recipient'] = $mu['recipient_id'];
	$transactions[$trans['height']] = $trans;
}
krsort($transactions);
?>
Recived payments
<?
echo show_pages($pages,'account&account='.$_GET['account'].'&submenu=at_multi_same',$nr);
?>
<table class="table table-striped small">
	<thead class="thead-light">
		<tr>
			<th>
				Height
			</th>
			<th>
				Type
			</th>
			<th>
				Burst Amount
			</th>
			<th>
				Sender
			</th>
			<th>
				Transaction ID
			</th>
			<th>
				Date
			</th>
		</tr>
	</thead>

<?
foreach($transactions as $as){
	$block = get_block_info($as['height']);	
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
											Transaction Fees
										</td>
										<td>
											<?=burst_value(get_burst_amount($block['total_fee'],2,1))?> Burst
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
			<?=transactions_type($as['type'],$as['subtype'])?><br>
			<?$multiouts = parseMultiOutSame($as['attachment_bytes']);?>
				<!-- Button trigger modal -->
				<a href="#" data-toggle="modal" data-target="#exampleModal<?=$block['height']?>">
					View Multiout's
				</a>
				<!-- Modal -->
				<div class="modal fade" id="exampleModal<?=$block['height']?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog" role="document">
					<div class="modal-content">
					  <div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Multiout Payments</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
					  </div>
					  <div class="modal-body">
						<table class="table table-striped table-sm">
						<?foreach($multiouts as $recipient){?>
							<tr>
								<td>
									<a href="?action=account&account=<?=$recipient?>"><?=show_account_id_name_and_or_rs($recipient,6)?></a><br>
								</td>
								<td><?//print_r($recipient);?>
									-> <span class="positiv_nr"><?echo '+'.burst_value(get_burst_amount($as['multi_amount'],2,1)).'</span> Burst<br>';?>
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
		</td>
		<td>
			<?
			if($as['multi_recipient']==$account['id']){
				echo '<span class="positiv_nr">+'.burst_value(get_burst_amount($as['multi_amount'],2,1)).'</span>';
				$earnings=1;
			}
			else {
				echo '<span class="negativ_nr">-'.burst_value(get_burst_amount($as['multi_amount'],2,1)).'</span>';
				$earnings=0;
			}		
			?>
		</td>
		<td>
			<a href="?action=account&account=<?=toUnsignedLong($as['sender_id'])?>"><?=show_account_id_name_and_or_rs($as['sender_id'],6)?></a>
			<br><span class=" small"><?=show_account_id_name_and_or_rs($as['sender_id'],1)?></span>
		</td>
		<td>
			<a href="?action=transaction&id=<?=toUnsignedLong($as['id'])?>"><?=toUnsignedLong($as['id'])?></a>
		</td>
		<td>
			<?=convert_time($as['timestamp'])?>
		</td>
	</tr>	
<?}?>
</table>
<?echo show_pages($pages,'account&account='.$_GET['account'].'&submenu=at_multi',$nr);?>
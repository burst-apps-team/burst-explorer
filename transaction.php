<?php 
$trans_id = $_GET['id'];
$transaction = get_transactions(fromUnsignedLong($trans_id));
$block = get_block_info($transaction['height']);
?>
<div class="tab-pane fade show active" id="account" role="tabpanel" aria-labelledby="account-tab">
		<b>Transaction</b> #<?=$trans_id?>
		<table class="table table-striped">
			<tr>
				<td>
					Sender
				</td>
				<td>
					<a href="?action=account&account=<?=toUnsignedLong($transaction['sender_id'])?>"><?=show_account_id_name_and_or_rs($transaction['sender_id'],6)?></a>
				</td>
			</tr>
			<tr>
				<td>
					Recipient(s)
				</td>
				<td>
					<?if($transaction['type']==0 && $transaction['subtype']==0){?>
						<a href="?action=account&account=<?=toUnsignedLong($transaction['recipient_id'])?>"><?=show_account_id_name_and_or_rs($transaction['recipient_id'],6)?></a>
					<?} elseif($transaction['type']==0 && $transaction['subtype']==1){
						// MultiOut
						$multiouts = parseMultiOut($transaction['attachment_bytes']);?>
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
										<a href="?action=account&account=<?=$recipient[0]?>"><?=show_account_id_name_and_or_rs($recipient[0],6,$as['height'])?></a><br>
									</td>
									<td>
										-> <span class="positiv_nr"><?echo '+'.burst_value(get_burst_amount($recipient[1],2,1)).'</span> Burst<br>';?>
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
					<?} elseif($transaction['type']==0 && $transaction['subtype']==2){
						// MultiOutSame
						$multiouts = parseMultiOutSame($transaction['attachment_bytes']);
						$transaction['multi_amount'] = ($transaction['amount']/count($multiouts));
						?>
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
										-> <span class="positiv_nr"><?echo '+'.burst_value(get_burst_amount($transaction['multi_amount'],2,1)).'</span> Burst<br>';?>
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
					<?}?>
				</td>
			</tr>
			<tr>
				<td>
					Amount
				</td>
				<td>
					<?=burst_value(get_burst_amount($transaction['amount'],2,1))?> Burst
				</td>
			</tr>
			<tr>
				<td>
					Fee
				</td>
				<td>
					<?=burst_value(get_burst_amount($transaction['fee'],2,1))?> Burst
				</td>
			</tr>
			<tr>
				<td>
					Height
				</td>
					<td>
					<a href="#" data-toggle="modal" data-target="#myModal<?=$block['height']?>"><?=$transaction['height']?></a>
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
													<div class="word_break small">
														<?=burst_value(get_burst_amount($block['total_fee'],2,1))?> Burst
													</div>
												</td>
											</tr>
											<tr>
												<td>
													Block Signature
												</td>
												<td>
													<div class="word_break small">
														<?=str2bin($block['block_signature'])?>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													Previous Block
												</td>
												<td>
													<?=$block['height']-1?>
												</td>
											</tr>
											<tr>
												<td>
													Next Block
												</td>
												<td>
												<?if($block['next_block_id']){?>
													<?=$block['height']+1?>
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
			</tr>
			<tr>
				<td>
					Type
				</td>
				<td>
					<?=transactions_type($transaction['type'],$transaction['subtype'])?>
				</td>
			</tr>
			<tr>
				<td>
					Date
				</td>
				<td>
					<?=convert_time($transaction['timestamp'])?>
				</td>
			</tr>
			<tr>
				<td>
					Signature
				</td>
				<td>
					<div class="word_break small">
						<?=str2bin($transaction['signature'])?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					Full Hash
				</td>
				<td>
					<div class="word_break small">
						<?=str2bin($transaction['full_hash'])?>
					</div>
				</td>
			</tr>
			<tr>
				<td>
					Message
				</td>
				<td>
					<?
					if($transaction['has_message']==1){
						$string = $transaction['attachment_bytes'];
						print_r(utf8_encode(trim($string,"\x00..\x1F")));
					} else {
						echo'N/A';
					}
					?>
				</td>
			</tr>
		</table>
		<div class="float-right">
			<button type="button" class="btn btn-secondary" onclick="window.history.back();">Back</button>
		</div>
	</div>
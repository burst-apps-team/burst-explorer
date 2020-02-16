<?php 
$account_id = (int)fromUnsignedLong($_GET['account']);
$nr = get_market_count();
$pages = ($_GET['page'])?(int)$_GET['page']:0;
$all_goods = get_market_info(0,$pages);
echo show_pages($pages,'marketplace',$nr);

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
				Issuer
			</th>
			<th>
				Quantity
			</th>
			<th>
				Burst Price
			</th>
		</tr>
	</thead>
<?

foreach ($all_goods as $goods){
$block = get_block_info($goods['height']);
$tid = microtime(true)*10000;
//print_r($goods);die;

?>
	<tr>
		<td>
			<a href="#" data-toggle="modal" data-target="#myModal<?=$tid+1?>"><?=$goods['db_id']?></a>
			<div>
				<!-- Modal -->
				<div class="modal fade topmargin" id="myModal<?=$tid+1?>" role="dialog">
					<div class="modal-dialog modal-md">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header"><span class="block_id_heeder"><?=blocks_short_name(display_str($goods['name']),60)?></span>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body" class="">
								<table class="table table-striped">										
									<tr>
										<td>
											Name
										</td>
										<td>
											<?=display_str($goods['name'])?>
										</td>
									</tr>
									<tr>
										<td>
											Description
										</td>
										<td>
											<div class="word_break"><?=display_str($goods['description'])?></div>
										</td>
									</tr>
									<tr>
										<td>
											Issuer
										</td>
										<td>
											<a href="?action=account&account=<?=show_account_id_name_and_or_rs($goods['seller_id'],4)?>"><?=show_account_id_name_and_or_rs($goods['seller_id'],6)?></a><br>
											<?=blocks_short_name(show_account_id_name_and_or_rs($goods['seller_id'],7),26)?>
										</td>
									</tr>
									<tr>
										<td>
											Quantity
										</td>
										<td>
											<?=$goods['quantity']?>
										</td>
									</tr>
									<tr>
										<td>
											Price
										</td>
										<td>
											<?=burst_value(get_burst_amount($goods['price'],2,1))?>
										</td>
									</tr>
									<tr>
										<td>
											Sold to
										</td>
										<td>
											<?
											$buyers = get_sold_goods($goods['id']);
											if(!$buyers)echo 'None';
											foreach($buyers as $by){?>
												<a href="?action=account&account=<?=show_account_id_name_and_or_rs($by['buyer_id'],7)?>"><?=show_account_id_name_and_or_rs($by['buyer_id'],6)?></a><br>
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
			<a href="#" data-toggle="modal" data-target="#myModal<?=$tid+2?>"><?=blocks_short_name(display_str($goods['name']),26)?></a>
			<div>
				<!-- Modal -->
				<div class="modal fade topmargin" id="myModal<?=$tid+2?>" role="dialog">
					<div class="modal-dialog modal-md">
						<!-- Modal content-->
						<div class="modal-content">
							<div class="modal-header"><span class="block_id_heeder"><?=blocks_short_name(display_str($goods['name']),60)?></span>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div class="modal-body" class="">
								<table class="table table-striped">										
									<tr>
										<td>
											Name
										</td>
										<td>
											<?=display_str($goods['name'])?>
										</td>
									</tr>
									<tr>
										<td>
											Description
										</td>
										<td>
											<div class="word_break"><?=display_str($goods['description'])?></div>
										</td>
									</tr>
									<tr>
										<td>
											Issuer
										</td>
										<td>
											<a href="?action=account&account=<?=show_account_id_name_and_or_rs($goods['seller_id'],7)?>"><?=show_account_id_name_and_or_rs($goods['seller_id'],6)?></a><br>
											<?=blocks_short_name(show_account_id_name_and_or_rs($goods['seller_id'],1),26)?>
										</td>
									</tr>
									<tr>
										<td>
											Quantity
										</td>
										<td>
											<?=$goods['quantity']?>
										</td>
									</tr>
									<tr>
										<td>
											Price
										</td>
										<td>
											<?=burst_value(get_burst_amount($goods['price'],2,1))?>
										</td>
									</tr>
									<tr>
										<td>
											Sold to
										</td>
										<td>
											<?
											$buyers = get_sold_goods($goods['id']);
											if(!$buyers)echo 'None';
											foreach($buyers as $by){?>
												<a href="?action=account&account=<?=show_account_id_name_and_or_rs($by['buyer_id'],7)?>"><?=show_account_id_name_and_or_rs($by['buyer_id'],6)?></a><br>
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
			<a href="?action=account&account=<?=show_account_id_name_and_or_rs($goods['seller_id'],7)?>"><?=show_account_id_name_and_or_rs($goods['seller_id'],6)?></a><br>
			<?=blocks_short_name(show_account_id_name_and_or_rs($goods['seller_id'],1),26)?>
		</td>
		<td>
			<?=$goods['quantity']?>
		</td>
		<td>
			<?=burst_value(get_burst_amount($goods['price'],2,1))?>
		</td>
	</tr>
<?
}
?>
</table>
<?echo show_pages($pages,'marketplace',$nr);?>
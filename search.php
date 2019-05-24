<script type="text/javascript">
    $(window).on('load',function(){
        $('#myModal').modal('show');
    });
</script>
<?
$block = get_block_info($search['height']);
?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
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
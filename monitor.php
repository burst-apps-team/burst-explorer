<?php 
if($_GET['hash']){
	$hash = $_GET['hash'];
	$result = query_execute_unsafe('select * from monitor where passphrase="'.mysqli_real_escape_string($db_link,$hash).'"');
	if($result){
		query_execute_unsafe('delete from monitor where passphrase="'.$result['passphrase'].'"');
		echo '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> You will no longer monitor '.RS_encode($result['account_id']).'</div>';
	} else {
		echo '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Hash in invalid.</div>';
		echo '<center><img src="image/logo/logo_bat_1.png"></center>';
	}
}

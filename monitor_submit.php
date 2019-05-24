<?php 
if($_POST['burst_adr']!='' && $_POST['burst_mail']!=''){
	$burst_adr = $_POST['burst_adr'];
	$email = $_POST['burst_mail'];
	if(strlen($burst_adr)==26 && strpos($burst_adr, 'BURST-') !== false && filter_var($email, FILTER_VALIDATE_EMAIL)){
		$account_id = fromUnsignedLong(RS_decode($burst_adr));
		$genPassPhrase = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
		
		$result = mysqli_fetch_assoc(mysqli_query($db_link, 'select count(*) as count from monitor where account_id='.$account_id.' and email="'.mysqli_real_escape_string($db_link,$email).'"'));
		if($result['count']>=1){
			$_SESSION["monitor_status"] = '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> But your subscription already existed.</div>';
		} else {
			mysqli_query($db_link, 'insert into monitor (account_id,passphrase,balance,email,welcome) values ("'.$account_id.'","'.$genPassPhrase.'",0,"'.mysqli_real_escape_string($db_link,$email).'","0")');
			$_SESSION["monitor_status"] = '<div class="alert alert-success"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Success!</strong> You\'re now monitoring.</div>';
		}
	} else {
		$_SESSION["monitor_status"] = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Something went wrong... Please check the Burst recipient and e-mail.</div>';
	}
} else $_SESSION["monitor_status"] = '<div class="alert alert-danger"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>Error!</strong> Something went wrong... Please check the Burst recipient or e-mail.</div>';
header('Location: '.$_POST["return_url"]);
die;
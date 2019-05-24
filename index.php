<?php
/*
# Burst Apps Team contribution -https://twitter.com/BurstAppsTeam
# Devolped by Zoh - https://twitter.com/Zoh63392187
# Donations: BURST-NMEA-GRHZ-BRFE-5SG6P
*/
// Insure HTTPS
if(empty($_SERVER['HTTPS'])) {
    header("Location: https://".$_SERVER['HTTP_HOST']);
    exit;
}

$menu_items = array('blocks','block_inspect','assets','marketplace','account','test','notification','asset_inspect','network_notification_system','ats','api','network_status','network_chart','search','monitor','transaction');
session_start();
include 'database.php';
include 'function.php';

if($_GET['action']=='monitor_submit')include 'monitor_submit.php';
?>
<head>
	<title>Burst Explorer</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=0.53">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href="css/global.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<?
if($_GET['search_box']!=''){
	$search = search($_GET['search_box']);
	if($search['type']=='block'){
		include 'search.php';
	}
	elseif($search['type']=='transaction'){
		header('Location: '.SITE_NAME.'/?action=block_inspect&height='.$search['height']);
	}
	elseif($search['type']=='account'){
		header('Location: '.SITE_NAME.'/?action=account&account='.$search['ID']);
	}elseif($search['type']=='name'){?>
		<script type="text/javascript">
			$(window).on('load',function(){
				$('#myModal_name').modal('show');
			});
		</script>
		<div class="modal fade" id="myModal_name" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<b>Search results</b>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
					Following account matched on name search:<br><br><center><b><?=blocks_short_name($search['txt'],26)?></b></center><br>
						<table class="table table-striped">
							<?
							foreach($search['ID'] as $account_id){?>
							<tr>
								<td>
									<a href="?action=account&account=<?=toUnsignedLong($account_id['id'])?>"><?=show_account_id_name_and_or_rs($account_id['id'],6)?></a><br>
									<?=show_account_id_name_and_or_rs($account_id['id'],1)?>
								</td>
							</tr>
							<?}?>
						</table>
						<i>Max 10 results</i>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	<?}else{?>
		<script type="text/javascript">
			$(window).on('load',function(){
				$('#myModal_nothing').modal('show');
			});
		</script>
		<div class="modal fade" id="myModal_nothing" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<b>Search - Nothing Found!</b>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						Note that you can only search for the following items:<br>
						<li>Account (BURST-NMEA-GRHZ-BRFE-5SG6P)</li>
						<li>Account (NMEA-GRHZ-BRFE-5SG6P)</li>
						<li>Blocks (Height)</li>
						<li>Transactions (647100804674806439)</li>
						<li>Account names (Result limit: 10)</li>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	<?}	
}
?>
<body>
<?
# Please do not remove credit or donation address in the below navbar
?>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-bottom">
	<span class="text-white">Developed by  Zoh <a href="https://twitter.com/Zoh63392187" target="_blank" class="text-info"><img src="image/twitter.png"></a> Member of Burst Apps Team <a href="https://twitter.com/BurstAppsTeam" target="_blank" class="text-info"><img src="image/twitter.png"></a> Donations: BURST-NMEA-GRHZ-BRFE-5SG6P</span>
</nav>
<nav class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
	<a class="navbar-brand" href="/"><img src="image/logo/LOGO_TEXT_BLUE_90x24.png"> Explorer</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="collapsibleNavbar">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link <?if($_GET['action']=='blocks' || !$_GET['action'] || $_GET['action']=='block_inspect')echo 'active';?>" href="?action=blocks">Blocks</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?if($_GET['action']=='assets')echo 'active';?>" href="?action=assets">Assets</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?if($_GET['action']=='marketplace')echo 'active';?>" href="?action=marketplace">Marketplace</a>
			</li>
			<li class="nav-item">
				<a class="nav-link <?if($_GET['action']=='ats')echo 'active';?>" href="?action=ats">ATs</a>
			</li>
			<li class="nav-item dropdown <?if($_GET['action']=='network_notification_system' || $_GET['action']=='network_chart' || $_GET['action']=='network_status')echo 'active';?>">
				<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
					Network
				</a>
				<div class="dropdown-menu">
					<a class="dropdown-item <?if($_GET['action']=='network_chart')echo 'active';?>" href="?action=network_chart"><i class="fas fa-chart-bar"></i> Chart</a>
					<a class="dropdown-item <?if($_GET['action']=='network_notification_system')echo 'active';?>" href="?action=network_notification_system"><i class="fas fa-envelope"></i> Notification</a>
					<a class="dropdown-item <?if($_GET['action']=='network_status')echo 'active';?>" href="?action=network_status"><i class="fas fa-signal"></i> Status</a>
				</div>
			</li>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
					Links
				</a>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="https://burst-coin.org" target="_blank"><i class="fas fa-link"></i> Burst-Coin</a>
					<a class="dropdown-item" href="https://discord.gg/G8N2QPa" target="_blank"><i class="fab fa-discord"></i> Burst Discord</a>
					<a class="dropdown-item" href="https://burstforum.net/" target="_blank"><i class="fas fa-satellite"></i> Burst Forum</a>					
					<a class="dropdown-item" href="https://github.com/PoC-Consortium/scavenger/releases" target="_blank"><i class="fab fa-github"></i> Burst Miner</a>
					<a class="dropdown-item" href="https://www.burstcoin.ist/" target="_blank"><i class="far fa-newspaper"></i> Burst News</a>
					<a class="dropdown-item" href="https://www.reddit.com/r/burstcoin/new" target="_blank"><i class="fab fa-reddit"></i> Burst Reddit</a>
					<a class="dropdown-item" href="https://t.me/burstcoin" target="_blank"><i class="fab fa-telegram"></i> Burst Telegram</a>
					<a class="dropdown-item" href="https://github.com/burst-apps-team/burstcoin/releases" target="_blank"><i class="fab fa-github"></i> Burst Wallet</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="http://wallet.burstcoin.network:8125/index.html" target="_blank"><i class="fab fa-github"></i> Online Wallet</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="https://github.com/burst-apps-team/burst-explorer/issues" target="_blank"><i class="fas fa-bug"></i> Bugs</a>
					<?if($_SERVER['REMOTE_ADDR']==$secureIP){?>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?=SITE_NAME?>/cron_.php"><i class="far fa-clock"></i> Cron</a>
					<a class="dropdown-item" href="<?=SITE_NAME?>/cron_network_status.php"><i class="far fa-clock"></i> Cron Network</a>
					<a class="dropdown-item" href="<?=SITE_NAME?>/cron_peers.php"><i class="far fa-clock"></i> Cron Peers</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="?action=flush_mem"><i class="fas fa-memory"></i> Flush Mem</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="?action=test"><i class="fas fa-vial"></i> Test</a>
					<?}?>
				</div>
			</li>
		</ul>
	</div>

<form class="nav navbar-form navbar-right" action="" method="get">
    <input type="text" size="36" name="search_box" class="form-control" placeholder="Search Account, Block, Names or Transaction" style="font-size:12px;">   
</form>
</nav>
<br><br><br>
<div class="container" style="container: 'body'">
	<?
	// Set cookie so users only see $show_notice one time
	if(!isset($_COOKIE[hash('sha256', 'newsite')])){
		setcookie(hash('sha256', 'newsite'), 'true', time() + (86400 * 30), "/");
		// Set to false for disable $show_notice
		$show_notice = false;
	}
	// Display message if true
	if($show_notice){
		echo '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert">&times;</a><strong>The Explorer has been moved to a Burst Apps Team website: https://explorer.burstcoin.network/</strong></div>';
	}
	if($_SESSION["monitor_status"]){
		echo $_SESSION["monitor_status"];
		session_unset();
	}
	// Include the page select from the menu if its in the array
	if(in_array($_GET['action'],$menu_items))include $_GET['action'].'.php';
	else include 'blocks.php';
	?>  
</div><br>

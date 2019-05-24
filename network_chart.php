<?
/*
# Burst Apps Team contribution -https://twitter.com/BurstAppsTeam
# Devolped by Zoh - https://twitter.com/Zoh63392187
# Donations: BURST-NMEA-GRHZ-BRFE-5SG6P
*/
?>
<ul class="nav nav-tabs small" id="myTab" role="tablist">
	<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle <?if($_GET['submenu']=='avg' || $_GET['submenu']=='blockchainsize' || $_GET['submenu']=='block_reward' || $_GET['submenu']=='circulation' || $_GET['submenu']=='burstmined' || $_GET['submenu']=='estimate')echo 'active';?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		BlockChain
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<a class="dropdown-item <?if($_GET['submenu']=='avg')echo 'active';?>" href="?action=network_chart&submenu=avg" role="tab"><i class="fas fa-chart-line"></i> Avg. Block Generation Time</a>
			<a class="dropdown-item <?if($_GET['submenu']=='blockchainsize')echo 'active';?>" href="?action=network_chart&submenu=blockchainsize" role="tab"><i class="fas fa-chart-area"></i> BlockChain Size</a>
			<a class="dropdown-item <?if($_GET['submenu']=='block_reward')echo 'active';?>" href="?action=network_chart&submenu=block_reward" role="tab"><i class="fas fa-chart-line"></i> Block Reward</a>
			<a class="dropdown-item <?if($_GET['submenu']=='circulation')echo 'active';?>" href="?action=network_chart&submenu=circulation" role="tab"><i class="fas fa-chart-line"></i> Burst In Circulation</a>
			<a class="dropdown-item <?if($_GET['submenu']=='burstmined')echo 'active';?>" href="?action=network_chart&submenu=burstmined" role="tab"><i class="fas fa-chart-area"></i> Burst Mined</a>
			<a class="dropdown-item <?if($_GET['submenu']=='difficulty')echo 'active';?>" href="?action=network_chart&submenu=difficulty" role="tab"><i class="fas fa-chart-area"></i> Difficulty</a>
			<a class="dropdown-item <?if($_GET['submenu']=='network_size' || $_GET['submenu']=='')echo 'active';?>" href="?action=network_chart&submenu=network_size" role="tab"><i class="fas fa-chart-area"></i> Estimated Network Size</a>
			<!--<div class="dropdown-divider"></div>-->
		</div>
	</li>
	<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle <?if($_GET['submenu']=='bf' || $_GET['submenu']=='bmp')echo 'active';?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		Miners
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<a class="dropdown-item <?if($_GET['submenu']=='bf')echo 'active';?>" href="?action=network_chart&submenu=bf" role="tab"><i class="fas fa-chart-pie"></i> Biggest Forger</a>
			<a class="dropdown-item <?if($_GET['submenu']=='bmp')echo 'active';?>" href="?action=network_chart&submenu=bmp" role="tab"><i class="fas fa-chart-pie"></i> Biggest Mining Pool</a>
			<!--<div class="dropdown-divider"></div>-->
		</div>
	</li>
	<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle <?if($_GET['submenu']=='trans_spd' || $_GET['submenu']=='atpb' || $_GET['submenu']=='tt' || $_GET['submenu']=='tpd')echo 'active';?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		Transactions
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<a class="dropdown-item <?if($_GET['submenu']=='trans_spd')echo 'active';?>" href="?action=network_chart&submenu=trans_spd" role="tab"><i class="fas fa-chart-area"></i> Amount Sent Per Day</a>
			<a class="dropdown-item <?if($_GET['submenu']=='atpb')echo 'active';?>" href="?action=network_chart&submenu=atpb" role="tab"><i class="fas fa-chart-line"></i> Avg. Transactions Per Block</a>
			<a class="dropdown-item <?if($_GET['submenu']=='tpd')echo 'active';?>" href="?action=network_chart&submenu=tpd" role="tab"><i class="fas fa-chart-area"></i> Transactions Per Day</a>
			<a class="dropdown-item <?if($_GET['submenu']=='tt')echo 'active';?>" href="?action=network_chart&submenu=tt" role="tab"><i class="fas fa-chart-line"></i> Transactions Total</a>
			<!--<div class="dropdown-divider"></div>-->
		</div>
	</li>
	<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle <?if($_GET['submenu']=='bd' || $_GET['submenu']=='richlist' || $_GET['submenu']=='tw')echo 'active';?>" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		Wallets
		</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
			<a class="dropdown-item <?if($_GET['submenu']=='bd')echo 'active';?>" href="?action=network_chart&submenu=bd" role="tab"><i class="fas fa-chart-pie"></i> Balance Distribution</a>
			<a class="dropdown-item <?if($_GET['submenu']=='richlist')echo 'active';?>" href="?action=network_chart&submenu=richlist" role="tab"><i class="fas fa-chart-pie"></i> Richlist</a>			
			<a class="dropdown-item <?if($_GET['submenu']=='wd')echo 'active';?>" href="?action=network_chart&submenu=wd" role="tab"><i class="fas fa-chart-line"></i> Wallets Daily</a>
			<a class="dropdown-item <?if($_GET['submenu']=='tw')echo 'active';?>" href="?action=network_chart&submenu=tw" role="tab"><i class="fas fa-chart-line"></i> Wallets Total</a>
			<!--<div class="dropdown-divider"></div>-->
		</div>
	</li>
</ul>
<?

if($_GET['submenu']=='block_reward'){echo $alert; include 'section/chart/block_reward.php';}
elseif($_GET['submenu']=='circulation'){echo $alert; include 'section/chart/circulation.php';}
elseif($_GET['submenu']=='burstmined'){echo $alert; include 'section/chart/burstmined.php';}
elseif($_GET['submenu']=='avg'){echo $alert; include 'section/chart/block_time.php';}
elseif($_GET['submenu']=='difficulty'){echo $alert; include 'section/chart/difficulty.php';}
elseif($_GET['submenu']=='network_size'){echo $alert; include 'section/chart/network_size.php';}
elseif($_GET['submenu']=='blockchainsize'){echo $alert; include 'section/chart/blockchainsize.php';}
elseif($_GET['submenu']=='bf'){echo $alert; include 'section/chart/bfat.php';}
elseif($_GET['submenu']=='bmp'){echo $alert; include 'section/chart/bmp.php';}
elseif($_GET['submenu']=='trans_spd'){echo $alert; include 'section/chart/trans_spd.php';}
elseif($_GET['submenu']=='atpb'){echo $alert; include 'section/chart/atpb.php';}
elseif($_GET['submenu']=='tt'){echo $alert; include 'section/chart/tt.php';}
elseif($_GET['submenu']=='tpd'){echo $alert; include 'section/chart/tpd.php';}
elseif($_GET['submenu']=='bd'){echo $alert; include 'section/chart/bd.php';}
elseif($_GET['submenu']=='richlist'){echo $alert; include 'section/chart/richlist.php';}
elseif($_GET['submenu']=='tw'){echo $alert; include 'section/chart/tw.php';}
elseif($_GET['submenu']=='wd'){echo $alert; include 'section/chart/wd.php';}
elseif($_GET['submenu']=='')include 'section/chart/network_size.php';
else {
	echo '<center>Comming soon!<br><img src="image/logo/logo_bat_1.png"></center>';
}
  
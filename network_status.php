<?
/*
# Burst Apps Team contribution -https://twitter.com/BurstAppsTeam
# Devolped by Zoh - https://twitter.com/Zoh63392187
# Donations: BURST-NMEA-GRHZ-BRFE-5SG6P
*/
?><b>Burst Network (24 hour)</b><br>
<center>
	<?
	$peers = get_peers();
	$count=0;
	$geo_ips = array();
	foreach($peers as $ip){
		$original_array = $memcached->get('geoip_'.$ip['address']);
		$geo_ips[$original_array['geoplugin_countryCode']]['amount']=$geo_ips[$original_array['geoplugin_countryCode']]['amount']+1;
	}
	?> 
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
		  google.charts.load('current', {
			'packages':['geochart'],
			'mapsApiKey': 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
		  });
		  google.charts.setOnLoadCallback(drawRegionsMap);

		  function drawRegionsMap() {
			var data = google.visualization.arrayToDataTable([
			  ['Country', 'Peers']
			  <?
				foreach($geo_ips as $gi => $all){
					echo ",['".$gi."', ".$all['amount']."]";
				}
			  ?>
			]);

			var options = {
				colorAxis: {colors: ['#efefef', '#337ab7']},
				datalessRegionColor: '#fdfdfd',
			};

			var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

			chart.draw(data, options);
		  }
		</script>
	<?
	$peers = query_to_array_unsafe('select * from peer_char where brs_version!="" and peer_time >= now() - interval 24 hour');
	foreach($peers as $peer){
		$versions[$peer['brs_version']]['amount'] = $versions[$peer['brs_version']]['amount']+1;
	}
	?>
	<script type="text/javascript">
	  google.charts.load('current', {'packages':['corechart']});
	  google.charts.setOnLoadCallback(drawChart);

	  function drawChart() {

		var data = google.visualization.arrayToDataTable([
		  ['Task', 'Some text']
		  <?
			krsort($versions);
			foreach($versions as $key => $amount){
				echo ",['".$key."',".$amount['amount']."]";
			}	 
		  ?>  
		]);

		var options = {
		  title: 'Peers Version (24 hour)'
		};

		var chart = new google.visualization.PieChart(document.getElementById('piechart'));

		chart.draw(data, options);
	  }
	</script>
	<?
	$date1=date_create(get_first_peer_active());
	$date2=date_create(date("Y-m-d", time()));
	$diff=date_diff($date1,$date2);
	?>
	<div id="regions_div"></div>
</center>
<div id="piechart" style="width:400px; height:400px;float:right;margin-top:-45px;"></div>
<div style="float:left">
<b>Based on Burst wallet 2.3.0</b><br>
<?=get_peers_count_all()?> active peers the last <?=$diff->format("%a days")?><br>
<?=get_peers_count_hour(24)?> active peers within the last 24 hour<br>
<?=get_peers_count_hour(1)?> active peers within the last hour
</div>
<br>
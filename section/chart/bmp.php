<?php 
if(!($bmrn = $memcached->get('bmrn'))){
	$min_height_30_days = query_to_array("select min(height) as min_height from block where timestamp >= ".(time()-1410400800));
	$bmrn = query_to_array("select count(*) as count, recipient_id from block_forger where height >=".$min_height_30_days[0]['min_height']." and recipient_id!=generator_id group by recipient_id order by count desc limit 100");
	$memcached->set('bmrn', $bmrn,86400);
	$bmrn = $memcached->get('bmrn');
}
if(!($bmat = $memcached->get('bmat'))){
	$bmat = query_to_array("select count(*) as count, recipient_id from block_forger where recipient_id!=generator_id group by recipient_id order by count desc limit 100");
	$memcached->set('bmat', $bmat,86400);
	$bmat = $memcached->get('bmat');
}
$account = show_account(fromUnsignedLong($_GET['account']));
?>
<b>Chart :: Miners :: Biggest Mining Pool</b><center>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type='text/javascript'>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day']
    <?
	foreach($bmrn as $acc){
		echo ",['".display_str(show_account_id_name_and_or_rs($acc['recipient_id'],10))." (".$acc['count'].")',".$acc['count']."]";
	}
	?>    
  
]);

var options = {
  title: 'Biggest Mining Pool: Right Now (last 31 days)',
  chartArea: {width: '100%'}
};

var chart = new google.visualization.PieChart(document.getElementById('piechart1'));

chart.draw(data, options);
}
</script>
<script type='text/javascript'>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day']
    <?
	foreach($bmat as $acc){
		echo ",['".display_str(show_account_id_name_and_or_rs($acc['recipient_id'],10))." (".$acc['count'].")',".$acc['count']."]";
	}
	?>    
  
]);

var options = {
  title: 'Biggest Mining Pool: All Time',
  chartArea: {width: '100%'}
};

var chart = new google.visualization.PieChart(document.getElementById('piechart2'));

chart.draw(data, options);
}
</script>
<div id="piechart1" style="height: 400px; width:100%"></div>  
<div id="piechart2" style="height: 400px; width:100%"></div>
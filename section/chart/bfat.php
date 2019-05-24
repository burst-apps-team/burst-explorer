<?php 
if(!($bfat = $memcached->get('bfat'))){
	$bfat = query_to_array("select count(*) as count, generator_id from block where 1 group by generator_id order by count desc limit 100");
	$memcached->set('bfat', $bfat,86400);
	$bfat = $memcached->get('bfat');
}
if(!($bfrn = $memcached->get('bfrn'))){
	$bfrn = query_to_array("select count(*) as count, generator_id from block where timestamp >= ".(time()-1415498400)." group by generator_id order by count desc limit 100");
	$memcached->set('bfrn', $bfrn,86400);
	$bfrn = $memcached->get('bfrn');
}
?>
<b>Chart :: Miners :: Biggest Forger</b><center>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type='text/javascript'>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day']
    <?
	foreach($bfrn as $acc){
		echo ",['".display_str(show_account_id_name_and_or_rs($acc['generator_id'],10))." (".$acc['count'].")',".$acc['count']."]";
	}
	?>    
  
]);

var options = {
  title: 'Biggest Forger: Right Now (last 31 days)',
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
	foreach($bfat as $acc){
		echo ",['".display_str(show_account_id_name_and_or_rs($acc['generator_id'],10))." (".$acc['count'].")',".$acc['count']."]";
	}
	?>    
  
]);

var options = {
  title: 'Biggest Forger: All Time',
  chartArea: {width: '100%'}
};

var chart = new google.visualization.PieChart(document.getElementById('piechart2'));

chart.draw(data, options);
}
</script>
<div id="piechart1" style="float:left;height: 400px; width:100%"></div>  
<div id="piechart2" style="float:left;height: 400px; width:100%"></div>
<?php 
if(!($richlist = $memcached->get('richlist'))){
	$richlist = query_to_array_unsafe("select * from account where latest='1' order by balance desc limit 50");
	$memcached->set('richlist', $richlist,3600);
	$richlist = $memcached->get('richlist');
}
?>
<b>Chart :: Wallets :: Richlist</b><center>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type='text/javascript'>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day']
  <?
  foreach($richlist as $acc){
		echo ",['".display_str(show_account_id_name_and_or_rs($acc['id'],10))."',".get_burst_amount($acc['balance'],2,1)."]";
	}
	?>
]);

var options = {
  title: 'Accounts Richlist',
  chartArea: {width: '100%'},
  
};

var chart = new google.visualization.PieChart(document.getElementById('piechart2'));

chart.draw(data, options);
}
</script>
<div id="piechart2" style="float:left;height: 400px; width:100%"></div>
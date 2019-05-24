<?php 
if(!($_0_1 = $memcached->get('_0_1'))){
	$_0_1 = query_execute_unsafe("select count(*) as count from account where balance >0 and balance <=10000000 and public_key!='' and latest='1'");
	$memcached->set('_0_1', $_0_1,3600);
	$_0_1 = $memcached->get('_0_1');
}
if(!($_1_99 = $memcached->get('_1_99'))){
	$_1_99 = query_execute_unsafe("select count(*) as count from account where balance >=10000000 and balance <1000000000 and public_key!='' and latest='1'");
	$memcached->set('_1_99', $_1_99,3600);
	$_1_99 = $memcached->get('_1_99');
}
if(!($_100_1000 = $memcached->get('_100_1000'))){
	$_100_1000 = query_execute_unsafe("select count(*) as count from account where balance >=1000000000 and balance <=10000000000 and public_key!='' and latest='1'");
	$memcached->set('_100_1000', $_100_1000,3600);
	$_100_1000 = $memcached->get('_100_1000');
}
if(!($_1000_10000 = $memcached->get('_1000_10000'))){
	$_1000_10000 = query_execute_unsafe("select count(*) as count from account where balance >=10000000000 and balance <=100000000000 and public_key!='' and latest='1'");
	$memcached->set('_1000_10000', $_1000_10000,3600);
	$_1000_10000 = $memcached->get('_1000_10000');
}
if(!($_10000_100000 = $memcached->get('_10000_100000'))){
	$_10000_100000 = query_execute_unsafe("select count(*) as count from account where balance >=100000000000 and balance <=1000000000000 and public_key!='' and latest='1'");
	$memcached->set('_10000_100000', $_10000_100000,3600);
	$_10000_100000 = $memcached->get('_10000_100000');
}
if(!($_100000_1000000 = $memcached->get('_100000_1000000'))){
	$_100000_1000000 = query_execute_unsafe("select count(*) as count from account where balance >=1000000000000 and balance <=10000000000000 and public_key!='' and latest='1'");
	$memcached->set('_100000_1000000', $_100000_1000000,3600);
	$_100000_1000000 = $memcached->get('_100000_1000000');
}
if(!($_1000000_10000000 = $memcached->get('_1000000_10000000'))){
	$_1000000_10000000 = query_execute_unsafe("select count(*) as count from account where balance >=10000000000000 and balance <=100000000000000 and public_key!='' and latest='1'");
	$memcached->set('_1000000_10000000', $_1000000_10000000,3600);
	$_1000000_10000000 = $memcached->get('_1000000_10000000');
}
if(!($_10000000_plus = $memcached->get('_10000000_plus'))){
	$_10000000_plus = query_execute_unsafe("select count(*) as count from account where balance >=100000000000000 and public_key!='' and latest='1'");
	$memcached->set('_10000000_plus', $_10000000_plus,3600);
	$_10000000_plus = $memcached->get('_10000000_plus');
}
?>
<b>Chart :: Wallets :: Balance Distribution</b><center>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type='text/javascript'>
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

var data = google.visualization.arrayToDataTable([
  ['Task', 'Hours per Day']
  ,['Burst: 0 - 1',<?=$_0_1['count']?>]
  ,['Burst: 1 - 99',<?=$_1_99['count']?>]
  ,['Burst: 100 - 1.000',<?=$_100_1000['count']?>]
  ,['Burst: 1.000 - 10.000',<?=$_1000_10000['count']?>]
  ,['Burst: 10.000 - 100.000',<?=$_10000_100000['count']?>]
  ,['Burst: 100.000 - 1.000.000',<?=$_100000_1000000['count']?>]
  ,['Burst: 1.000.000 - 10.000.000',<?=$_1000000_10000000['count']?>]
  ,['Burst: 10.000.000+',<?=$_10000000_plus['count']?>]  
]);

var options = {
  title: 'Accounts Burst Holdings',
  chartArea: {width: '100%'},
  
};

var chart = new google.visualization.PieChart(document.getElementById('piechart2'));

chart.draw(data, options);
}
</script>
<div id="piechart2" style="float:left;height: 400px; width:100%"></div>  
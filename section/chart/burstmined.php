<b>Chart :: BlockChain :: Burst Mined</b><center>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type='text/javascript'>
  google.charts.load('current', {'packages':['annotationchart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('date', 'Date');
	data.addColumn('number', 'Burst Mined Calculated');
	data.addColumn('string', 'Burst Coins');
	data.addColumn('number', 'Burst Mined Fees');
	data.addRows([
	  [new Date(2014,07,10), 0,undefined,0]
	  <?
		echo file_get_contents("section/chart/feed/burstmined");
	  ?>
	]);

	var chart = new google.visualization.AnnotationChart(document.getElementById('chart_div'));

	var options = {
	  displayAnnotations: true
	};

	chart.draw(data, options);
  }
</script>
<?
if(!($total_mined = $memcached->get('chart_total_mined'))){
		$last_block = query_execute('select height from block where 1 order by height desc limit 1');
		while($i <= $last_block['height']){
			$reward = ($reward + block_reward($i));
			$i++;
		}
		$memcached->set('chart_total_mined', $reward,360);
		$total_mined = $memcached->get('chart_total_mined');
	}
$unmined = 2158812800-$total_mined;

?>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {

	var data = google.visualization.arrayToDataTable([
	  ['Task', 'Hours per Day'],
	  ['Mined',   <?=$total_mined?>],
	  ['Unmined',  <?=$unmined?>]
	  
	]);

	var options = {
	  title: 'Mining Status'
	};

	var chart = new google.visualization.PieChart(document.getElementById('piechart'));

	chart.draw(data, options);
  }
</script> 
<div id='chart_div' style='height: 500px;'></div>
<div id="piechart" style="width: 900px; height: 500px;"></div>
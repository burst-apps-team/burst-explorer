<b>Chart :: BlockChain :: Burst Block Time</b><center>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type='text/javascript'>
  google.charts.load('current', {'packages':['annotationchart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('date', 'Date');
	data.addColumn('number', 'Avg. Block Creation Time');
	data.addRows([
	  <?
		echo file_get_contents("section/chart/feed/burst_avg_mined");
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
?>
<div id='chart_div' style='height: 500px;'></div>
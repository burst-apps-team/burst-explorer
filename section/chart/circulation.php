<b>Chart :: BlockChain :: Burst In Circulation</b><center>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type='text/javascript'>
  google.charts.load('current', {'packages':['annotationchart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('date', 'Date');
	data.addColumn('number', 'Burst In Circulation');
	data.addColumn('string', 'Burst Coins');
	data.addRows([
	  [new Date(2014,07,11), 108000000,undefined]
	  <?
		$time = strtotime("2014-8-11");
		$height =0;
		$reward = 108000000;
		while($time<=1809986400){
			$final = date("Y,m,d", $time);
			$time = strtotime("+30 days", $time);
			$height = $height+10800;
			$reward = $reward+(block_reward($height)*10800);
			echo ',[new Date('.$final.'), '.$reward.',undefined]';
		}
	  ?>
	]);

	var chart = new google.visualization.AnnotationChart(document.getElementById('chart_div'));

	var options = {
	  displayAnnotations: true
	};

	chart.draw(data, options);
  }
</script>
<div id='chart_div' style='height: 500px;'></div>
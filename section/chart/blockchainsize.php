<b>Chart :: BlockChain :: Blockchain Size</b><center>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type='text/javascript'>
  google.charts.load('current', {'packages':['annotationchart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('date', 'Date');
	data.addColumn('number', 'Transaction Size in MB');
	data.addColumn('number', 'Accounts Size in MB');
	data.addColumn('number', 'Block Size in MB');
	data.addColumn('number', 'Total Size in MB');
	data.addRows([
	  <?		
			echo file_get_contents("section/chart/feed/blockchainsize");
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
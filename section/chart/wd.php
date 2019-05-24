<b>Chart :: Wallets :: Wallet Daily</b><center>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type='text/javascript'>
  google.charts.load('current', {'packages':['annotationchart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
	var data = new google.visualization.DataTable();
	data.addColumn('date', 'Date');
	data.addColumn('number', 'Wallets Daily');
	data.addRows([
	  <?		
			echo file_get_contents("/var/www/html/section/chart/feed/wd");
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
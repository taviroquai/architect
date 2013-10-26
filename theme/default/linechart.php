<h3>Line Chart Demo</h3>
<div id="chart1" style="width: 100%; height: 300px"></div>
<h4>PHP</h4>
<pre>
$chart = app()->createLineChart();
$data = array(
    array("x" => "2011 W27", "y" => 100),
    array("x" => "2011 W28", "y" => 500)
);
$chart->set('data', $data)->set('ykeys', array('y'))->set('labels', array('Sells'));
c($chart);
</pre>
<script type="text/javascript">
    jQuery(function($) {
        var data = <?=json_encode($data)?>;
            Morris.Line({
            element: 'chart1',
            data: data,
            xkey: 'x',
            ykeys: <?=json_encode($ykeys)?>,
            labels: <?=json_encode($labels)?>
        });
    });
</script>
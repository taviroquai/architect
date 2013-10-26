<h3>Datepicker Demo</h3>
<div id="calendar1" class="input-append date">
    <input data-format="dd/MM/yyyy hh:mm:ss" type="text" />
    <span class="add-on">
        <i data-time-icon="icon-time" data-date-icon="icon-calendar">
        </i>
    </span>
</div>
<div class="clearfix"></div>
<em>Powered by Bootstrap Datetimepicker</em>
<h4>PHP</h4>
<pre>
$datepicker = app()->createDatepicker();
$this->addContent($datepicker);
</pre>
<h4>JS</h4>
<pre>
$('#calendar1').datetimepicker();
</pre>
<script type="text/javascript">
	$(function() {
  		$('#calendar1').datetimepicker({
    		pickTime: false,
    		format: 'yyyy/mm/dd',
      		language: 'en'
    	});
  	});
</script>
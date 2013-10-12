<h3>Datepicker Demo</h3>
<div id="calendar1" class="input-append date">
    <input data-format="dd/MM/yyyy hh:mm:ss" type="text" />
    <span class="add-on">
        <i data-time-icon="icon-time" data-date-icon="icon-calendar">
        </i>
    </span>
</div>
<script type="text/javascript">
	$(function() {
  		$('#calendar1').datetimepicker({
    		pickTime: false,
    		format: 'yyyy/mm/dd',
      		language: 'en'
    	});
  	});
</script>
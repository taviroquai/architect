<div id="<?=$_id?>" class="input-append date" title="Choose date">
    <input data-format="dd/MM/yyyy hh:mm:ss" type="text" />
    <span class="add-on">
        <i data-time-icon="icon-time" data-date-icon="icon-calendar">
        </i>
    </span>
</div>
<script type="text/javascript">
	$(function() {
  		$('#<?=$_id?>').datetimepicker({
    		pickTime: false,
    		format: 'yyyy/mm/dd',
      		language: 'en'
    	});
  	});
</script>
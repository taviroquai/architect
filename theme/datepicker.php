<div id="<?=$_id?>" class="input-append date" title="Choose date">
    <input data-format="dd/MM/yyyy hh:mm:ss" type="text" 
           name ="<?=$name?>" value="<?=$value?>"/>
    <span class="add-on">
        <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
    </span>
</div>
<script type="text/javascript">
jQuery(function($) {
    $('#<?=$_id?>').datetimepicker({
        pickTime: false,
        format: 'yyyy-MM-dd',
        language: 'en'
    });
});
</script>

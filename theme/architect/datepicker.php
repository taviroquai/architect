<div id="<?=$_id?>" class="input-append date" title="Choose date">
    <input data-format="dd/MM/yyyy hh:mm:ss" type="text" value="<?=$default?>"/>
    <span class="add-on">
        <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
    </span>
</div>
<script type="text/javascript">
jQuery(function($) {
    $('#<?=$_id?>').datetimepicker({
        pickTime: false,
        format: 'yyyy-mm-dd',
        language: 'en'
    });
});
</script>
<?php $this->slot('content', function($item) { ?>
    <?=$item?>
<?})?>

<div id="<?=$_id?>" style="width: 100%; height: 300px" title="Line Chart"></div>
<script type="text/javascript">
    jQuery(function($) {
        var data = <?=json_encode($data)?>;
            Morris.Line({
            element: '<?=$_id?>',
            data: data,
            xkey: 'x',
            ykeys: <?=json_encode($ykeys)?>,
            labels: <?=json_encode($labels)?>
        });
    });
</script>
<?php $this->slot('content', function($item) { ?>
    <?=$item?>
<?})?>
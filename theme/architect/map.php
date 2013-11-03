<div id="<?=$_id?>" style="width: 100%; height: 300px" title="Map"></div>
<script type="text/javascript">
    jQuery(function($) {
        var style = {
            iconUrl: Arch.url('/arch/asset/img/pin.png'),
            iconSize: [32, 37],
            iconAnchor: [16, 37],
            popupAnchor: [0, -28]
        };
        var markers = <?=json_encode($this->model->getMarkers())?>;
        var <?=$_id?>_map = new Map('<?=$_id?>', <?=$lon?>, <?=$lat?>, <?=$zoom?>);
        <?=$_id?>_map.addMarkers(markers, style);
    });
</script>
<?php $this->slot('content', function($item) { ?>
    <?=$item?>
<?})?>
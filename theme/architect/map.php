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
        var map1 = new Map('<?=$_id?>', <?=$lon?>, <?=$lat?>, <?=$zoom?>);
        map1.addMarkers(markers, style);
    });
</script>
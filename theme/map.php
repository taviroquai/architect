<div id="<?=$_id?>" style="width: 100%; height: 300px" title="Map"></div>
<script type="text/javascript">
    jQuery(function($) {
        var style = {
            iconUrl: Arch.url('/arch/asset/img/pin.png'),
            iconSize: [32, 37],
            iconAnchor: [16, 37],
            popupAnchor: [0, -28]
        };
        var markers = <?=json_encode($markers)?>;
        var map_<?=$_id?> = new Map('<?=$_id?>', <?=$lon?>, <?=$lat?>, <?=$zoom?>);
        map_<?=$_id?>.addMarkers(markers, style);
    });
</script>

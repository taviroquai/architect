<h3>Map Demo</h3>
<div id="map1" style="width: 100%; height: 300px"></div>
<em>Powered by LeafletJS, Google API and Map Icons Collection</em>
<h4>PHP</h4>
<pre>
$map = app()->createMap()->set('lon', 0)->set('lat', 0)->set('zoom', 2);
$marker = $map->model->createMarker(0, 0, 'Hello Architect!', true);
$map->model->addMarker($marker);
c($map);
</pre>
<h4>Default Template</h4>
<pre>theme/default/map.php</pre>
<script type="text/javascript">
    jQuery(function($) {
        var style = {
            iconUrl: Arch.url('/arch/asset/img/pin.png'),
            iconSize: [32, 37],
            iconAnchor: [16, 37],
            popupAnchor: [0, -28]
        };
        var markers = <?=json_encode($this->model->getMarkers())?>;
        var map1 = new Map('map1', <?=$lon?>, <?=$lat?>, <?=$zoom?>);
        map1.addMarkers(markers, style);
    });
</script>
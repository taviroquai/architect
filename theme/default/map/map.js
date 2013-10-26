
function Map (id, lon, lat, zoom)
{
    this.base = new L.Google();
    this.map = L.map(id).setView([lon, lat], zoom);
    this.map.addLayer(this.base);
}

Map.prototype.addMarkers = function (markers, style)
{
    for (var i = 0; i < markers.length; i++ ) {
        var m = L.marker([markers[i].lon, markers[i].lat])
            .bindPopup(markers[i].popup);
        if (style) m.setIcon(L.icon(style));
        m.addTo(this.map);
        if (markers[i].open) m.openPopup();
    }
}

/**
 * Architect Map widget
 * @param {string} id - The map id
 * @param {float} lon - The center longitude
 * @param {float} lat - The center latitude
 * @param {integer} zoom - The map zoom level
 */
function Map (id, lon, lat, zoom)
{
    this.base = new L.Google();
    this.map = L.map(id).setView([lon, lat], zoom);
    this.map.addLayer(this.base);
}

/**
 * Adds markers to map
 * @param {Array} markers - The array of markers
 * @param {object} style - The style object
 */
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
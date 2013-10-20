
// default app client
UI = function(BASE_URL)
{
	
}

UI.url = function(path, params) {
    var base = INDEX_FILE == '' ? rtrim(BASE_URL, '/') : BASE_URL;
    var uri = path == undefined ? '' : path;
    var query = params == undefined ? '' : '?';
    if (params) {
        var ret = [];
        for (var d in params) {
           ret.push(d + "=" + params[d]);
        }
        query = query + ret.join("&");
    }
    return base+INDEX_FILE+uri+query;
}

/**
 * Listens for drag and drop events on selectors
 * @var string from The from element selector
 * @var string to The selector whichh receives the dragged element
 * @var function cb The drop callback
 * @var string attr The selector attribute which contains the data
 */
UI.drag = function(from, to, cb, attr)
{
    if (attr === undefined) attr = 'data-ui';
    $(document).on('dragstart', from, function(e) {
        e.originalEvent.dataTransfer.setData("Text", $(e.target).attr(attr));
    });

    $(document).on('dragover', to, function(e) {
        e.preventDefault();
        e.stopPropagation();
    });

    $(document).on('drop', to, function(e) {
        e.preventDefault();
        e.stopPropagation();
        var data = e.originalEvent.dataTransfer.getData("Text");
        cb(data);
    });
}
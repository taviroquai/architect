
/**
 * @class Arch - The client side Architect
 */
function Arch () {}

/**
 * Client side url function
 * @param {string} path - The relative path
 * @param {Array} params - The GET params
 */
Arch.url = function(path, params)
{
    var base = INDEX_FILE == '' ? 
        BASE_URL.substring(0, BASE_URL.length - 1) : BASE_URL;
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
 * @param {string} from - The from element selector
 * @param {string} to - The selector whichh receives the dragged element
 * @param {function} cb - The drop callback
 * @param {string} attr - The selector attribute which contains the data
 */
Arch.drag = function(from, to, cb, attr)
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
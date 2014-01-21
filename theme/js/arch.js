
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

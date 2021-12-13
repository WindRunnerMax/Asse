function getParams(key) {
    var url = window.location.href;
    var regex = new RegExp("[\?&]" + key + "=([^&]+)", "i");
    var match = regex.exec(url);
    if (match && match.length > 1) return decodeURI(match[1]);
    else return false;
}

export { getParams }
export default { getParams }
/**
 * Dicfro
 *
 * @copyright 2008-2013 Michel Corne
 * @license   http://www.opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

var
domainSubpath,
prevDictionary;

function autoSearchLastWord()
{
    var	action = document.getElementById('action').value;
	
    getcookie('no-auto-search') || searchLastWord(action);
}

function base64_encode( data )
{
    // phpjs.org
    var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var o1, o2, o3, h1, h2, h3, h4, bits, i = ac = 0, enc="", tmp_arr = [];

    if (!data) {
        return data;
    }

    data = utf8_encode(data+'');

    do {
        o1 = data.charCodeAt(i++);
        o2 = data.charCodeAt(i++);
        o3 = data.charCodeAt(i++);

        bits = o1<<16 | o2<<8 | o3;

        h1 = bits>>18 & 0x3f;
        h2 = bits>>12 & 0x3f;
        h3 = bits>>6 & 0x3f;
        h4 = bits & 0x3f;

        tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
    } while (i < data.length);

    enc = tmp_arr.join('');

    switch( data.length % 3 ){
        case 1:
            enc = enc.slice(0, -2) + '==';
        break;
        case 2:
            enc = enc.slice(0, -1) + '=';
        break;
    }

    return enc;
}

function displayLexromd(actionUrl)
{
    var image = document.getElementById('lexromd-img'),
    tome = document.getElementById('tome').value,
    page = document.getElementById('tome-page').value;

    page = parseInt(page);
    tome = parseInt(tome);

    if (validateLexromPage(tome, page)) {
        page = formatLexromPage(page);
        updateTabContents('lexromd-tab');
        image.src = actionUrl.replace('%s', tome).replace('%s', page);
    } else {
        updateTabContents('lexromd-help-tab');
    }
}

function formatLexromPage(page)
{
    if (page < 10) {
        page = '00' + page;
    } else if (page < 100) {
        page = '0' + page;
    }

    return page;
}

function getcookie(name)
{
    return getrawcookie(name, true);
}

function getrawcookie(name, toUnescape) {
    var
    end,
    start,
    value = '';

    if (document.cookie.length > 0) {
        start = document.cookie.indexOf(name + "=");

        if (start != -1) {
            start = start + name.length + 1;
            end = document.cookie.indexOf(";", start);
            end == -1 && (end = document.cookie.length);
            value = document.cookie.substring(start, end);
            toUnescape && (value = value.replace('%3D', '=').replace('%3B', ';'));
            value[0] == '{' && (value = json_decode(value));
            value == 'undefined'  && (value = '');
        }
    }

    return value;
}

function getGhostwordNotice(update)
{
    var ghostword = document.getElementById('ISIS_SELECT');

    if (update || !ghostword.value) {
        ghostword.value = document.getElementById('ghostword-select').value;
        document.getElementById('ghostword-form').submit();
        setDictionaryHeight('ghostword-notice');
    }
}
function getWindowHeight()
{
    var height;

    if (window.innerHeight) {
        height = window.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) {
        height = document.documentElement.clientHeight;
    } else if (document.body) {
        height = document.body.clientHeight;
    }

    return height;
}

function goEntry(action)
{
    var 
    entry = document.getElementById('entries'),
    pageVolume = entry.value.split('/'),    
    page = parseInt(pageVolume[0]);
    volume = parseInt(pageVolume[1]);
    entryHash = pageVolume[2];
    
    action = action.replace('%s', page);
    action = action.replace('%s', volume);
    action = action.replace('%s', entryHash);
    
    location.assign(action);
}

function goPage(action)
{
    var
    page = document.getElementById('page'),
    volume = document.getElementById('volume');

    page && (action = action.replace('%s', parseInt(page.value)));
    volume && (action = action.replace('%s', parseInt(volume.value)));

    location.assign(action);
}

function isEnterKey(keyEvent)
{
    var keynum;

    if (window.event) { // IE
        keynum = keyEvent.keyCode;
    } else if (keyEvent.which) { // Netscape/Firefox/Opera
        keynum = keyEvent.which;
    }

    return (keynum === 13);
}


/*
 * Decodes the JSON representation into a PHP value - phpjs.org version: 1004.2314
 * adaptation: returns null instead of throwing error
 */
function json_decode(str_json) {
    var json = this.window.JSON;
    if (typeof json === 'object' && typeof json.parse === 'function') {
        return json.parse(str_json);
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
    var j;
    var text = str_json;

    cx.lastIndex = 0;
    if (cx.test(text)) {
        text = text.replace(cx, function (a) {
            return '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
        });
    }

    if ((/^[\],:{}\s]*$/).test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

        j = eval('(' + text + ')');

        return j;
    }

    // throw new SyntaxError('json_decode');
    return ''; // fix
}

function onBlur()
{
	window.onfocus = onFocus;
}

function onFocus()
{
    getcookie('new-tab') && autoSearchLastWord();
	
	window.onfocus = null;
	window.onblur = onBlur;
}

function onLoad()
{
	window.onblur = onBlur;
	prevDictionary = document.getElementById('dictionary').selectedIndex;
}

function openDictionary(action)
{
    var 
	isNewTab = getcookie('new-tab'),
	dictionary = document.getElementById('dictionary');
	
	action = action.replace('%s', dictionary.value);
    searchWord(action, null, isNewTab);
	
	isNewTab && (dictionary.selectedIndex = prevDictionary);
}

function printPage()
{
    var
    child = document.getElementById('middle').firstChild,
    grandChild,
    innerHTML,
    url = '/print.php?content=';
    
    domainSubpath && (url = '/' + domainSubpath + url);

    while(child){
        if (child.nodeType === 1 && child.nodeName.toLowerCase() == 'div' && child.style.display != 'none'){
            // this is the content, errata or ghostwords node
            grandChild = child.firstChild;
            while (grandChild){
                if (grandChild.nodeType === 1){
                    if (grandChild.nodeName.toLowerCase() == 'iframe'){
                        // this is an iframe, expecting only one; e.g. an external dictionary  page
                        window.open(grandChild.src, 'dfprint'); // note: IE does not accept name with "-" character
                        return;
                    } else if (grandChild.nodeName.toLowerCase() == 'img'){
                        // this a set of images, e.g. an internal dictionary page
                        // update the images path that is one level up from print.php
                        // base64_encode() is necessary because double quotes are causing problems
                        // encodeURIComponent() because the "+" character is causing problems
                        innerHTML = encodeURIComponent(base64_encode(child.innerHTML));
                        window.open(url + innerHTML, 'dfprint'); // must use letters for IE, no dash!
                        return;
                    }
                }
                grandChild = grandChild.nextSibling;
            }
        }
        child = child.nextSibling;
    }

    window.print();
}

function searchLastWord(action)
{
    var
	dictionaryLanguage = document.getElementById('dictionary-language').value,
	lastWord = getcookie('last-word-' + dictionaryLanguage),
	word = document.getElementById('word').value;
	
    (word != lastWord) && searchWord(action, lastWord);
}

function searchWord(action, word, isNewTab)
{
    word || (word = document.getElementById('word').value);
	action = action.replace('%s', word);

    if (isNewTab) {
        window.open(action);
	} else {
        location.assign(action);
	}
}

function setDictionaryHeight(id)
{
    var
    height = getWindowHeight(),
    bottomMargin = 50,
    middle;

    if (id ) {
        bottomMargin += 2;
    } else {
        id = 'middle';
    }

    middle = document.getElementById(id);
    // resizes the height if different from 0!
    // note that this might not be the case when loaded thru an iframe
    // that is not being displayed, ex. loaded by MultiDic
    height && (middle.style.height = (height - middle.offsetTop - bottomMargin) + 'px');
}

function updateTabContents(target)
{
    var
    sections = ['content', 'errata', 'ghostwords', 'lexromd', 'lexromd-help' ],
    tabs = ['content-tab', 'errata-tab', 'ghostwords-tab', 'lexromd-tab', 'lexromd-help-tab' ],
    sectionId,
    tabId,
    section,
    tab,
    i;

    for (i in sections) {
        sectionId = sections[i];
        tabId = tabs[i];

        section = document.getElementById(sectionId);
        section && (section.style.display = tabId == target ? 'inline': 'none');
        tab = document.getElementById(tabId);
        tab && (tab.className = tabId == target ? 'tab-selected': 'tab');
    }
}

function utf8_encode( string )
{
    // phpjs.org
    string = (string+'').replace(/\r\n/g, "\n").replace(/\r/g, "\n");

    var utftext = "";
    var start, end;
    var stringl = 0;

    start = end = 0;
    stringl = string.length;
    for (var n = 0; n < stringl; n++) {
        var c1 = string.charCodeAt(n);
        var enc = null;

        if (c1 < 128) {
            end++;
        } else if((c1 > 127) && (c1 < 2048)) {
            enc = String.fromCharCode((c1 >> 6) | 192) + String.fromCharCode((c1 & 63) | 128);
        } else {
            enc = String.fromCharCode((c1 >> 12) | 224) + String.fromCharCode(((c1 >> 6) & 63) | 128) + String.fromCharCode((c1 & 63) | 128);
        }
        if (enc != null) {
            if (end > start) {
                utftext += string.substring(start, end);
            }
            utftext += enc;
            start = end = n+1;
        }
    }

    if (end > start) {
        utftext += string.substring(start, string.length);
    }

    return utftext;
}

function validateLexromPage(tome, page)
{
    return page >= 1 &&
        (tome == 2 && page <= 536) ||
        (tome == 3 && page <= 611) ||
        (tome == 4 && page <= 676) ||
        (tome == 5 && page <= 612) ||
        (tome == 6 && page <= 40);
}

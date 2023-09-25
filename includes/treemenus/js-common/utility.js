// Copyright 2001-2004 Interakt Online. All rights reserved.

utility = {
	'math': {},
	'string': {}, 
	'js': {}, 
	'debug': {}, 
	'url': {},
	'dom': {},
	'window': {}
};

utility.math.dec2hex = function(x) {
	return Number(parseInt(x)).toString(16);
}

utility.math.hex2dec = function(x) {
	return parseInt(x, 16);
}

utility.math.zeroPad = function (str, length) {
	if (!str) str = "";
	str = str.toString();
	while (str.length < length) {
		str = "0" + str;
	}
	return str;
}

utility.js.build = function(fun1, fun2) {
	var me = function() {
		if (fun2) { fun2(); }
		if (fun1) { fun1(); }
	}
	return me;
}

utility.debug.dump = function (obj, sep) {
	if (sep == undefined) {
		sep = '';
	}
	tm = "";
	if (typeof (obj) == "object") {
		for (i in obj) {
			tm += sep + i + ":{\n" + utility.debug.dump(obj[i], sep + '  ') + "}\n";
		}
		return tm;
	}
	if (typeof (obj) == "function") return sep + typeof (obj) + "\n";
	return sep + obj + "\n";
}

utility.string.htmlspecialchars = function(str) {
	[	['>', '&gt;'],
		['<', '&lt;'],
		['\xA0', '&nbsp;'],
		['"', '&quot;']
	].each(function(repl, idx) {
		str = str.replace(new RegExp('['+repl[0]+']', "g"), repl[1]);
	});
	return str;
}

utility.string.getInnerText = function(str) {
	if (typeof getInnerText_tmpDiv == 'undefined') {
		getInnerText_tmpDiv = document.createElement('div');
	}
	var oldstr = str;
	try {
		getInnerText_tmpDiv.innerHTML = str;
		str = getInnerText_tmpDiv.innerText;
	} catch(e) { return oldstr; } 
	if ( typeof str == 'undefined') {
		return oldstr;
	}
	return str;
}

utility.string.sprintf = function() {
	if (!arguments || arguments.length < 1 || !RegExp) {
		return;
	}

	var str = arguments[0];
	var oldstr = arguments[0];
	var re = /([^%]*)%('.|0|\x20)?(-)?(\d+)?(\.\d+)?(%|b|c|d|u|f|o|s|x|X)(.*)/;
	var a = b = [], numSubstitutions = 0, numMatches = 0;
	while (a = re.exec(str)) {
		var leftpart = a[1], pPad = a[2], pJustify = a[3], pMinLength = a[4];
		var pPrecision = a[5], pType = a[6], rightPart = a[7];
		numMatches++;

		if (pType == '%') {
			subst = '%';
		} else {
			numSubstitutions++;
			if (numSubstitutions >= arguments.length) {
				return oldstr;
			}
			var param = arguments[numSubstitutions];
			var subst = param;

			if (pType == 'c') subst = String.fromCharCode(parseInt(param));
			else if (pType == 'd') subst = parseInt(param) ? parseInt(param) : 0;
			else if (pType == 's') subst = param;
		}
		str = leftpart + subst + rightPart;
	}
	return str;
}


utility.dom.put = function(el, left, top) {
	el.style.left = left + 'px';
	el.style.top = top + 'px';
}

utility.dom.resize = function(el, width, height) {
	el.style.width = width + 'px';
	el.style.height = height + 'px';
}

utility.dom.focusElem =function(elem) {
	var d;
	d = this.getElem(elem);
	if (!d) return;
	if (d.focus) d.focus();
}

utility.dom.hideElem = function(elem) {
	this.setCssProperty(elem, "display", "none");
}

utility.dom.showElem = function(elem, force) {
	var tag_display = {
		table: 'table',
		tr: 'table-row',
		td: 'table-cell'
	}
	elem = utility.dom.getElem(elem);
	var tn = elem.tagName.toLowerCase();
	var t;
	if (!force) {
		if (typeof tag_display[tn] != 'undefined') {
			t = tag_display[tn];
		} else {
			t = "block";
		}
	} else {
		t = 'force';
	}
	try {
		this.setCssProperty(elem, "display", t);
	} catch(e) {
		// default to block if first try doesn't work
		// this happens on explorer when trying to set table-row and friends
		this.setCssProperty(elem, "display", "block");
	}
}

// because we can't find out on the first call the real state, we assume the element is hidden
utility.dom.toggleElem = function(elem, force) {
	elem = utility.dom.getElem(elem);
	try {
		if (!elem.style.display || elem.style.display == 'none') {
			utility.dom.showElem(elem, force);
		} else {
			utility.dom.hideElem(elem);
		}
	} catch(e) { }
}

// select the option that has the given value
utility.dom.selectOption = function(sel, val) {
	var i;
	if (!sel) return;
	for (i=0; i<sel.options.length; i++) {
		sel.options[i].removeAttribute('selected');
	}
	for (i=0; i<sel.options.length; i++) {
		if (sel.options[i].value == val) {
			sel.options[i].setAttribute('selected','selected');
			sel.options[i].selected = true;
			return;
		} else {
			sel.options[i].removeAttribute('selected');
		}
	}
}

// get value of the selected option
utility.dom.getSelected = function(sel) {
	return sel.options[sel.selectedIndex].value;
}

utility.dom.setCssProperty = function(elem, name, value) {
	var d;
	// sanity
	if (!elem || !name || !value) return;
	d = this.getElem(elem);
	if (!d) return;
	d.style[name] = value;
}

utility.dom.getElem = function(elem) {
	var d;
	if (typeof(elem) == "string") {
		d = document.getElementById(elem);
	} else {
		d = elem;
	}
	return d;
}

// return 
utility.dom.getClassNames = function(o) {
	o = utility.dom.getElem(o);
	if (!o) return false;
	var cn = o.className;
	var cns = [];
	cn.split("\n").each(function(cnpart, i){
		cnpart.normalize_space().split(" ").each(function(item){
			if (item != '') cns.push(item);
		});
	});
	return cns;
}

utility.dom.classNameAdd = function(obj, toadd) {
	var cls = utility.dom.getClassNames(obj);
	if (typeof toadd == 'string') {
		toadd = toadd.split(',');
	}
	toadd.each(function(item, i){
		if (cls.indexOf(item) == -1) {
			cls.push(item);
		}
	});
	cls = cls.join(' ').trim();
	if (obj.className.trim() != cls) {
		obj.className = cls;
	}
}

utility.dom.classNameRemove = function(obj, toremove) {
	var cls = utility.dom.getClassNames(obj);
	var result = [];
	/* can't use ruby.js and all those nice things yet 
	 * since ie5 doesn't have .hasOwnProperty
	cls = cls.reject(function(item, i) {
		return (item == str);
	});
	*/
	if (typeof toremove == 'string') {
		toremove = toremove.split(',');
	}
	cls.each(function(item, i){
		if (toremove.indexOf(item) == -1) {
			result.push(item);
		}
	});
	
	cls = result.join(' ').trim();
	if (obj.className.trim() != cls) {
		obj.className = cls;
	}
}

// sTagName is optional, defaults to *
utility.dom.insertAfter = function(newElement, targetElement) {
	var sibling = targetElement.nextSibling
	var parent = targetElement.parentNode;
	if (sibling == null) {
		var toret = parent.appendChild(newElement);
	} else {
		var toret = parent.insertBefore(newElement, sibling);
	}
	return toret;
}

utility.dom.getParentByTagName = function(t, parentName) {
	if (t.nodeName.toLowerCase() == parentName.toLowerCase()) {
		return t;
	}

	while (t.parentNode
			&& t.parentNode.nodeName.toLowerCase() != parentName.toLowerCase()
			&& t.parentNode.nodeName != 'BODY') {
		t = t.parentNode;
	}

	if (t.parentNode && t.parentNode.nodeName.toLowerCase() == parentName.toLowerCase()) {
		return t.parentNode;
	} else {
		return null;
	}
}

// should refactor this to take the tagname as a list or array of tagnames
// TODO : parameter order
utility.dom.getElementsByTagName = function(o, sTagName) {
	var el;
	if (typeof(o) == 'undefined') {
		o = document;
	} else {
		o = utility.dom.getElem(o);
	}

	if (sTagName == '*' || typeof sTagName == 'undefined') {
		if (o.all) {
			el = o.all;
		} else {
			el = o.getElementsByTagName('*');
		}
	} else {
		el = o.getElementsByTagName(sTagName);
	}
	el.each = Array.prototype.each;
	return el;
}

// actually, this should be a front for a more generic method that gets elements filtered by an attribute
// or, we should try to use more of ruby.js to make this things easier to do (include Enumerable)
utility.dom.getElementsByClassName = function(o, sClassName, sTagName) {
	var elements = [];
	utility.dom.getElementsByTagName(o, sTagName).each(function(elem, i) {
		if (utility.dom.getClassNames(elem).indexOf(sClassName) != -1) { 
			elements.push(elem);
		}
	});
	return elements;
}

utility.dom.getElementsByProps = function(start, props_hash) {
	var unfiltered, toret = [];
	if (typeof(start) == 'undefined') {
		start = document;
	} else {
		start = utility.dom.getElem(o);
	}
	if (o.all) {
		unfiltered = o.all;
	} else {
		unfiltered = o.getElementsByTagName('*');
	}
	unfiltered.each = Array.prototype.each;
	unfiltered.each(function(item) {
		var cond = true;
		for (i in props_hash) {
			try {
				var value = item[i];
			} catch(e) { value = null; }
			cond = cond && (value == props_hash[i]);
		}
		if (cond) {
			toret.push(item);
		}
	});
	return toret;
}

// get all children of elem that have the "tag" tagName
utility.dom.getChildrenByTagName = function(elem, tag) {
	var result = [];
	var x;
	if (typeof(tag) == 'undefined') tag = '*';
	tag = tag.toLowerCase();
	if (!elem.childNodes) return result;
	for (var i=0; i<elem.childNodes.length; i++) {
		x = elem.childNodes[i];
		try {
			if (
				(typeof(x) != 'undefined' && 
				typeof(x.tagName) != 'undefined' && 
				x.tagName.toLowerCase() == tag) || tag == '*'
				
			) {
				result.push(x);
			}
		} catch(e) { 
			// nick the error 
		}
	}
	return result;
}

// get all children of elem that have the class "sClassName"
// sTagName is optional, defaults to *
utility.dom.getChildrenByClassName = function(elem, sClassName, sTagName) {
	var result = [];
	result = utility.dom.getChildrenByTagName(sTagName).each(function(elem, i) {
		if (utility.dom.getClassNames(item).indexOf(sClassName) != -1) { 
			result.push(elem);
		}
	});
}

utility.dom.stripAttributes = function(el) {
	var cearElementProps = [
		'onload', 'data', 'onmouseover', 'onmouseout', 'onmousedown', 
		'onmouseup', 'ondblclick', 'onclick', 'onselectstart', 
		'oncontextmenu',      'onkeydown',   'onkeypress', 'onkeyup',
		'onblur', 'onfocus', 'onbeforedeactivate', 'onchange'];
	for (var c = cearElementProps.length; c--; ) {
		el[cearElementProps[c]] = null;
	}
}
// use attachEvent for ie
utility.dom.attachEvent2 = function(where, type, what, when) {
	utility.dom.attachEvent_base(where, type, what, when, 1);
}
// use on. for ie
utility.dom.attachEvent = function(where, type, what, when) {
	utility.dom.attachEvent_base(where, type, what, when, 0);
}

// don't use attachEvent for ie since we can't get 
// to the element where the handler is added from the handler
utility.dom.attachEvent_base = function(where, type, what, when, useAdvancedModel) {
	function treatType(type) {
		//mozilla events are without 'on'
		if (typeof document.addEventListener != 'undefined') {
			type = type.replace(/^on/, '');
		} else if (typeof document.attachEvent != 'undefined' && !type.match(/^on/)) {
			type = 'on' + type;
		}

		return type;
	}
	
	if (typeof(when) == 'undefined') when = 1;
	if (typeof(useAdvancedModel) == 'undefined') useAdvancedModel = 0;

	type = treatType(type);

	var oldHandler = where['on' + type.replace(/^on/, '')] || function() { };
	if (when == 0) {
		var built_function = function(e) {
			if (typeof e == 'undefined') { e = window.event; }
			what.call(where, e); 
			oldHandler.call(where, e); 
		}
	} else {
		var built_function = function(e) {
			if (typeof e == 'undefined') { e = window.event; }
			oldHandler.call(where, e); 
			what.call(where, e); 
		}
	}
	
	if (useAdvancedModel) {
		//we don't give a shit : that you need "this" in the handler, that you already have other handlers etcetera. 
		if (typeof where.addEventListener != 'undefined') {   //Mozilla
			where.addEventListener(type, what, false);
		} else if (typeof where.attachEvent != 'undefined') { //IE
			where.attachEvent(type, what);
		} else {
			where['on' + type.replace(/^on/, '')] = what;
		}
	} else {
		where['on' + type.replace(/^on/, '')] = built_function;
	}
	// add the event
}

utility.dom.getStyleProperty = function(el, property) {
	var value = el.style[property];

	if (!value) {
		if (document.defaultView && 
			typeof (document.defaultView.getComputedStyle) == "function") { 
			// moz, opera
			value = document.defaultView.getComputedStyle(el, "").getPropertyValue(property);
		} else if (el.currentStyle) {
			// IE
			value = el.currentStyle[property];
		} else if (el.style) {
			value = el.style[property];
		}
	}

	return value;
}

utility.dom.getDisplay = function(el) {
	return utility.dom.getStyleProperty(el, 'display');
}

utility.dom.getVisibility = function(el) {
	return utility.dom.getStyleProperty(el, 'visibility');
}

utility.dom.getAbsolutePos = function(el) {
	var scrollleft = 0, scrolltop = 0, tn = el.tagName.toUpperCase();

	if (['BODY', 'HTML'].indexOf(tn) == -1) { // ?
		if (el.scrollLeft) {
			scrollleft = el.scrollLeft;
		}

		if (el.scrollTop) {
			scrolltop = el.scrollTop;
		}
	}

	var r = { x: el.offsetLeft - scrollleft, y: el.offsetTop - scrolltop };

	if (el.offsetParent && tn != 'BODY') {
		var tmp = utility.dom.getAbsolutePos(el.offsetParent);
		r.x += tmp.x;
		r.y += tmp.y;
	}

	return r;
}

utility.dom.setEventVars = function(e) {
	var targ, relTarg, posx = 0, posy = 0;

	if (!e) var e = window.event;

	if (e.relatedTarget) relTarg = e.relatedTarget;
	else if (e.fromElement) relTarg = e.fromElement;

	if (e.target) targ = e.target;
	else if (e.srcElement) targ = e.srcElement;

	//position
	if (e.pageX || e.pageY) {
		posx = e.pageX;
		posy = e.pageY;
	} else if (e.clientX || e.clientY) {
		posx = e.clientX + 
			(document.documentElement ? 
				document.documentElement.scrollLeft 
				: document.body.scrollLeft);
		posy = e.clientY + 
			(document.documentElement ? 
				document.documentElement.scrollTop 
				: document.body.scrollTop);
	}

	//mouse button
	if (window.event) {
		var leftclick = (e.button == 1);
		var middleclick = (e.button == 4);
		var rightclick = (e.button == 2);
	} else {
		var leftclick = (e.button == 0);
		var middleclick = (e.button == 1);
		var rightclick = (e.button == 2);
	}

	o = { 'e': e,
		'relTarg': relTarg,
		'targ': targ,
		'posx': posx, 'posy': posy,
		'leftclick': leftclick, 
		'middleclick': middleclick, 
		'rightclick': rightclick, 
		'type': e.type };

	return o;
}

utility.dom.stopEvent = function(e) {
	try {
	e.cancelBubble = true;
	e.returnValue = false;
	} catch(e) {}
	if (e.preventDefault) e.preventDefault();
	if (e.stopPropagation) e.stopPropagation();
	return false;
}

utility.dom.getBox = function(el) {
	var box = { 
		"x": 0, "y": 0, 
		"width": 0, "height": 0, 
		"scrollTop": 0, "scrollLeft": 0 
	};

	if (el.ownerDocument.getBoxObjectFor) {
		var rect = el.ownerDocument.getBoxObjectFor(el);
		box.x = rect.x - el.parentNode.scrollLeft;
		box.y = rect.y - el.parentNode.scrollTop;
		box.width = rect.width;
		box.height = rect.height;
		box.scrollLeft = el.ownerDocument.body.scrollLeft;
		box.scrollTop = el.ownerDocument.body.scrollTop;
	} else {
		var rect = el.getBoundingClientRect();
		box.x = rect.left;
		box.y = rect.top;
		box.width = rect.right - rect.left;
		box.height = rect.bottom - rect.top;
		box.scrollLeft = 0; //el.document.body.scrollLeft;
		box.scrollTop = 0;  //el.document.body.scrollTop;
	}
	return box;
}

// from quirksmode, fixed to properly differentiate between IE versions
utility.dom.getPageInnerSize = function() {
	var x, y;
	if (typeof self.innerHeight != "undefined") {
		x = self.innerWidth;
		y = self.innerHeight;
	} else if (typeof document.compatMode != 'undefined' && document.compatMode == 'CSS1Compat') {
		x = document.documentElement.clientWidth;
		y = document.documentElement.clientHeight;
	} else if (document.body) {
		x = document.body.clientWidth;
		y = document.body.clientHeight;
	}
	return {x: x, y: y};
}
// from quirksmode, fixed to properly differentiate between IE versions
utility.dom.getPageScroll = function() {
	var x, y;
	if (typeof self.pageYOffset != 'undefined') {
		x = self.pageXOffset;
		y = self.pageYOffset;
	} else if (typeof document.compatMode != 'undefined' && document.compatMode == 'CSS1Compat') {
		x = document.documentElement.scrollLeft;
		y = document.documentElement.scrollTop;
	}
	else if (document.body) {
		x = document.body.scrollLeft;
		y = document.body.scrollTop;
	}
	return {x: x, y: y};
}

utility.dom.createElement = function(type, attribs) {
	var elem = document.createElement( type );
	if ( typeof attribs != 'undefined' ) {
		for (var i in attribs) {
			switch ( true ) {
				case ( i == 'text' )  : 
					elem.appendChild( document.createTextNode( attribs[i] ) ); 
					break;
				case ( i == 'class' ) : 
					elem.className = attribs[i]; 
					break;
				case ( i == 'id' ) : 
					elem.id = attribs[i]; 
					break;
				case ( i == 'style' ) : 
					elem.style.cssText = attribs[i]; 
					break;
				default : 
					elem.setAttribute( i, '' );
					elem[i] = attribs[i];
			}
		}
	}
	return elem;	
};

utility.window.openWindow = function(u, n, w, h, x) {
	var top, left, top, features;
	left = (screen.width - w) / 2;
	top = (screen.height - h) / 2;
	features = ",left=" + left + ",top=" + top;
	winargs = "width=" + w + ",height=" + h + ",resizable=Yes,scrollbars=No,status=Yes,";
	dialogargs = "scrollbars=yes,center=yes;dialogHeight=" + h + "px;dialogWidth=" + w
								 + "px;help=no;status=no;resizable=yes; ";

	n += (n.indexOf("?") != -1) ? '&' : '?';
	n += "rand=" + Math.random().toString().substring(3);

	if (window.showModalDialog && !x) {
		remote = window.showModelessDialog(n, window, dialogargs + features);
		if (n.match(/dirbrowser/)) {
			window._dlg_ = remote;
		}
	} else {
		var newLocation = (window.location + '').replace(/\/[^\/]*$/, '');
		if (n.indexOf('./') == 0) {
			newLocation += n.replace(/^\./, '');
		} else if (n.indexOf('http://') == 0 || n.indexOf('https://') == 0) {
			newLocation = n;
		} else {
			if (!usedByJahia) {
				newLocation += '/' + n;
			} else {
				// jahia only
				var ix = newLocation.indexOf('//');
				ix = newLocation.indexOf('/', ix + 2);
				newLocation = newLocation.substr(0, ix) + n;
			}
		}
		remote = window.open(newLocation, u, winargs + features);
		if (remote) 
			remote.focus();
	}

	if (remote != null) {
		if (remote.opener == null) remote.opener = self;
	}

	return remote;
}

// simple UID generator
UIDGenerator = function(name) {
	if (typeof(name) == 'undefined') {
		name = 'iaktuid_' + Math.random().toString().substring(2, 6) + '_';
	}
	this.name = name;
	this.counter = 1;
}
UIDGenerator.prototype.generate = function() {
	return (this.name + this.counter++ + '_');
}

QueryString = function() {
	this.keys = new Array();
	this.values = new Array();
	var query = window.location.search.substring(1);
	var pairs = query.split("&");

	for (var i = 0; i < pairs.length; i++) {
		var pos = pairs[i].indexOf('=');

		if (pos >= 0) {
			var argname = pairs[i].substring(0, pos);
			var value = pairs[i].substring(pos + 1);
			this.keys[this.keys.length] = argname;
			this.values[this.values.length] = value;
		}
	}
}

QueryString.prototype.find = function(key) {
	var value = null;
	for (var i = 0; i < this.keys.length; i++) {
		if (this.keys[i] == key) {
			value = this.values[i];
			break;
		}
	}
	return value;
}

/*
 * class XmlHttp
*/
//MsXML on Mozilla
function getDomDocumentPrefix() {
	if (getDomDocumentPrefix.prefix) return getDomDocumentPrefix.prefix;
	var prefixes = ["MSXML2", "Microsoft", "MSXML", "MSXML3"];
	var o;

	for (var i = 0; i < prefixes.length; i++) {
		try {
			// try to create the objects
			o = new ActiveXObject(prefixes[i] + ".DomDocument");
			return getDomDocumentPrefix.prefix = prefixes[i];
		} catch (ex) { }

		;
	}

	throw new Error("Could not find an installed XML parser");
}
function getXmlHttpPrefix() {
	if (getXmlHttpPrefix.prefix) return getXmlHttpPrefix.prefix;

	var prefixes = ["MSXML2", "Microsoft", "MSXML", "MSXML3"];
	var o;

	for (var i = 0; i < prefixes.length; i++) {
		try {
			// try to create the objects
			o = new ActiveXObject(prefixes[i] + ".XmlHttp");
			return getXmlHttpPrefix.prefix = prefixes[i];
		} catch (ex) { }

		;
	}

	throw new Error("Could not find an installed XML parser");
}

// XmlHttp factory
function XmlHttp() { }
XmlHttp.create = function() {
	try {
		if (window.XMLHttpRequest) {
			var req = new XMLHttpRequest();
			//	some versions of Moz do not support the readyState property
			//	and the onreadystate event so we patch it!
			if (req.readyState == null) {
				req.readyState = 1;
				req.addEventListener("load", 
					function() {
						req.readyState = 4;
						if (typeof req.onreadystatechange == "function") 
							req.onreadystatechange();
					}, false);
			}

			return req;
		}

		if (window.ActiveXObject) {
			var ax = new ActiveXObject(getXmlHttpPrefix() + ".XmlHttp");
			return ax;
		}
	} catch (ex) { }

	// fell through
	throw new Error("Your browser does not support XmlHttp objects");
}


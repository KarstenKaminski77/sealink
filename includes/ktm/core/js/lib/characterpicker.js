// Copyright 2001-2005 Interakt Online. All rights reserved.

var CharacterPickerCharacters = [
'euro', 'pound', 'cent', 'yen', 'copy', 'reg', 'curren', 'iexcl', 'brvbar', 'sect', 'uml', 'ordf', 'laquo', 'not', 'macr', 'deg', 'plusmn', 'sup2', 'sup3', 'acute', 'micro', 'para', 'middot', 'cedil', 'sup1', 'ordm', 'raquo', 'frac14', 'frac12', 'frac34', 'iquest', 'Agrave', 'Aacute', 'Acirc', 'Atilde', 'Auml', 'Aring', 'AElig', 'Ccedil', 'Egrave', 'Eacute', 'Ecirc', 'Euml', 'Igrave', 'Iacute', 'Icirc', 'Iuml', 'ETH', 'Ntilde', 'Ograve', 'Oacute', 'Ocirc', 'Otilde', 'Ouml', 'times', 'Oslash', 'Ugrave', 'Uacute', 'Ucirc', 'Uuml', 'Yacute', 'THORN', 'szlig', 'agrave', 'aacute', 'acirc', 'atilde', 'auml', 'aring', 'aelig', 'ccedil', 'egrave', 'eacute', 'ecirc', 'euml', 'igrave', 'iacute', 'icirc', 'iuml', 'eth', 'ntilde', 'ograve', 'oacute', 'ocirc', 'otilde', 'ouml', 'divide', 'oslash', 'ugrave', 'uacute', 'ucirc', 'uuml', 'yacute', 'thorn', 'yuml', 
	// Symbols and Greek Letters 
'fnof', 'Alpha', 'Beta', 'Gamma', 'Delta', 'Epsilon', 'Zeta', 'Eta', 'Theta', 'Iota', 'Kappa', 'Lambda', 'Mu', 'Nu', 'Xi', 'Omicron', 'Pi', 'Rho', 'Sigma', 'Tau', 'Upsilon', 'Phi', 'Chi', 'Psi', 'Omega', 'alpha', 'beta', 'gamma', 'delta', 'epsilon', 'zeta', 'eta', 'theta', 'iota', 'kappa', 'lambda', 'mu', 'nu', 'xi', 'omicron', 'pi', 'rho', 'sigmaf', 'sigma', 'tau', 'upsilon', 'phi', 'chi', 'psi', 'omega', 'thetasym', 'upsih', 'piv', 'bull', 'hellip', 'prime', 'Prime', 'oline', 'frasl', 'weierp', 'image', 'real', 'trade', 'alefsym', 'larr', 'uarr', 'rarr', 'darr', 'harr', 'crarr', 'lArr', 'uArr', 'rArr', 'dArr', 'hArr', 'forall', 'part', 'exist', 'empty', 'nabla', 'isin', 'notin', 'ni', 'prod', 'sum', 'minus', 'lowast', 'radic', 'prop', 'infin', 'ang', 'and', 'or', 'cap', 'cup', 'int', 'there4', 'sim', 'cong', 'asymp', 'ne', 'equiv', 'le', 'ge', 'sub', 'sup', 'nsub', 'sube', 'supe', 'oplus', 'otimes', 'perp', 'sdot', 'lceil', 'rceil', 'lfloor', 'rfloor', 'lang', 'rang', 'loz', 'spades', 'clubs', 'hearts', 'diams',
	// Other Special Characters 
'quot', 'amp', 'lt', 'gt', 
'OElig', 'oelig', 'Scaron', 'scaron', 'Yuml', 'circ', 'tilde', 'ensp', 'emsp', 'thinsp', 'zwnj', 'zwj', 'ndash', 'mdash', 'lsquo', 'rsquo', 'sbquo', 'ldquo', 'rdquo', 'bdquo', 'dagger', 'Dagger', 'permil', 'lsaquo', 'rsaquo' ];
/**
	class CharacterPalette
*/
var CharacterPaletteDivHead =
'<table border="0" cellpadding="0" cellspacing="0" '+
'	class="ktml_bg" '+
'	style="border: solid 1px #000000; width: 100%; cursor: default !important; -moz-user-select: none;"'+
'	id="ktml_@@ktml@@_mousecapture"'+
'	onmouseover="window[\'ktml_@@ktml@@\'].charpalette.mOver(event)"'+
'	onmouseout="window[\'ktml_@@ktml@@\'].charpalette.mOut(event)"'+
'	onmouseup="window[\'ktml_@@ktml@@\'].charpalette.mClick(event)"'+
'	ondblclick="window[\'ktml_@@ktml@@\'].charpalette.mdblClick(event)"'+
'   onselectstart="return false" '+
'>'+
'  <tr>'+
'    <td valign="top" style="padding: 4px !important">'+
'	   <fieldset class="ktml_fieldset" style="height: 96%; cursor: default !important">'+
'		<legend class="ktml_legend">||Character Picker||</legend>'+
'			<div id="character_picker_characters_@@ktml@@" class="character_picker_character_table">';
var CharacterPaletteDivFooter =
'			</div>'+
'	   </fieldset>'+
'	</td>'+
'	<td style="vertical-align: top !important">'+
'		<div>'+
'			<div style="text-align: center; font-size: 12px; line-height: 14px; width: 60px; margin-top: 10px">||Selected||</div> '+
'			<div style="border: solid 1px #000000; text-align: center; font-size: 12px; line-height: 14px; padding-top: 2px; padding-bottom: 2px; width: 58px; margin-top: 10px" id="ktml_@@ktml@@_textfld">&#160;</div>'+
'			<input style="text-align: center; font-size: 12px; line-height: 14px; width: 60px; margin-top: 10px" type="button" value="||Insert||" onmouseup="window[\'ktml_@@ktml@@\'].charpalette.pickColor(window[\'ktml_@@ktml@@\'].charpalette.textfld.innerHTML)"/>'+
'			<input style="text-align: center; font-size: 12px; line-height: 14px; width: 60px; margin-top: 10px" type="button" value="||Cancel||" onmouseup="window[\'ktml_@@ktml@@\'].charpalette.hide()"/>'+
'			<input style="text-align: center; font-size: 12px; line-height: 14px; width: 60px; margin-top: 10px" type="button" value="||Help||" onmouseup="window[\'ktml_@@ktml@@\'].charpalette.showHelp()"/>'+
'		</div>'+
'	</td>'+
'  </tr>'+
'</table>';

function CharacterPalette(owner) {
	this.owner = owner;
	this.div = document.getElementById('ktml_'+this.owner.name+'_cpdiv');
	this.div.style.zIndex = 1000;
	this.height = 320;
	this.width = 306;
	this.original = "&copy;";
	this.preview = 'ktml_'+this.owner.name+'_character_preview';
	this.isVisible = false;
	this.isDrawn = false;

	this.userCharacters = util_defaultValue($KTML4_GLOBALS['special_chars'], '');
	if (this.userCharacters) {
		this.userCharacters = this.userCharacters.split(",");
	}
}

CharacterPalette.prototype.writeCharacterElement =function(charEntity) {
	return '<div character="'+charEntity.replace(/^&/, '').replace(/;$/, '')+'" class="' + (charEntity?'character':'empty') + '">' + charEntity + '</div>';
};

CharacterPalette.prototype.initializeUI = function() {
	if (this.isDrawn) {
		return;
	}
	this.isDrawn = true;
	this.drawPicker();
	this.textfld = document.getElementById('ktml_'+this.owner.name + '_textfld');
};

CharacterPalette.prototype.drawTable = function() {
	var text = '';
	if(this.userCharacters && this.userCharacters.length) {
		for(var i=0;i<this.userCharacters.length;i++) {
			text += this.writeCharacterElement(this.userCharacters[i]);
		}
	}	else {
		for (var i=0; i<CharacterPickerCharacters.length; i++) {
			text += this.writeCharacterElement("&" + CharacterPickerCharacters[i] + ";");
		}
	}
	return text;
};

CharacterPalette.prototype.drawPicker = function() {
	var text = '';
	var header = lang_translatepage(CharacterPaletteDivHead, this.owner.config.UILanguage, window);
	header = header.replace(/@@ktml@@/g, this.owner.name);

	var footer = CharacterPaletteDivFooter.replace(/@@ktml@@/g, this.owner.name);
	footer = lang_translatepage(footer, this.owner.config.UILanguage, window);

	text += header;
	text += this.drawTable();
	text += footer;

	this.div.innerHTML = text;
	this.characters_div = document.getElementById("character_picker_characters_" + this.owner.name);
	this.recalculateDimensions();

	if (is.mozilla && is.mac) {
		this.div.style.overflow = "auto";
	}
};

CharacterPalette.prototype.recalculateDimensions = function() {
	if (!this.isDrawn) {
		this.initializeUI();
	}
	this.div.style.left = "-2000px";
	this.div.style.display = 'block';
	this.characters_div.style.height = (Math.max(this.characters_div.scrollHeight, 132)) + 'px';
	this.height = this.div.offsetHeight;
	this.width = this.div.offsetWidth;
	this.div.style.display = 'none';
	this.div.style.left = "";
};


CharacterPalette.prototype.mOver = function(e) {
	var o = is.ie ? e.srcElement : e.target;
	var current = o.getAttribute("character");
	if(o.tagName == "DIV" && current){
		utility.dom.classNameAdd(o, "hover");
	}
};

CharacterPalette.prototype.mOut = function(e){
	var o = is.ie ? e.srcElement : e.target;
	var current = o.getAttribute("character");
	if(o.tagName == "DIV" && current){
		utility.dom.classNameRemove(o, "hover");
	}
};

CharacterPalette.prototype.mClick = function(e) {
	utility.dom.stopEvent(is.ie ? event : e);
	var o = is.ie ? e.srcElement : e.target;
	var current = o.getAttribute("character");
	if (o.tagName == "DIV" && current) {
		this.textfld.innerHTML = "&" + current + ";";
	}
};

CharacterPalette.prototype.mdblClick = function(e) {
	var o = is.ie ? e.srcElement : e.target;
	var current = o.getAttribute("character");
	if (!this.div.contains(o)) {
		this.owner.util_restoreSelection();
		this.setVisible(false);
		return;
	}
	if(o.tagName == "DIV" && current){
		this.pickColor("&" + current + ";");
	}
};

CharacterPalette.prototype.setAction = function(str) {
	this.actionStr = str;
};

CharacterPalette.prototype.setSelected = function(str) {
	if (!str) {
		str = "&copy;";
	}
	this.original = str;
};

CharacterPalette.prototype.pickColor = function(selectedColor) {
	this.setVisible(false);
	this.onSelect(selectedColor);
	utility.popup.force = true;
	utility.popup.removeModal();
	utility.popup.force = false;
};


CharacterPalette.prototype.keyPress = CharacterPalette.prototype.keyUp = function(o, e) {
	try { this.selcolor.innerHTML = this.textfld.value; }
	catch(e) { this.selcolor.innerHTML = ""; }
};

CharacterPalette.prototype.keyDown = function(o, e) {
	if (e.keyCode == 13) { // ENTER
		this.pickColor(this.textfld.value);
		return utility.dom.stopEvent(e);
	}
	return true;
};

CharacterPalette.prototype.hide = function() {
	this.setVisible(false);
	this.owner.util_restoreSelection();
};

CharacterPalette.prototype.setVisible = function(par) {
	if (this.isVisible == par) {
		return;
	}
	if (par) {
		if (!this.original) {
			this.original = "&copy;";
		}
		this.textfld.innerHTML = this.original;
		this.div.style.display = 'block';
		var cp = this;
		this.div.style.zIndex = 20;
		utility.popup.makeModal(function() {
			cp.setVisible(false);
		}, this.div);
	} else {
		this.original = "&copy;";
		this.div.style.display= 'none';
	}
	this.isVisible = par;
};

CharacterPalette.prototype.showAt = function(x, y) {
	if (!this.isDrawn) {
		this.initializeUI();
	}
	var d = utility.dom.getPositionRelativeTo00(x, y, this.width, this.height);
	utility.dom.put(this.div, d.x, d.y);
};

CharacterPalette.prototype.showHelp = function() {
	this.owner.toolbar.showHelp("character_picker");
}


CharacterPalette.prototype.dispose = function() {
	this.div.innerHTML = '';
	this.div = null;
};

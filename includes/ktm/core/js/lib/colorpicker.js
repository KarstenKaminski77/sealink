// Copyright 2001-2005 Interakt Online. All rights reserved.
/**
	class ColorPalette
*/
var ColorPaletteDivHead =
'<table border="0" width="100%" style="border: solid 1px #000000" class="ktml_bg"'+
'				id="ktml_@@ktml@@_mousecapture" onmouseover="window[\'ktml_@@ktml@@\'].cpalette.mOver(event)"'+
'				onclick="window[\'ktml_@@ktml@@\'].cpalette.mClick(event)"'+
'				ondblclick="window[\'ktml_@@ktml@@\'].cpalette.mdblClick(event)"'+
'>'+
'  <tr class="ktml_bg">'+
'    <td valign="top"><fieldset class="ktml_fieldset" style="height: 98%;">'+
'<legend class="ktml_legend">||Color Picker||</legend>'+
'						<label style="vertical-align:middle">||Color Code||:</label>'+
'<div>'+
'						<input class="color_picker_current_color" style="vertical-align:middle" type="text" name="ktml_@@ktml@@_cctext" id="ktml_@@ktml@@_cctext" value=""'+
'							onkeydown="return window[\'ktml_@@ktml@@\'].cpalette.keyDown(this, event);"'+
'							onkeyup="return window[\'ktml_@@ktml@@\'].cpalette.keyUp(this, event);"'+
'						/>'+
'					<div class="color_picker_preview_color" id="ktml_@@ktml@@_ccpreview">&#160;</div>'+
'</div>'+
'<div style="clear:both"></div>'+
'			<div id="color_picker_colors_@@ktml@@" class="color_picker_color_table">';
var ColorPaletteDivFooter =
'			</div>'+
'</fieldset>'+
'</td>'+
'	<td style="width:80px" align="center" valign="top">'+
'		<div style="position:relative;margin-top: 10px;">'+
'			<label style="vertical-align:middle;margin-left:-7px;">||Selected||</label>'+
'			<div style="margin-top: 3px;border: solid 1px #000000; width: 60px; height: 20px" id="ktml_@@ktml@@_ccsel">&#160;</div>'+
'		</div>'+
'		<input type="button" class="ktml_button" style="width: 60px;margin-top:20px" value="||OK||" onclick="window[\'ktml_@@ktml@@\'].cpalette.pickColor(window[\'ktml_@@ktml@@\'].cpalette.selcolor.style.backgroundColor)"/>'+
'		<input type="button" class="ktml_button" style="width: 60px;margin-top:10px" value="||Cancel||" onclick="window[\'ktml_@@ktml@@\'].cpalette.setVisible(false)"/>'+
'		<input type="button" class="ktml_button" style="width: 60px;margin-top:10px" value="||Help||" onclick="window[\'ktml_@@ktml@@\'].cpalette.showhelp()"/>'+
'	</td>'+
'  </tr>'+
'</table>';

function ColorPalette(owner) {
	var d;
	this.owner = owner;
	this.height = 238;
	this.width = 297;
	this.div = document.getElementById('ktml_'+this.owner.name+'_ccdiv');
	this.div.style.zIndex = 1000;
	if (is.mozilla && is.mac) {
		this.div.style.overflow = "auto";
	}
	this.original = "#000000";
	this.preview = 'ktml_'+this.owner.name+'_ccpreview';
	this.isVisible = false;
	this.isDrawn = false;

	this.userColors = util_defaultValue($KTML4_GLOBALS['colors'], '');
	if (this.userColors) {
		this.userColors = this.userColors.split(",");
	}
};

ColorPalette.prototype.dechex = function(val) {
	var dxarr = new Array();
	dxarr[10] = 'A';
	dxarr[11] = 'B';
	dxarr[12] = 'C';
	dxarr[13] = 'D';
	dxarr[14] = 'E';
	dxarr[15] = 'F';
	if (val < 10) return val;
	else return dxarr[val];
};

ColorPalette.prototype.writeColorElement = function(color) {
	return '<div class="' + (color?'color':'empty') + '" style="background-color:'+color+';"></div>';
};

ColorPalette.prototype.initializeUI = function() {
	if (this.isDrawn) {
		return;
	}
	this.isDrawn = true;
	this.drawPicker();
	var oname = this.owner.name;
	this.textfld = document.getElementById('ktml_'+oname+'_cctext');
	this.selcolor = document.getElementById('ktml_'+oname+'_ccsel');
};

ColorPalette.prototype.drawTable = function() {
	var text = '';
	if(this.userColors && this.userColors.length) {
		for(var i=0;i<this.userColors.length;i++) {
			text += this.writeColorElement(this.userColors[i]);
		}
	}	else {
		var kt_a, kt_b, kt_c, kt_hex_a, kt_hex_b, kt_hex_c;
		for (var kt_j=0; kt_j<12; kt_j++) {
			kt_b = (kt_j%6)*3;
			for (var kt_i=0;kt_i<18;kt_i++) {
				kt_a = (kt_i%6)*3;
				kt_c = parseInt(kt_i/6)*3 + parseInt(kt_j/6)*9;
	
				kt_hex_a = this.dechex(kt_a);
				kt_hex_b = this.dechex(kt_b);
				kt_hex_c = this.dechex(kt_c);
				
				kt_hex_a += '' + kt_hex_a;
				kt_hex_b += '' + kt_hex_b;
				kt_hex_c += '' + kt_hex_c;
				text+=this.writeColorElement('#' + kt_hex_c +''+ kt_hex_a +''+ kt_hex_b);
			}
		}
	}
	return text;
};

ColorPalette.prototype.drawPicker = function() {
	var text = '';
	var header = ColorPaletteDivHead.replace(/@@ktml@@/g, this.owner.name);
	header = lang_translatepage(header, this.owner.config.UILanguage, window);
	var footer = ColorPaletteDivFooter.replace(/@@ktml@@/g, this.owner.name);
	footer = lang_translatepage(footer, this.owner.config.UILanguage, window);

	text+=header;
	if(this.userColors && this.userColors.length) {
		
	} else {
		for (var i=0;i<12;i++) {
			text+=this.writeColorElement('');
		}
		text+=this.writeColorElement('#000000');
		text+=this.writeColorElement('#333333');
		text+=this.writeColorElement('#666666');
		text+=this.writeColorElement('#999999');
		text+=this.writeColorElement('#cccccc');
		text+=this.writeColorElement('#ffffff');
	}
	text+=this.drawTable();
	text+=footer;

	this.div.innerHTML = text;
	this.colors_div = document.getElementById("color_picker_colors_" + this.owner.name);
	this.recalculateDimensions();
};

ColorPalette.prototype.recalculateDimensions = function() {
	if (!this.isDrawn) {
		this.initializeUI();
	}
	this.div.style.left = "-2000px";
	this.div.style.display = 'block';
	this.colors_div.style.height = (Math.max(this.colors_div.scrollHeight, 102)) + 'px';
	this.height = this.div.offsetHeight;
	this.width = this.div.offsetWidth;
	this.div.style.display = 'none';
	this.div.style.left = "";
};

ColorPalette.prototype.mOver = function(e){
	var o = is.ie ? e.srcElement : e.target;
	var preview = document.getElementById(this.preview);
	if(o.tagName == "DIV" && o.className == "color"){
		var color = o.style.backgroundColor;
		preview.style.background = ColorPalette_transformColor(color);
		preview.innerHTML = ColorPalette_transformColor(color);
	}
};

ColorPalette.prototype.mClick = function(e) {
	utility.dom.stopEvent(is.ie ? event : e);
	var o = is.ie ? e.srcElement : e.target;

	if(o.tagName == "DIV" && o.className == "color"){
		var color = o.style.backgroundColor;
		this.textfld.value = ColorPalette_transformColor(color);
		this.selcolor.style.backgroundColor = ColorPalette_transformColor(color);
		this.textfld.focus();
		this.textfld.select();
	}
};

ColorPalette.prototype.mdblClick = function(e) {
	var o = is.ie ? e.srcElement : e.target;
	if (!this.div.contains(o)) {
		this.owner.util_restoreSelection();
		this.setVisible(false);
		return;
	}
	if(o.tagName == "DIV" && o.className == "color"){
		var color = o.style.backgroundColor;
		this.pickColor(color);
	}
};

ColorPalette.prototype.setAction = function(str) {
	this.actionStr = str;
};

ColorPalette.prototype.setSelected = function(str) {
	if (!str) {
		str = "#000000";
	}
	this.original = str;
};

ColorPalette_transformColor = ColorPalette.prototype.transformColor = function(c) {
	if (!is.mozilla) {
		return c;
	}
	var arr = [];
	if (arr = c.match(/^rgb\(([0-9]+),\s*([0-9]+),\s*([0-9]+)\)/i)) {
		var ret = '';
		for (var i = 1; i < 4; i++) {
			var tmp = utility.math.dec2hex(arr[i]);
			while (tmp.length < 2) {
				tmp = "0" + tmp;
			}
			ret += tmp;
		}
		return "#" + ret;
	} else {
		return c;
	}
};

ColorPalette.prototype.pickColor = function(selectedColor) {
	selectedColor = ColorPalette_transformColor(selectedColor);
	this.textfld.value = selectedColor;
	this.setVisible(false);
	if (this.onSelect) {
		this.onSelect(selectedColor);
	}
	utility.popup.force = true;
	utility.popup.removeModal();
	utility.popup.force = false;	
};


ColorPalette.prototype.keyPress = ColorPalette.prototype.keyUp = function(o, e) {
	try { this.selcolor.style.backgroundColor = this.textfld.value; }
	catch(e) { this.selcolor.style.backgroundColor = "#000000"; }
};

ColorPalette.prototype.keyDown = function(o, e) {
	if (e.keyCode == 13) { // ENTER
		this.pickColor(this.textfld.value);
		return utility.dom.stopEvent(e);
	}
	if (e.keyCode == 27) {
		this.setVisible(false);
		utility.popup.force = true;
		utility.popup.removeModal();
		utility.popup.force = false;	
		this.owner.util_restoreSelection();
	}

	return true;
};

ColorPalette.prototype.hide = function() {
	this.setVisible(false);
};

ColorPalette.prototype.setVisible = function(par) {
	if (this.isVisible == par) {
		return;
	}
	if (par) {
		if (!this.original) {
			this.original = "#000000";
		}
		this.textfld.value = this.original;
		this.selcolor.style.backgroundColor = this.original;
		this.div.style.display = 'block';
		var cp = this;
		this.div.style.zIndex = 20;
		this.owner.preserveSelection(false);
		this.textfld.select();
		utility.popup.makeModal(function() {
			cp.setVisible(false);
			}, this.div, false);
	} else {
		this.owner.preserveSelection(false);
		this.original = "#000000";
		this.div.style.display= 'none';
	}
	this.isVisible = par;
};

ColorPalette.prototype.showAt = function(x, y) {
	if (!this.isDrawn) {
		this.initializeUI();
	}
	var d = utility.dom.getPositionRelativeTo00(x,y, this.width, this.height);
	utility.dom.put(this.div, d.x, d.y);
};

ColorPalette.prototype.dispose = function() {
	this.div.innerHTML = '';
	this.div = null;
};

ColorPalette.prototype.showhelp = function() {
	this.owner.toolbar.showHelp("colors_picker");
}
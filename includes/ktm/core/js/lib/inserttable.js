// Copyright 2001-2005 Interakt Online. All rights reserved.

//this the width of the grid image used as background for the mask
grid_cell_width = 28;

/**
	class InsertTablePicker
*/
function InsertTablePicker(owner) {
	this.owner = owner;
	this.div = utility.dom.createElement("DIV", {
		"className":"insert_table_dialog"
	});
	this.div = this.owner.toolbar.base.appendChild(this.div);
	this.isVisible = false;
	this.isDrawn = false;
};


InsertTablePicker.prototype.initializeUI = function() {
	this.drawPicker();
	this.isDrawn = true;
};

InsertTablePicker.prototype.drawPicker = function() {
	var text = '';
	text += '<div class="square_white_shadow"></div><div class="square_shadow"></div><div class="square_mask"></div><div class="drg_info"></div>';
	this.div.innerHTML = text;

	this.wshadow = this.div.childNodes[0];
	this.shadow = this.div.childNodes[1];
	this.mask = this.div.childNodes[2];
	this.info = this.div.childNodes[3];

	var obj = this;
	var strictm = document.compatMode == "CSS1Compat";

	this.div.onmousemove = function (e) {
		var dx = (is.ie?event.clientX:e.clientX) - obj.bdialog.x - obj.sx  + (is.mozilla?(strictm?document.documentElement:document.body).scrollLeft:0);
		var dy = (is.ie?event.clientY:e.clientY) - obj.bdialog.y - obj.sy + (is.mozilla?(strictm?document.documentElement:document.body).scrollTop:0);
		obj.cx = Math.max(0,(parseInt(dx / grid_cell_width) + 1));
		obj.cy = Math.max(0,(parseInt(dy / grid_cell_width) + 1));
		if (dx<0 || dy<0) {obj.cx = 0; obj.cy=0;};
		obj.insert_table_setShadow();
	};

	this.mask.onmouseout = function (e) {
		var dx = (is.ie?event.clientX:e.clientX) - obj.bdialog.x - obj.sx  + (is.mozilla?(strictm?document.documentElement:document.body).scrollLeft:0);
		var dy = (is.ie?event.clientY:e.clientY) - obj.bdialog.y - obj.sy + (is.mozilla?(strictm?document.documentElement:document.body).scrollTop:0);
		var nx = Math.max(0,(parseInt(dx / grid_cell_width) + 1));
		var ny = Math.max(0,(parseInt(dy / grid_cell_width) + 1));
		var expandgrid = false;
		if (nx>obj.mx) {
			expandgrid = true;
			obj.mx = nx;
		}
		if (ny>obj.my) {
			expandgrid = true;
			obj.my = ny;
		}
		if (expandgrid) {
			obj.insert_table_setGrid();
		}
	
		obj.cx = nx;
		obj.cy = ny;
	
		obj.insert_table_setShadow();
	};

	this.mask.onmouseup = function(e) {
		//var o = utility.dom.setEventVars(e);
		obj.select();
	};
};

InsertTablePicker.prototype.insert_table_setShadow = function() {
		if (this.cx>this.mx) {
			this.cx = this.mx;
		}
		if (this.cy>this.my) {
			this.cy = this.my;
		}

		this.shadow.style.width = (this.cx * grid_cell_width) + "px";
		this.shadow.style.height = (this.cy * grid_cell_width) + "px";
		this.info.innerHTML = this.cx?(this.cy + " x " + this.cx + " " + translate("insert_table", this.owner.config.UILanguage)):"";
};

InsertTablePicker.prototype.insert_table_setGrid = function () {
	var nw = this.mx * grid_cell_width;
	var nh = this.my * grid_cell_width;
	var dw = this.mask.offsetWidth - nw;
	var dh = this.mask.offsetHeight - nh;
	
	this.div.style.width = (nw + 2 * this.sx) + "px";
	this.div.style.height = (nh + 3 * this.sy + this.info.offsetHeight) + "px";
	this.wshadow.style.width = (nw) + "px";
	this.wshadow.style.height = (nh) + "px";

	this.info.style.width = (this.info.offsetWidth - dw) + "px";

	this.mask.style.width = nw + "px";
	this.mask.style.height = nh + "px";
};

InsertTablePicker.prototype.select = function() {
	this.setVisible(false);
	this.onSelect({cx:this.cx, cy:this.cy});
	utility.popup.force = true;
	utility.popup.removeModal();
	utility.popup.force = false;
};

InsertTablePicker.prototype.hide = function() {
	this.setVisible(false);
};

InsertTablePicker.prototype.setVisible = function(par) {
	if (this.isVisible == par) {
		return;
	}
	if (par) {
		this.div.style.display = 'block';
		this.bdialog = utility.dom.getBox(this.div);
		this.bshadow = utility.dom.getBox(this.shadow);
		
		this.sx = this.bshadow.x - this.bdialog.x;
		this.sy = this.bshadow.y - this.bdialog.y;
		
		this.mx = 5;
		this.my = 5;
		this.cx = 0;
		this.cy = 0;
		this.insert_table_setGrid();
		this.insert_table_setShadow();

		var cp = this;
		this.div.style.zIndex = 20;
		utility.popup.makeModal(function() {
			cp.setVisible(false);
		}, this.div);
	} else {
		this.owner.preserveSelection(false);
		this.div.style.display= 'none';
	}
	this.isVisible = par;
};

InsertTablePicker.prototype.showAtElement = function(el) {
	if (!this.isDrawn) {
		this.initializeUI();
	}
	var strictm = el.ownerDocument.compatMode == "CSS1Compat";

	var box= utility.dom.getBox(el);
	var d = utility.dom.getPositionRelativeTo00(box.x, box.y,140,51);
	utility.dom.put(this.div, d.x + (is.ie?(strictm?document.documentElement:document.body).scrollLeft:0), d.y + el.offsetHeight + (is.ie?(strictm?document.documentElement:document.body).scrollTop:0));
	this.setVisible(true);
};

InsertTablePicker.prototype.dispose = function() {
	this.div.innerHTML = '';
	this.div = null;
};

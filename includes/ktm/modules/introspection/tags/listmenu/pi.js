
// Copyright 2001-2005 Interakt Online. All rights reserved.

function ListMenuPI(owner){
	this.owner = owner;
	this.listmenu_id = "Properties_listmenu_id_"+this.owner.name;
	this.listmenu_type = [];
	this.listmenu_type["menu"] = "Properties_listmenu_type_"+this.owner.name+"_menu";
	this.listmenu_type["list"] = "Properties_listmenu_type_"+this.owner.name+"_list";
	this.listmenu_size = "Properties_listmenu_size_"+this.owner.name;
	this.listmenu_size_label = "Properties_listmenu_size_label_"+this.owner.name;
	this.listmenu_multiple = "Properties_listmenu_multiple_"+this.owner.name;
	this.listmenu_multiple_label = "Properties_listmenu_multiple_label_"+this.owner.name;
};

function Input_ListMenu_renameInput(el, newName) {
	el.removeAttribute("name");
	el.removeAttribute("NAME");
	el.setAttribute("NAME", newName);
	el.setAttribute("name", newName);
	return el;
};


function ListMenuPI_apply(propName, propValue){

	switch (propName) {
		case "id":
			if (propValue != "") {
				this.owner.inspectedNode.setAttribute("id",propValue);
			} else {
				this.owner.inspectedNode.removeAttribute("id");
			}
			break;
		case "options":
			var sel = this.owner.inspectedNode;
			while(sel.options.length) {
				sel.options[sel.options.length-1] = null;
			}
			for(var i=0; i<propValue.length; i++) {
				sel.options.add(new Option(propValue[i][0], propValue[i][1]));
			}
			break;
		case "value":
			var oldPropValue = this.owner.inspectedNode.value;
			if (propValue != "") {
				this.owner.inspectedNode.value = propValue;
			} else {
				this.owner.inspectedNode.removeAttribute("value");
			}
			break;
		case "size":
			var oldPropValue = this.owner.inspectedNode.size;
			if (propValue != "") {
				this.owner.inspectedNode.size = Math.max(1, propValue);
			} else {
				this.owner.inspectedNode.removeAttribute("size");
			}
			break;
		case "type":
			var oldPropValue = this.owner.inspectedNode.size;
			if (propValue == "list") {
				document.getElementById(this.listmenu_size).disabled = false;
				document.getElementById(this.listmenu_size_label).disabled = false;
				
				document.getElementById(this.listmenu_multiple).disabled = false;
				document.getElementById(this.listmenu_multiple_label).disabled = false;
				
				this.owner.inspectedNode.size = 3;
				
			} else {
				this.owner.inspectedNode.multiple = false;
				if (this.owner.inspectedNode.attributes.getNamedItem("size")) {
					this.owner.inspectedNode.removeAttribute("size");
				}

				document.getElementById(this.listmenu_size).disabled = true;
				document.getElementById(this.listmenu_size_label).disabled = true;
				document.getElementById(this.listmenu_multiple).disabled = true;
				document.getElementById(this.listmenu_multiple_label).disabled = true;

			}
			break;
		case "multiple":
			var oldPropValue = this.owner.inspectedNode.multiple;
			this.owner.inspectedNode.multiple = propValue;
			break;
	}
	this.owner.logic_domSelect(this.owner.inspectedNode, 2)
	try { fixFocusHack(0); }
	catch(e) { }
};

function ListMenuPI_inspect(propName, propValue){
	util_safeSetFieldValue(this.listmenu_id, this.owner.inspectedNode.getAttribute("id"));

	var propValue = this.owner.inspectedNode.size;
	propValue = (propValue>0) ? 'list' : 'menu';
	document.getElementById(this.listmenu_type[propValue]).checked = true;

	propValue = this.owner.inspectedNode.size;
	propValue = (propValue>0) ? propValue : '';
	util_safeSetFieldValue(this.listmenu_size, propValue);
	

	propValue = this.owner.inspectedNode.multiple;
	propValue = propValue ? true : false;
	document.getElementById(this.listmenu_multiple).checked = propValue;
};

ListMenuPI.prototype.apply = ListMenuPI_apply;
ListMenuPI.prototype.inspect = ListMenuPI_inspect;

window.KtmlPIObjects["listmenu"] = ListMenuPI;

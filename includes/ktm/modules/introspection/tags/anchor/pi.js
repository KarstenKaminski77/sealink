
function AnchorPI(owner){this.owner=owner;this.ctrl_name="Properties_anchor_name_"+this.owner.name;this.ctrl_id="Properties_anchor_id_"+this.owner.name;};function Anchor_renameButton(el,newName){el.removeAttribute("name");el.removeAttribute("NAME");el.setAttribute("NAME",newName);el.setAttribute("name",newName);return el;};function AnchorPI_apply(propName,propValue){var inspected=this.owner.inspectedNode;if(/img/i.test(inspected.tagName)&&inspected.getAttribute("orig")){inspected=new WMedia_Translator(this.owner);}
propName=propName.toLowerCase();switch(propName){case"id":if(propValue!=""){inspected.setAttribute("id",propValue);}else{inspected.removeAttribute("id");}
break;case"name":if(propValue!=""){inspected.setAttribute("name",propValue);}else{inspected.removeAttribute("name");}
break;case"remove":this.owner.insertHTML(inspected.getAttribute("content"),"first-node");this.owner.logic_updateDOMHierarchy(true,0);if(this.owner.introspector){this.owner.introspector.update();this.owner.cw.focus();}
return;default:alert("Setter for "+propName+" not implemented yet!\r\n[in AnchorPI_apply]");}
if(/img/i.test(inspected.tagName)&&inspected.getAttribute("orig")){this.owner.logic_domSelect(inspected.translated,2);}else{this.owner.logic_domSelect(inspected.translated,2);}
try{fixFocusHack(0);}
catch(e){}};function AnchorPI_inspect(propName,propValue){var inspected=this.owner.inspectedNode;if(/img/i.test(inspected.tagName)&&inspected.getAttribute("orig")){inspected=new WMedia_Translator(this.owner);}
util_safeSetFieldValue(this.ctrl_id,inspected.getAttribute("id"));util_safeSetFieldValue(this.ctrl_name,inspected.getAttribute("name"));};AnchorPI.prototype.apply=AnchorPI_apply;AnchorPI.prototype.inspect=AnchorPI_inspect;window.KtmlPIObjects["anchor"]=AnchorPI;
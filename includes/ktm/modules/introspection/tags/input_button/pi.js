
function Input_ButtonPI(owner){this.owner=owner;this.input_id="Properties_input_button_id_"+this.owner.name;this.input_label="Properties_input_button_label_"+this.owner.name;this.button_type="Properties_input_button_type_"+this.owner.name;};function Input_Button_renameButton(el,newName){el.removeAttribute("name");el.removeAttribute("NAME");el.setAttribute("NAME",newName);el.setAttribute("name",newName);return el;};function Input_ButtonPI_apply(propName,propValue){switch(propName){case"id":if(propValue!=""){this.owner.inspectedNode.setAttribute("id",propValue);}else{this.owner.inspectedNode.removeAttribute("id");};break;case"label":var oldPropValue=this.owner.inspectedNode.value;if(propValue!=""){this.owner.inspectedNode.value=propValue;}else{this.owner.inspectedNode.removeAttribute("value");}
break;case"type":var newSpan=this.owner.edit.createElement("SPAN");newSpan.id="uniqid";var oName=this.owner.inspectedNode.name;new_html=this.owner.inspectedNode.outerHTML;new_html=new_html.replace(/type="?[^\s]*"?/i,'type="'+propValue+'"');if(!/>$/.test(new_html)){new_html+='>';}
newSpan.innerHTML=new_html;this.owner.inspectedNode.parentNode.replaceChild(newSpan,this.owner.inspectedNode);var newEl=this.owner.edit.getElementById("uniqid");this.owner.inspectedNode=newEl.firstChild.cloneNode(true);newEl.parentNode.replaceChild(this.owner.inspectedNode,newEl);this.owner.inspectedNode.name=oName;break;}
this.owner.logic_domSelect(this.owner.inspectedNode,2);try{fixFocusHack(0);}
catch(e){}};function Input_ButtonPI_inspect(propName,propValue){util_safeSetFieldValue(this.input_id,this.owner.inspectedNode.getAttribute("id"));util_safeSetFieldValue(this.input_label,this.owner.inspectedNode.getAttribute("value"));var propValue=this.owner.inspectedNode.getAttribute("type");propValue=propValue?propValue:'submit';document.getElementById(this.button_type+"_"+propValue).checked=true;};function __PropertiesUI_input_button_test(){var sn=this.owner.selectableNodes[0];var st=sn.type.toLowerCase();if(sn.tagName.toLowerCase()=='input'&&(st=='button'||st=="submit"||st=="reset"))return sn;return false;};Input_ButtonPI.prototype.apply=Input_ButtonPI_apply;Input_ButtonPI.prototype.inspect=Input_ButtonPI_inspect;window.KtmlPIObjects["input_button"]=Input_ButtonPI;
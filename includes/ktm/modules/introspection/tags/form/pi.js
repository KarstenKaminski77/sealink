
function FormPI(owner){this.owner=owner;this.form_id="Properties_form_id_"+this.owner.name;this.form_action="Properties_form_action_"+this.owner.name;this.form_method="Properties_form_method_"+this.owner.name;this.form_target="Properties_form_target_"+this.owner.name;this.form_enctype="Properties_form_enctype_"+this.owner.name;};function Form_renameForm(el,newName){el.removeAttribute("name");el.removeAttribute("NAME");el.setAttribute("NAME",newName);el.setAttribute("name",newName);return el;};function FormPI_apply(propName,propValue){switch(propName){case"id":if(propValue!=""){this.owner.inspectedNode.setAttribute('id',propValue);}else{this.owner.inspectedNode.removeAttribute("id");}
break;case"action":var oldPropValue=this.owner.inspectedNode.action;if(propValue!=""){this.owner.inspectedNode.action=propValue;}else{this.owner.inspectedNode.removeAttribute("action");}
break;case"method":var oldPropValue=this.owner.inspectedNode.method;if(propValue!=""){this.owner.inspectedNode.method=propValue;}else{this.owner.inspectedNode.attributes.removeNamedItem("method");}
break;case"target":var oldPropValue=this.owner.inspectedNode.target;if(propValue!=""){this.owner.inspectedNode.target=propValue;}else{this.owner.inspectedNode.attributes.removeNamedItem("target");}
break;case"enctype":if(propValue!=""){this.owner.inspectedNode.enctype=propValue;}else{this.owner.inspectedNode.enctype="";this.owner.inspectedNode.attributes.removeNamedItem("enctype");}
break;}
try{fixFocusHack(0);}
catch(e){}};function FormPI_inspect(propName,propValue){util_safeSetFieldValue(this.form_id,this.owner.inspectedNode.getAttribute("id"));util_safeSetFieldValue(this.form_action,this.owner.inspectedNode.getAttribute("action"));var attr=this.owner.inspectedNode.attributes.getNamedItem("method");propValue=attr&&attr.specified?this.owner.inspectedNode.getAttribute("method"):"";document.getElementById(this.form_method).value=propValue;var targetAttr=this.owner.inspectedNode.attributes.getNamedItem("target");propValue=(targetAttr&&targetAttr.specified)?this.owner.inspectedNode.getAttribute("target"):"";document.getElementById(this.form_target).value=propValue;var specified=/<form[^>]*enctype=['"]?[^\s]+['"]?[^>]*>/i.test(this.owner.inspectedNode.outerHTML);propValue=this.owner.inspectedNode.enctype&&specified?this.owner.inspectedNode.enctype:"";document.getElementById(this.form_enctype).value=propValue;};FormPI.prototype.apply=FormPI_apply;FormPI.prototype.inspect=FormPI_inspect;window.KtmlPIObjects["form"]=FormPI;
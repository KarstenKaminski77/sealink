
function LabelPI(owner){this.owner=owner;this.label_id="Properties_label_id_"+this.owner.name;this.label_for="Properties_label_for_"+this.owner.name;};function LabelPI_apply(propName,propValue){switch(propName){case"id":if(propValue!=""){this.owner.inspectedNode.setAttribute("id",propValue);}else{this.owner.inspectedNode.removeAttribute("id");};break;case"for":var oldPropValue=this.owner.inspectedNode.htmlFor;if(propValue!=""){this.owner.inspectedNode.htmlFor=propValue;}else{this.owner.inspectedNode.removeAttribute("for");}
break;}
this.owner.logic_domSelect(this.owner.inspectedNode,1);try{fixFocusHack(0);}
catch(e){}}
function LabelPI_inspect(propName,propValue){util_safeSetFieldValue(this.label_id,this.owner.inspectedNode.getAttribute("id"));util_safeSetFieldValue(this.label_for,this.owner.inspectedNode.htmlFor);};LabelPI.prototype.apply=LabelPI_apply;LabelPI.prototype.inspect=LabelPI_inspect;window.KtmlPIObjects["label"]=LabelPI;
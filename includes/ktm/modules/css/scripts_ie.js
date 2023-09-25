
ktml.prototype.logic_QuerryNodeToStyleUp=function(callerObj){if(callerObj.title.toLowerCase()!="style"){return;};var _tagName=new RegExp('\\w*\\.',"i");if(!callerObj.contentIsModified||Array_indexOf(callerObj.contentIsModified,"style")<=0){callerObj.modifyValues(_tagName,'',"style");}
var _nodeSelected_=this.inspectedNode?this.inspectedNode:null;callerObj.showHideByFilter(_tagName,'hide');if(_nodeSelected_){callerObj.showHideByFilter(new RegExp('^'+_nodeSelected_.nodeName.toLowerCase()+'\\.',"i"),'show');}}
ktml.prototype.logic_InsertStyle=function(stName){var styleTagName="";var selType=this.edit.selection.type;var range=this.edit.selection.createRange();if(stName!=""){var spl=stName.split(/\./);if(spl.length==2){styleTagName=spl[0];stName=spl[1];}else{stName=spl[0];}}
if(this.selectableNodeClick){util_safeSetClassAttribute(this.selectableNodeClick,stName);return;}
if(selType=="Control"){util_safeSetClassAttribute(range.item(0),stName);return;}
if(selType.toLowerCase()=="none"){var tmpprs=range.parentElement();if(tmpprs.tagName=="BODY"){range.expand("word");try{if(range.htmlText!=""){range.pasteHTML('<span id="KTML_TEMPORARY_ID" class="'+stName+'">'+range.htmlText+'</span>');var newel=this.edit.getElementById("KTML_TEMPORARY_ID");range.moveToElementText(newel);range.select();newel.attributes.removeNamedItem("id");}}catch(e){range.select();}
return;}
while(true){if(tmpprs.tagName=="BODY"){return;}
if(stName!=""||tmpprs.className){break;}
tmpprs=tmpprs.parentElement;}
range.pasteHTML('<span id="kt_removable1">&nbsp;</span>')
util_safeSetClassAttribute(tmpprs,stName);try{range.select();var rem=this.edit.getElementById('kt_removable1');range.moveToElementText(rem);range.select();rem.removeNode(true);range.select();}catch(e){}
return;}else if(selType=="Text"){var tmpprs=range.parentElement();if(tmpprs.innerText!=range.text){if(stName==""){}
else{var seltHTML="<span id=\"KTML_TEMPORARY_ID\" class=\""+stName+"\">"+range.htmlText+"</span>";try{range.pasteHTML(seltHTML);}
catch(e){}
try{var newel=this.edit.getElementById("KTML_TEMPORARY_ID");range.moveToElementText(newel);range.select();newel.attributes.removeNamedItem("id");}catch(e){range.select();}}}else{if(tmpprs.tagName=="BODY"){return;}
util_safeSetClassAttribute(tmpprs,stName);try{range.moveToElementText(tmpprs);}
catch(e){}}
return;}else{var tmpprs=range.commonParentElement();util_safeSetClassAttribute(tmpprs,stName);}
if(stName==""&&tmpprs.tagName=="SPAN"){var oAttrColl=tmpprs.attributes;var removeTag=true;for(var i=0;i<oAttrColl.length;i++){var oneAttr=oAttrColl[i];if(oneAttr.specified){removeTag=false;break;}}
if(removeTag){tmpprs.outerHTML=tmpprs.innerHTML;}}}
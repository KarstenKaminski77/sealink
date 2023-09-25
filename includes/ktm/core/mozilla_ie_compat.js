
function KT_attachProperties(obj){obj_wnd=obj.contentWindow;obj_doc=obj.contentWindow.document;obj_doc.readyState="complete";obj_doc_proto=obj.contentWindow.document.__proto__;obj_docbody_proto=obj.contentWindow.document.body.__proto__;obj_doc_htmlel_proto=obj.contentWindow.document.createElement("P").__proto__.__proto__;obj_docbody_proto.contains=_contains;obj_doc_event_proto=obj.contentWindow.document.createEvent("MouseEvents").__proto__;obj_doc_style_proto=obj.contentWindow.document.createElement("STYLE").__proto__;obj_doc_proto.__defineGetter__("KT_Window",function(){return obj.contentWindow;});obj_doc_proto.__defineGetter__("selection",function(){if(this.KT_Window.getSelection()==null)
return null;return this.KT_Window.getSelection();});obj_wnd_proto=Window.prototype;obj_wnd_proto.__proto__={get selection(){if(this.getSelection()==null)
return null;return this.getSelection();},__proto__:obj_wnd_proto.__proto__};obj_wndsel_proto=obj.contentWindow.getSelection().__proto__;obj_wndsel_proto.__proto__={get type(){var ret="None";if(this.rangeCount==0){}else{if(this.rangeCount==1){var fRange=this.getRangeAt(0);if(fRange.toString()==""){if(fRange.startContainer.nodeType==1){var selNode=fRange.startContainer.childNodes[fRange.startOffset];if(!selNode){ret="None";}else if(selNode.nodeType==3){ret="Text";}else{ret=!selNode.canHaveChildren?"Control":"None";}}else{ret="None";}}else{ret="Text";}}else{ret="Text";}}
return ret;},createRange:function(){var sel=this;if(sel){try{return this.getRangeAt(0);}catch(e){}}else{return this.ownerDocument.createRange();}},clear:function(){this.removeAllRanges();},empty:function(){this.removeAllRanges();},__proto__:obj_wndsel_proto.__proto__};obj_doc_proto.createStyleSheet=_createStyleSheet;document.__proto__.createStyleSheet=_createStyleSheet;var howMap={"EndToEnd":Range.END_TO_END,"EndToStart":Range.END_TO_START,"StartToStart":Range.START_TO_START,"StartToEnd":Range.START_TO_END};obj_docrange_proto=obj.contentWindow.document.createRange().__proto__;obj_docrange_proto.__proto__={isEqual:function(rng){return
this.startContainer==rng.startContainer&&this.startOffset==rng.startOffset&&this.endContainer==rng.endContainer&&this.endOffset==rng.endOffset;},get KT_Window(){return obj.contentWindow;},parentElement:function(){var parent=this.commonAncestorContainer;if(parent.nodeType==3){return parent.parentNode;}else{return parent;}},moveToElementText:function(node){var first=this.findFirstTextNode(node);if(!first){first=node;}
var last=this.findLastTextNode(node);if(!last){last=first;}
try{this.setEnd(last,last.nodeValue.length);this.setStart(first,0);}catch(e){try{if(/NS_ERROR_ILLEGAL_VALUE/.test(e.message)){this.setStart(first,0);this.setEnd(last,last.nodeValue.length);}}catch(e){}}},addElement:function(node){this.selectNode(node);return;},duplicate:function(){return this.cloneRange();},compareEndPoints:function(how,eRng){return this.compareBoundaryPoints(howMap[how],eRng);},select:function(){var sel=this.KT_Window.getSelection();sel.removeAllRanges();sel.addRange(this);},findFirstTextNode:function(node){if(!node){return false;}
switch(node.nodeType){case 1:case 9:case 11:if(node.childNodes.length>0)
return this.findFirstTextNode(node.firstChild);else
return this.findFirstTextNode(node.nextSibling);case 3:return node;default:alert("Not implemented:  type="+node.nodeType);}},findLastTextNode:function(node){if(!node){return false;}
switch(node.nodeType){case 1:case 9:case 11:if(node.childNodes.length>0)
return this.findLastTextNode(node.lastChild);else
return this.findLastTextNode(node.previousSibling);case 3:return node;default:alert("Not implemented:  type="+node.nodeType);}},get htmlText(){return KT_getHTML(this.cloneContents(),false);},set htmlText(newHtml){var sc=this.startContainer;var so=this.startOffset;var ec=this.endContainer;var eo=this.endOffset;this.deleteContents();var sel=this.KT_Window.getSelection();sel.collapse(ec,0);sel.collapse(sc,so);var br=this.startContainer.ownerDocument.createElement("BR");var unique=Math.random();br.id=unique;this.insertNode(br);var newEl=this.startContainer.ownerDocument.createElement("SPAN");newEl.innerHTML=newHtml;for(var i=newEl.childNodes.length-1;i>=0;i--){this.insertNode(newEl.childNodes[i]);}
try{br=this.startContainer.ownerDocument.getElementById(unique);this.setEndAfter(br);this.setStartAfter(br);this.select();br.parentNode.removeChild(br);}catch(err){}},pasteHTML:function(newHtml){this.htmlText=newHtml;},get text(){return this.toString();},set text(newText){try{if(this.startContainer.nodeType==1){var targetNode=this.startContainer.childNodes[this.startOffset];if(targetNode.replaceData){targetNode.replaceData(0,targetNode.textContent.length,newText);var tn=this.startContainer.parentElement.tagName.toLowerCase();if((tn=="td"||tn=="th")&&newText==""){this.startContainer.innerHTML="&nbsp;";}}}else if(this.startContainer.nodeType==3){var startOffset=this.startOffset;var endOffset=this.endOffset;this.deleteContents();this.startContainer.insertData(startOffset,newText);this.setEnd(this.startContainer,startOffset+newText.length);this.select();}}catch(e){Logging.log("Error docrange text setter:");Logging.log(e.message);}
return newText;},__proto__:obj_docrange_proto.__proto__};obj_docbody_proto.createTextRange=function(){if(obj.contentWindow.getSelection().rangeCount>0){return obj.contentWindow.getSelection().getRangeAt(0);}else
return null;};obj_docbody_proto.createControlRange=function(){if(obj.contentWindow.getSelection().rangeCount>0){return obj.contentWindow.getSelection().getRangeAt(0);}else
return null;};obj_doc_event_proto.__defineSetter__("returnValue",_returnValue);obj_doc_event_proto.__defineSetter__("cancelBubble",_cancelBubble);obj_doc_event_proto.__defineGetter__("srcElement",_srcElement);obj_doc_event_proto.__defineGetter__("fromElement",_fromElement);obj_doc_event_proto.__defineGetter__("toElement",_toElement);obj_doc_event_proto.__defineGetter__("offsetX",_offsetX);obj_doc_event_proto.__defineGetter__("offsetY",_offsetY);obj_doc_htmlel_proto.__defineGetter__("parentElement",_parentElement);obj_doc_htmlel_proto.__defineGetter__("children",_children);obj_doc_htmlel_proto.contains=_contains;obj_doc_htmlel_proto.__defineSetter__("outerHTML",function(sHTML){var r=this.ownerDocument.createRange();r.setStartBefore(this);var df=r.createContextualFragment(sHTML);this.parentNode.replaceChild(df,this);return sHTML;});obj_doc_htmlel_proto.__defineGetter__("canHaveChildren",function(){switch(this.tagName){case"AREA":case"BASE":case"BASEFONT":case"COL":case"FRAME":case"HR":case"IMG":case"BR":case"INPUT":case"ISINDEX":case"LINK":case"META":case"PARAM":return false;}
return true;});obj_doc_htmlel_proto.__defineGetter__("outerHTML",function(){var newNode=this.ownerDocument.createElement("DIV");newNode.style.display="none";newNode.style.position="absolute";newNode.style.left="-500px";newNode.style.top="-500px";newNode.style.width="0px";newNode.style.height="0px";newNode.style.overflow="hidden";newNode=this.ownerDocument.body.appendChild(newNode);newNode.appendChild(this.cloneNode(true));var str=newNode.innerHTML;newNode.parentNode.removeChild(newNode);return str;var attr,attrs=this.attributes;var str="<"+this.tagName;for(var i=0;i<attrs.length;i++){attr=attrs[i];if(attr.specified){str+=" "+attr.name+'="'+attr.value+'"';}}
if(!this.canHaveChildren){return str+">";}
str=str+">"+this.innerHTML+"</"+this.tagName+">";return str;});obj_doc_htmlel_proto.__defineGetter__("innerText",function(){return this.textContent;});}
function _createStyleSheet_new(xmlURI){var theHeadNode=this.getElementsByTagName("head")[0];var theStyleNode=this.createElement('link');if(xmlURI){theStyleNode.rel="stylesheet";theStyleNode.type="text/css";theStyleNode.href=xmlURI;theHeadNode.appendChild(theStyleNode);}
return theStyleNode;};function _createStyleSheet(xmlURI){var theHeadNode=this.getElementsByTagName("head")[0];var theStyleNode=this.createElement('style');theStyleNode.type="text/css";theStyleNode.rules=new Array();theHeadNode.appendChild(theStyleNode);if(xmlURI!=""){var xmlHttp=new XMLHttpRequest();try{xmlHttp.open("GET",xmlURI,false);xmlHttp.send(null);}
catch(e){alert(utility.string.sprintf(translate('Cannot load stylesheet from server'),this.location.hostname,xmlURI));return null;}
if(xmlHttp.status==404){prompt('Stylesheet was not found:',xmlURI);return null;}
var theTextNode=this.createTextNode(xmlHttp.responseText);theStyleNode.appendChild(theTextNode);var re=/\s*\{([^\}]*)\}\s*/;nameList=xmlHttp.responseText.split(re);for(var i=0;i<nameList.length;i=i+2){var rul=new Object();rul.selectorText=nameList[i];rul.cssText=nameList[i+1];theStyleNode.rules.push(rul);}}else{}
return theStyleNode;};function _addRule(itemName,itemStyle){var theStyleNode=this.getElementsByTagName("style")[0];var theTextNode=this.createTextNode(itemName+" { "+itemStyle+" }");theStyleNode.appendChild(theTextNode);};function setBRSelection(node,where){if(!node){return;}
var sel=node.ownerDocument.defaultView.getSelection();if(where=="before"){if(node.nodeName=="BR"){var prevLeaf=previousLeaf(node);if(prevLeaf){sel.collapse(prevLeaf,prevLeaf.textContent.length);}else{sel.collapse(first_child(node.ownerDocument.body),0);}}else{sel.collapse(node,0);}}
if(where=="after"){if(node.nodeName=="BR"){sel.collapse(nextLeaf(node),0)}else{sel.collapse(node,0);}}};function previousLeaf(node){var nb=node_before(node);if(nb){return nb;}
var pNode=node.parentNode;if(!pNode.ownerDocument.body.contains(pNode)){return null;}
while(!node_before(pNode)){pNode=pNode.parentNode;if(!pNode||pNode&&pNode.tagName=="BODY"){return null;}}
if(!pNode.ownerDocument.body.contains(pNode)){return null;}
pNode=node_before(pNode);if(!pNode.ownerDocument.body.contains(pNode)){return null;}
while(true){if(pNode.lastChild){pNode=last_child(pNode);}else{break;}}
if(!pNode.ownerDocument.body.contains(pNode)){return null;}
return pNode;};function nextLeaf(node){if(node.nextSibling){pNode=node.nextSibling;}else{var pNode=node.parentNode;if(!pNode.ownerDocument.body.contains(pNode)){return null;}
while(!pNode.nextSibling){pNode=pNode.parentNode;if(!pNode||pNode&&pNode.tagName=="BODY"){return null;}}
if(!pNode.ownerDocument.body.contains(pNode)){return null;}
pNode=pNode.nextSibling;}
if(!pNode.ownerDocument.body.contains(pNode)){return null;}
while(true){if(pNode.firstChild){pNode=pNode.firstChild;}else{break;}}
if(!pNode.ownerDocument.body.contains(pNode)){return null;}
return pNode;};function _returnValue(b){if(!b)this.preventDefault();return b;};function _getType(){if(this.rangeCount==0){return"None";}else{if(this.rangeCount==1){var fRange=this.getRangeAt(0);if(fRange.toString()==""){return"None";}}else{}
return"Text";}};function _cancelBubble(){var node=this.target;while(node.nodeType!=1)node=node.parentNode;return node;};function _srcElement(){var node=this.target;while(node.nodeType!=1)node=node.parentNode;return node;};function _fromElement(){var node;if(this.type=="mouseover")
node=this.relatedTarget;else if(this.type=="mouseout")
node=this.target;if(!node)return;while(node.nodeType!=1)node=node.parentNode;return node;};function _toElement(){var node;if(this.type=="mouseout")
node=this.relatedTarget;else if(this.type=="mouseover")
node=this.target;if(!node)return;while(node.nodeType!=1)node=node.parentNode;return node;};function _offsetX(){return this.layerX;};function _offsetY(){return this.layerY;};function _parentElement(){if(this.parentNode==this.ownerDocument)
return null;return this.parentNode;};function _children(){var tmp=[];var j=0;var n;for(var i=0;i<this.childNodes.length;i++){n=this.childNodes[i];if(n.nodeType==1){tmp[j++]=n;if(n.name){if(!tmp[n.name])
tmp[n.name]=[];tmp[n.name][tmp[n.name].length]=n;}
if(n.id)
tmp[n.id]=n}}
return tmp;};function _contains(oEl){if(oEl==this)return true;if(oEl==null)return false;return this.contains(oEl.parentNode);};HTMLElement.prototype.__defineGetter__("parentElement",_parentElement);HTMLElement.prototype.__defineGetter__("children",_children);HTMLElement.prototype.contains=_contains;function KT_getHTML(root,outputRoot){function encode(str){str=str.replace(/&/ig,"&amp;");str=str.replace(/</ig,"&lt;");str=str.replace(/>/ig,"&gt;");str=str.replace(/\"/ig,"&quot;");return str;};var html="";switch(root.nodeType){case 1:case 11:var closed;var i;if(outputRoot){closed=(!(root.hasChildNodes()||(" script style div span ".indexOf(" "+root.tagName.toLowerCase()+" ")!=-1)));html="<"+root.tagName.toLowerCase();var attrs=root.attributes;for(i=0;i<attrs.length;++i){var a=attrs.item(i);if(!a.specified){continue;}
var name=a.name.toLowerCase();if(name.substr(0,4)=="_moz"){continue;}
var value;if(name!='style'){value=a.value;}else{value=root.style.cssText.toLowerCase();}
if(value.substr(0,4)=="_moz"){continue;}
html+=" "+name+'="'+value+'"';}
html+=closed?" />":">";}
for(i=root.firstChild;i;i=i.nextSibling){html+=KT_getHTML(i,true);}
if(outputRoot&&!closed){html+="</"+root.tagName.toLowerCase()+">";}
break;case 3:html=encode(root.data);break;case 8:html="<!--"+root.data+"-->";break;}
return html;};function is_all_ws(nod)
{if(typeof nod=="string"){return!(/[^\t\n\r ]/.test(nod));}else{return!(/[^\t\n\r ]/.test(nod.data));}};function is_ignorable(nod)
{return(nod.nodeType==8)||((nod.nodeType==3)&&is_all_ws(nod));};function node_before(sib)
{while((sib=sib.previousSibling)){if(!is_ignorable(sib))return sib;}
return null;};function node_after(sib)
{while((sib=sib.nextSibling)){if(!is_ignorable(sib))return sib;}
return null;};function last_child(par)
{var res=par.lastChild;while(res){if(!is_ignorable(res))return res;res=res.previousSibling;}
return null;};function first_child(par)
{var res=par.firstChild;while(res){if(!is_ignorable(res))return res;res=res.nextSibling;}
return null;};function data_of(txt)
{var data=txt.data;data=data.replace(/[\t\n\r ]+/g," ");if(data.charAt(0)==" ")
data=data.substring(1,data.length);if(data.charAt(data.length-1)==" ")
data=data.substring(0,data.length-1);return data;};
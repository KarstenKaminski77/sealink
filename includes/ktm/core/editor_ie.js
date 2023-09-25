
ktml.prototype.logic_updateDOMHierarchy=function(toDraw,toCollapse,toRebuild){var toUpd=this.taginspector;if(toCollapse){if(this.edit.selection.type=="Text"){var _Range=this.edit.selection.createRange();_Range.collapse();}}
if(typeof toRebuild=="undefined"){toRebuild=true;}
if(toRebuild){this.selectableNodeClick=null;var oldselectableNodes=this.selectableNodes;this.selectableNodes=null;this.selectableNodes=new Array();var tmpprs=this.logic_getSelectedNode();if(tmpprs&&toDraw&&tmpprs.ownerDocument!=this.edit){return;}}else if(this.selectableNodes.length>0){tmpprs=this.selectableNodes[0];}else{tmpprs=null;}
var cnt=0;var cElement="";var current_tag=this.logic_getSelectedNode()
while(tmpprs&&(tmpprs.tagName!="BODY")){if(tmpprs.id==HIDDEN_TAG_ID){tmpprs=tmpprs.parentElement;continue;}
tagName=tmpprs.tagName;if(/img/i.test(tagName.toLowerCase())&&tmpprs.getAttribute("orig")){tagName=tmpprs.getAttribute("for");this.selectableNodes=null;this.selectableNodes=new Array();cnt=0;cElement="";}
var tmp_class=tmpprs.className;if(tmp_class){if(tmp_class.length>15){tmp_class=tmp_class.substring(0,12)+"...";}
tmp_class="."+tmp_class;}
var elname=tagName+tmp_class;var class_name=(tmpprs.uniqueID==current_tag.uniqueID)?"tagitem_current":"tagitem";cElement=('<td><a class="'+class_name+'" hidefocus="hidefocus"  onclick="utility.dom.stopEvent(event);window[\'ktml_'+this.name+'\'].logic_domSelect('+cnt
+', null);" href="javascript:window[\'ktml_'+this.name+'\'].logic_domSelect('+cnt
+', null);">&lt;'+elname+'&gt;</a></td>')+cElement;this.selectableNodes.push(tmpprs);cnt++;tmpprs=tmpprs.parentElement;}
if(toDraw){if(cnt==0){toUpd.innerHTML='';return true;}
var removetag=translate("Remove Tag",this.config.UILanguage);var removeclasses=translate("Remove Classes",this.config.UILanguage);tmp='<table cellpadding="0" cellspacing="0" border="0"><tr>'+cElement;tmp+='<td nowrap="nowrap">...</td>';tmp+='<td nowrap="nowrap"><a class="tagitem" onclick="utility.dom.stopEvent(event);window[\'ktml_'+this.name
+'\'].logic_removeTag(0);return false;" href="javascript:window[\'ktml_'+this.name
+'\'].logic_removeTag(0);">'+removetag+'</a></td>';tmp+='<td nowrap="nowrap"><a class="tagitem"  onclick="utility.dom.stopEvent(event);window[\'ktml_'+this.name
+'\'].logic_removeClasses(0);return false;" href="javascript:window[\'ktml_'+this.name
+'\'].logic_removeClasses(0);">'+removeclasses+'</a></td>';tmp+='</tr></table>';toUpd.innerHTML=tmp;var tds=toUpd.firstChild.rows[0].cells;var sum=0;if(cnt>0){sum+=tds[0].offsetWidth;sum+=tds[tds.length-4].offsetWidth;}
var ellipsis_width=tds[tds.length-3].offsetWidth;sum+=ellipsis_width;sum+=tds[tds.length-2].offsetWidth;sum+=tds[tds.length-1].offsetWidth;tds[tds.length-3].style.display="none";for(var i=1;i<tds.length-4;i++){sum+=tds[i].offsetWidth;if(sum>this.width){tds[i-1].innerHTML=tds[tds.length-3].innerHTML;tds[i-1].noWrap="noWrap";for(var j=i;j<tds.length-4;j++){tds[j].style.display="none";}
break;}}}
return true;};ktml.prototype.logic_getSelectedNode=function(){if(document.activeElement&&/_htmlObject/i.test(document.activeElement.id)){var selType=this.edit.selection.type;var rng=this.edit.selection.createRange();}else{var selType=this.savedSelectionType;var rng=this.savedSelection;}
var startElement=null;var startElements=[];if(selType=="Text"||selType=="None"){var tmprngo=this.edit.body.createTextRange();for(var i=0;i<this.selectableNodes.length;i++){var tmpelement=this.selectableNodes[i];if(tmpelement&&tmpelement.innerHTML!=""){var tmprng=tmprngo.duplicate();try{tmprng.moveToElementText(tmpelement);}
catch(e){continue;}
if(rng.isEqual(tmprng)){if(startElement==null){startElement=tmpelement;}
startElements.push(tmpelement);}}}}
if(startElements.length!=1){startElement=startElements[startElements.length-1];}
var cnt=0;var tmpprs;if(selType=='Text'||selType=='None'){if(startElement==null){tmpprs=rng.parentElement();}else{tmpprs=startElement;}}else if(selType=="Control"){if(startElement==null){tmpprs=rng.commonParentElement();}else{tmpprs=startElement;}}
return tmpprs;};ktml.prototype.logic_cleanList=function(){this.logic_cleanTag('OL');this.logic_cleanTag('UL');};ktml.prototype.logic_cleanTag=function(name){var rem=true;var lists=this.edit.getElementsByTagName(name);for(var i=0;i<lists.length;i++){var kids=lists[i].childNodes;for(var j=0;j<kids.length;j++){try{rem=rem&(kids[i].nodeType==3);}
catch(e){rem=rem&false;}}
if(rem){lists[i].parentNode.removeChild(lists[i]);}
rem=true;}};ktml.prototype.hndlr_onkeydown=function(){var e=this.cw.event;if(e.shiftKey&&e.keyCode==35){if(!this.util_selectionInsideTags("td,th")){return true;}
try{var rng=this.edit.selection.createRange();var eRng=this.edit.body.createTextRange();var pn=null;if(rng.length){pn=rng.item(0);rng.remove(0);rng=this.edit.body.createTextRange();rng.moveToElementText(pn);rng.collapse(true);rng.select();eRng.moveToElementText(pn);pn=pn.parentElement;}else{pn=rng.parentElement();}
eRng.moveToElementText(pn);eRng.collapse(false);rng.setEndPoint("EndToEnd",eRng);rng.select();}catch(err){}
return true;}
if(e.ctrlKey&&e.keyCode==70){if(!e.shiftKey){e.cancelBubble=true;e.returnValue=false;e.keyCode=90909090;this.logic_FindReplace();return false;}}
if(!e.ctrlKey&&e.keyCode==13&&this.undo){this.undo.update();}
if(e.ctrlKey&e.shiftKey){if(e.keyCode==76){this.logic_doFormat('JustifyLeft');e.cancelBubble=true;e.returnValue=false;return false;}
if(e.keyCode==82){this.logic_doFormat('JustifyRight');e.cancelBubble=true;e.returnValue=false;return false;}
if(e.keyCode==69){this.logic_doFormat('JustifyCenter');e.cancelBubble=true;e.returnValue=false;return false;}
if(e.keyCode==74){this.logic_doFormat('JustifyFull');e.cancelBubble=true;e.returnValue=false;return false;}}
if(this.edit&&!this.edit.hasFocus()){utility.dom.stopEvent(e);return false;}
if(e.ctrlKey&&e.keyCode==90){if(this.undo){utility.dom.stopEvent(e);this.undo.undo();}else{this.logic_recCommand(KtmlGetCommand('undo'));}}
if(e.ctrlKey&&e.keyCode==89){if(this.undo){this.undo.redo();}else{this.logic_recCommand(KtmlGetCommand('redo'));}}
if(e.keyCode==9&&!e.altKey){if(!e.ctrlKey){var _targetElem=this.logic_getSelectedNode();var _targetElemContainer=_targetElem;while(_targetElemContainer.parentNode&&_targetElemContainer.parentNode.nodeName.tolowerCase!="body"){if(_targetElemContainer.nodeName.toLowerCase()=="td"){break;}
_targetElemContainer=_targetElemContainer.parentNode;}
var _currentCell=(_targetElem.nodeName.toLowerCase()=="td")?_targetElem:(_targetElemContainer.nodeName.toLowerCase()=="td")?_targetElemContainer:null;}
if(_currentCell&&!e.ctrlKey){var _cellToFocus=this.logic_tableNavigation(_currentCell,!e.shiftKey);this.logic_domSelect(_cellToFocus,1,e.shiftKey?"end":"begin");}else{if(e.shiftKey==true){this.logic_doFormat('outdent');}else{this.logic_doFormat('indent');}}
utility.dom.stopEvent(e);}else if(e.ctrlKey&&(e.keyCode==90||e.keyCode==89||e.keyCode==66||e.keyCode==73||e.keyCode==85)){utility.dom.stopEvent(e);}else if(e.ctrlKey&&e.keyCode==75){if(this.ui.showPI){this.flags.link_pi_next_focus_href=true;}
utility.dom.stopEvent(e);this.logic_InsertLink();return false;}else if(e.keyCode==8||e.keyCode==46){if(this.selectableNodes[0]&&(this.selectableNodes[0].tagName=="TABLE"||this.selectableNodes[0].tagName=="IMG")){this.logic_removeTag();utility.dom.stopEvent(e);}}};ktml.prototype.registerEvents=function(obj){obj.selection={range:null,type:'none'};utility.dom.attachEvent(this.edit,'onselectionchange',function(e){if(!obj.dontLeaveMeFlag){obj.selection.range=obj.edit.selection.createRange();obj.selection.type=obj.edit.selection.type;}});var stop_parent_scroll=($KTML4_GLOBALS['stop_parent_scroll_on_focus']+'')=='true';if(stop_parent_scroll){utility.dom.attachEvent(this.edit,"onmousewheel",function(e){if(obj.flags.mouseover){var amount=obj.cw.event.wheelDelta;amount=amount/120;utility.dom.stopEvent(obj.cw.event);obj.edit.body.scrollTop-=amount*45;return false;}},false);}
utility.dom.attachEvent(this.edit.body,'onkeyup',function(e){obj.hndlr_onkeyup(e);});utility.dom.attachEvent(this.edit.body,'onbeforedeactivate',function(e){if(e.toElement&&obj.edit==e.toElement.ownerDocument){return true;}
if(obj.dontLeaveMeFlag){obj.cw.event.cancelBubble=true;obj.cw.event.returnValue=false;return false;}
obj.active=false;obj.util_saveSelection();});utility.dom.attachEvent(this.edit.body,'onkeypress',function(){return obj.hndlr_onkeypress(obj.cw.event);});utility.dom.attachEvent(this.edit.body,'onkeydown',function(){var ret=utility.popup.escapeModal(obj.cw.event);if(ret&&obj.fullScreenState&&obj.cw.event.keyCode==27){obj.toggleFullScreen();}
return ret&&obj.hndlr_onkeydown();});utility.dom.attachEvent(this.textarea,'onkeydown',function(){return obj.hndlr_textonkeypress();});utility.dom.attachEvent(this.edit.body,'onpaste',function(){var ret=obj.pasteClipboard(true);if(ret){obj.cw.event.cancelBubble=true;obj.cw.event.returnValue=false;return false;}});utility.dom.attachEvent(this.edit,'onmouseup',function(){obj.displayShouldChange=true;if(obj.undo){obj.undo.addEdit(false);}
obj.displayChanged();obj.clickDropdown();obj.util_saveSelection();});utility.dom.attachEvent(this.edit,'onmouseup',function(){obj.active=true;try{if(focusedKTMLIndex!=null&&obj.counter!=focusedKTMLIndex&&ktmls[focusedKTMLIndex].ui.toolbar){ktmls[focusedKTMLIndex].setToolbarVisibility(false);}
if(obj.ui.showToolbar=="focus"&&!obj.toolbar.lateLoaded){focusedKTMLIndex=obj.counter;obj.setToolbarVisibility(true);obj.ui.showToolbar="none";}else if(obj.ui.originalShowToolbar=="focus"&&!obj.ui.toolbar){focusedKTMLIndex=obj.counter;ktmls[focusedKTMLIndex].setToolbarVisibility(true);}}catch(e){}});if(this.ui.originalShowToolbar=="focus"){utility.dom.attachEvent(this.textarea,'onfocus',function(){obj.active=true;try{if(focusedKTMLIndex!=null&&obj.counter!=focusedKTMLIndex&&ktmls[focusedKTMLIndex].ui.toolbar){ktmls[focusedKTMLIndex].setToolbarVisibility(false);}
if(!obj.ui.toolbar){focusedKTMLIndex=obj.counter;ktmls[focusedKTMLIndex].setToolbarVisibility(true);}}catch(e){}});}
utility.dom.attachEvent2(this.cw,'onblur',function(){obj.hndlr_onblur();});utility.dom.attachEvent(this.edit,'onmouseover',function(){obj.flags.mouseover=true;obj.hndlr_onmouseover()});if(stop_parent_scroll){utility.dom.attachEvent(this.iframe,'onmouseout',function(){obj.flags.mouseover=false;});}};ktml.prototype.hndlr_textonkeypress=function(){this.invalidate();var e=window.event;if(e.ctrlKey&&e.keyCode==70){e.cancelBubble=true;e.returnValue=false;e.keyCode=90909090;this.logic_FindReplace();return false;}};ktml.prototype.hndlr_onblur=function(){this.active=false;try{this.formElement.value=HandleOutgoingText(this);}catch(e){}};ktml.prototype.util_saveSelection=function(){this.savedSelectionType=this.edit.selection.type;this.savedSelection=this.edit.selection.createRange();return true;};ktml.prototype.hndlr_onfocus=function(){this.util_restoreSelection(false);};ktml.prototype.hndlr_onkeypress=function(e){try{if(this.edit.selection.type=="Control"){return true;}
var rng=this.edit.selection.createRange();var el=rng.parentElement();if(e.keyCode!=32&&(el.innerHTML=='&nbsp;'||el.innerHTML=='<br>')){el.innerHTML='';}}catch(err){}};ktml.prototype.clickDropdown=function(){if(this.selectableNodes.length>0){var tagName=this.selectableNodes[0].tagName;var theLegend=null;if(tagName=="FIELDSET"){var theFieldset=this.selectableNodes[0];theFieldset.focus();theLegend=theFieldset.getElementsByTagName("LEGEND");if(theLegend.length>0){theLegend=theLegend[0];}else{theLegend=null;}}else if(tagName=="LEGEND"){theLegend=this.selectableNodes[0];this.edit.selection.empty();var theFieldset=theLegend.parentElement;theFieldset.focus();}else if(tagName=="OBJECT"){}
if(theLegend){var rng=this.edit.selection.createRange();rngLegend=rng.duplicate();rngLegend.moveToElementText(theLegend);var posStart=rng.compareEndPoints("StartToEnd",rngLegend);var posEnd=rng.compareEndPoints("EndToEnd",rngLegend);if(posStart<=0||posEnd<=0){rngLegend.collapse(false);rngLegend.move("character");rngLegend.select();}}}};ktml.prototype.hndlr_onkeyup=function(){this.invalidate();var e=this.cw.event;if(this.edit&&!this.edit.hasFocus()){utility.dom.stopEvent(e);return false;}
if(e.ctrlKey&&e.keyCode==66){this.logic_doFormat('bold');this.util_saveSelection();}else if(e.ctrlKey&&e.keyCode==73){this.logic_doFormat('italic');this.util_saveSelection();}else if(e.ctrlKey&&e.keyCode==85){this.logic_doFormat('underline');this.util_saveSelection();}else if(e.ctrlKey&&e.keyCode==75){if(this.ui.showPI){utility.dom.stopEvent(e);return false;}}else{this.util_saveSelection();if(!e.ctrlKey&&(e.keyCode==32||e.keyCode==13||e.keyCode==37||e.keyCode==38||e.keyCode==39||e.keyCode==40||e.keyCode==46||e.keyCode==8)){if(this.undo){this.undo.addEdit();}}
if(!this.flags.link_pi_next_focus_href){this.flags.link_pi_next_focus_href=false;this.displayShouldChange=true;wait_displayChanged(this,e);}
return true;}};ktml.prototype.util_expandSelection=function(unused1,unused2){var range,text,pe;if(this.savedSelection){this.savedSelection.select();}
if(this.edit.selection.type!="Control"){range=this.edit.selection.createRange();text=range.htmlText;pe=range.parentElement();if(!text){if(pe&&pe.tagName=="FONT"){range.moveToElementText(pe);range.select();}}}};ktml.prototype.insertNodeAtSelection=function(insertNode,bOverwriteSelection){var win=this.edit;if(typeof(bOverwriteSelection)=="undefined"){bOverwriteSelection=false;}
var uniqueID=this.getUniqueID();var insertHTML='<span id="'+uniqueID+'"></span>';if(win.selection.type=='Text'||win.selection.type=="None"){var tr=win.selection.createRange();if(!bOverwriteSelection){tr.collapse(false);}
tr.pasteHTML(insertHTML);}else{var ctrlRange=win.selection.createRange();try{var tmpel=ctrlRange(0);if(!bOverwriteSelection){tmpel.outerHTML+=insertHTML;}else{tmpel.outerHTML=insertHTML;}}catch(e){return e.message;}}
var placeholder=win.getElementById(uniqueID);placeholder.parentElement.replaceChild(insertNode,placeholder);return insertNode;};ktml.prototype.util_restoreSelection=function(dofocus){if(typeof(dofocus)=="undefined"){dofocus=false;}
try{if(this.savedSelection){var locaSelection=this.savedSelection.duplicate();this.savedSelection=null;if(dofocus){this.cw.focus();}
locaSelection.select();}}catch(e){}};ktml.prototype.getSelectionAsNodes=function(rng){function recurseAddNode(container,start_if_reached,end_if_reached){if(C_DOACTION==2){return;}
for(var i=0;i<container.childNodes.length;i++){var current=container.childNodes[i];if(current.nodeType==1){if(current.uniqueID==start_if_reached.uniqueID&&C_DOACTION==-1){C_DOACTION=1;}
if(C_DOACTION==1&&current.uniqueID!=end_if_reached.uniqueID){C_NODESTOCLEAN.push(current);}
if(current.uniqueID==end_if_reached.uniqueID&&C_DOACTION==1){if(end_if_reached.uniqueID!=start_if_reached.uniqueID){C_NODESTOCLEAN.push(current);}
break;C_DOACTION=2;}}}
return;}
function getSelectionElements(rng){if(rng.length){return{container:rng(0),start:rng(0),end:rng(0)}}else{var sRng=rng.duplicate();sRng.collapse(true);var sn=sRng.parentElement();var eRng=rng.duplicate();eRng.collapse(false);var en=eRng.parentElement();var cp=null;var t=null;while(sn){if(sn.tagName=="BODY"){t=sn;break;}
t=en;while(t){if(t.uniqueID==sn.uniqueID){cp=t;break;}
t=t.parentElement;}
if(cp){break;}
sn=sn.parentElement;}
return{container:cp||sn,start:sRng.parentElement(),end:eRng.parentElement()}}}
var els=getSelectionElements(rng);var container=els.container;var start_if_reached=els.start;var end_if_reached=els.end;if(start_if_reached!=container){while(start_if_reached){if(start_if_reached.parentElement==container){break;}
start_if_reached=start_if_reached.parentElement;}}
if(end_if_reached!=container){while(end_if_reached){if(end_if_reached.parentElement==container){break;}
end_if_reached=end_if_reached.parentElement;}}
C_DOACTION=-1;C_NODESTOCLEAN=[];recurseAddNode(container,start_if_reached,end_if_reached);if(C_NODESTOCLEAN.length==0){if(start_if_reached.uniqueID!=end_if_reached.uniqueID){C_NODESTOCLEAN.push(container);}else{C_NODESTOCLEAN.push(start_if_reached);}}
if(C_NODESTOCLEAN.length==1){while(C_NODESTOCLEAN[0].outerHTML==C_NODESTOCLEAN[0].parentElement.innerHTML){C_NODESTOCLEAN[0]=C_NODESTOCLEAN[0].parentElement;}}
var toret=C_NODESTOCLEAN;C_DOACTION=null;C_NODESTOCLEAN=null;return toret;};function util_preventEvent(o,e){if(e.keyCode==13){if(o.onchange){o.onchange();}else if(o.onblur){o.blur();o.focus();}
return e.srcElement.tagName.toLowerCase()=="textarea";}};
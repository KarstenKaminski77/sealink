
FR_KEY=70;ktml.prototype.logic_updateDOMHierarchy=function(toDraw,toCollapse,toRebuild){var toUpd=this.taginspector;if(toCollapse){if(this.edit.selection.type=="Text"){var _Range=this.edit.selection.createRange();_Range.collapse(true);}}
if(typeof toRebuild=="undefined"){toRebuild=true;}
if(toRebuild){this.selectableNodeClick=null;var oldselectableNodes=this.selectableNodes;this.selectableNodes=null;this.selectableNodes=new Array();var tmpprs=this.logic_getSelectedNode();if(tmpprs&&tmpprs.ownerDocument!=this.edit&&toDraw){this.util_restoreSelection();this.logic_updateDOMHierarchy(toDraw,toCollapse,toRebuild);return;}}else if(this.selectableNodes.length>0){tmpprs=this.selectableNodes[0];}else{tmpprs=null;}
var cnt=0;var cElement="";var current_tag=this.logic_getSelectedNode()
while(tmpprs&&(tmpprs.tagName!="BODY")){if(tmpprs.id==HIDDEN_TAG_ID){tmpprs=tmpprs.parentElement;continue;}
tagName=tmpprs.tagName;if(/img/i.test(tagName.toLowerCase())&&tmpprs.getAttribute("orig")){tagName=tmpprs.getAttribute("for");this.selectableNodes=[];cnt=0;cElement="";}
var tmp_class=tmpprs.className;if(tmp_class){if(tmp_class.length>15){tmp_class=tmp_class.substring(0,12)+"...";}
tmp_class="."+tmp_class;}
var elname=tagName+tmp_class;var class_name=(tmpprs.isSameNode(current_tag))?"tagitem_current":"tagitem";cElement=('<td><a class="'+class_name+'" href="javascript:window[\'ktml_'+this.name+'\'].logic_domSelect('+cnt
+', null);">&lt;'+elname+'&gt;</a></td>')+cElement;this.selectableNodes.push(tmpprs);cnt++;tmpprs=tmpprs.parentElement;}
if(toDraw){if(cnt==0){toUpd.innerHTML='';return true;}
var removetag=translate("Remove Tag",this.config.UILanguage);var removeclasses=translate("Remove Classes",this.config.UILanguage);tmp='<table cellpadding="0" cellspacing="0" border="0"><tr>'+cElement;tmp+='<td nowrap="nowrap">...</td>';tmp+='<td nowrap="nowrap"><a class="tagitem" href="javascript:window[\'ktml_'+this.name
+'\'].logic_removeTag(0);">'+removetag+'</a></td>';tmp+='<td nowrap="nowrap"><a class="tagitem" href="javascript:window[\'ktml_'+this.name
+'\'].logic_removeClasses(0);">'+removeclasses+'</a></td>';tmp+='</tr></table>';var this_width=toUpd.offsetWidth;toUpd.innerHTML=tmp;var tds=toUpd.firstChild.rows[0].cells;var sum=12;if(cnt>0){sum+=tds[0].offsetWidth;sum+=tds[tds.length-4].offsetWidth;}
var ellipsis_width=tds[tds.length-3].offsetWidth;sum+=ellipsis_width;sum+=tds[tds.length-2].offsetWidth;sum+=tds[tds.length-1].offsetWidth;tds[tds.length-3].style.display="none";for(var i=1;i<tds.length-4;i++){sum+=tds[i].offsetWidth;if(sum>this_width-12){tds[i-1].innerHTML=tds[tds.length-3].innerHTML;tds[i-1].noWrap="noWrap";for(var j=i;j<tds.length-4;j++){tds[j].style.display="none";}
break;}}}};ktml.prototype.logic_getSelectedNode=function(){try{var tmpprs=this.edit.selection.focusNode;}catch(e){return null;}
if(tmpprs.nodeType==3){tmpprs=tmpprs.parentNode;}else{tmpprs=tmpprs.childNodes[this.edit.selection.anchorOffset];if(!tmpprs||tmpprs.nodeType==3){tmpprs=this.edit.selection.anchorNode;}}
return tmpprs;};ktml.prototype.logic_cleanList=function(){};function dom_stripWhiteSpaces(el,dir){}
ktml.prototype.hndlr_onkeyup=function(e){this.invalidate();if(this.flags.cleanhtml){this.flags.cleanhtml=false;var sel=this.cw.getSelection();var rng=sel.getRangeAt(0);var span1=this.edit.getElementById('kt_removable1');var span2=this.edit.getElementById('kt_removable2');if(span1){try{rng.setStart(span1,0);}catch(err){if(span2){span2.parentNode.removeChild(span2);}
return true;}}else{if(span2){span2.parentNode.removeChild(span2);}
return true;}
if(span2){try{rng.setEnd(span2,0);}catch(err){span1.parentNode.removeChild(span1);return true;}}else{span1.parentNode.removeChild(span1);return true;}
var ret=testClipboardForWordHTML(rng.htmlText+"");if(ret.has_word){rng.select();this.logic_doClean('CleanWordHTML',null,rng);span1=this.edit.getElementById('kt_removable1');span2=this.edit.getElementById('kt_removable2');}
if(span1){span1.parentNode.removeChild(span1);}
if(span2){span2.parentNode.removeChild(span2);}
sel.collapseToEnd();return true;}
if(e.ctrlKey&&e.keyCode==66){this.logic_doFormat('bold');}else if(e.ctrlKey&&e.keyCode==73){this.logic_doFormat('italic');}else if(e.ctrlKey&&e.keyCode==85){this.logic_doFormat('underline');}else if(e.keyCode==13){if(this.undo){this.undo.addEdit();}
return true;}else if(e.keyCode==32||e.keyCode==37||e.keyCode==38||e.keyCode==39||e.keyCode==40){if(this.undo){this.undo.addEdit();}}else if(e.keyCode==46||e.keyCode==8){if(this.selectableNodes[0]&&this.selectableNodes[0].tagName.toLowerCase()=="table"){if(this.undo){this.undo.addEdit();}
this.logic_removeTag();return true;}
var sel=this.cw.getSelection();var inner_html=String_trim(this.edit.body.innerHTML);if(inner_html==""||inner_html=="<br>"||/^<p>\s*<br\s*\/?>\s*<\/p>$/i.test(inner_html)){this.edit.body.innerHTML="<p>&nbsp;</p>";sel.collapse(this.edit.body.firstChild,0);if(this.undo){this.undo.addEdit();}}else{var fn=sel.focusNode;if(fn.nodeName=="BODY"){fn=fn.childNodes[sel.focusOffset];if(fn&&fn.nodeType==3&&String_trim(fn.textContent)==""){this.edit.body.removeChild(fn);}}else{if(fn&&",TD,TH,".indexOf(","+fn.nodeName+",")>=0&&String_trim(fn.textContent)==""){fn.innerHTML="&nbsp;";}}
if(this.undo){this.undo.addEdit();}}
return true;}else{if(this.edit.body.childNodes.length==1){if(this.edit.body.firstChild.nodeType==3&&String_trim(this.edit.body.firstChild.textContent).length==1){this.edit.body.innerHTML="<p>"+String_trim(this.edit.body.firstChild.textContent)+"</p>";this.cw.getSelection().collapse(this.edit.body.firstChild,1)}}
return true;}
e.preventDefault();e.stopPropagation();return true;};ktml.prototype.util_mozFixSelection=function(){try{var sel=this.cw.getSelection();if(sel.focusNode.tagName=="BODY"){var sc=sel.focusNode.childNodes[sel.focusOffset];var sv=null;while(sc&&sc.nodeType!=1){if(/^[\s\r\n]+$/i.test(sc.textContent)){sv=sc.previousSibling;sc.parentNode.removeChild(sc);sc=sv;}else{sc=sc.previousSibling;}
if(!sc){break;}
if(sc.tagName&&sc.tagName=="BODY"){break;}}
if(sc){if(sc.lastChild){sc.lastChild.textContent=sc.lastChild.textContent.replace(/[\s\r\n]*$/gi,'');sel.collapse(sc.lastChild,sc.lastChild.textContent.length);}}else{var rng=sel.getRangeAt(0);if(rng.startOffset==rng.startContainer.childNodes.length){sel.collapse(rng.startContainer.lastChild,rng.startContainer.textContent.length);}}}}catch(e){}};ktml.prototype.registerEvents=function(obj){if(is.v>1.7){this.edit.execCommand("insertBrOnReturn",false,($KTML4_GLOBALS['add_new_paragraph_on_enter']+'')!='true');}
var stop_parent_scroll=($KTML4_GLOBALS['stop_parent_scroll_on_focus']+'')=='true';if(stop_parent_scroll){document.addEventListener("DOMMouseScroll",function(e){if(obj.flags.mouseover){utility.dom.stopEvent(e);return false;}},true);}
this.edit.addEventListener('keyup',function(e){obj.hndlr_onkeyup(e);if(!obj.flags.link_pi_next_focus_href){obj.flags.link_pi_next_focus_href=false;obj.displayShouldChange=true;wait_displayChanged(obj,e);}},true);this.edit.addEventListener('keypress',function(e){var ret=utility.popup.escapeModal(e);if(ret&&obj.fullScreenState&&e.keyCode==27){obj.toggleFullScreen();}
return ret&&obj.hndlr_onkeypress(e);},false);this.edit.addEventListener('click',function(e){obj.cw.focus();},true);this.edit.addEventListener('keydown',function(e){return obj.hndlr_onkeydown(e);},true);this.textarea.addEventListener('keypress',function(e){return obj.hndlr_textonkeypress(e);},false);this.edit.addEventListener('mouseup',function(e){obj.displayShouldChange=true;if(obj.undo){obj.undo.addEdit(false);}
obj.displayChanged();},true);utility.dom.attachEvent(this.edit,'mouseup',function(){if(obj.focused){return;}
obj.focused=true;try{if(focusedKTMLIndex!=null&&obj.counter!=focusedKTMLIndex&&ktmls[focusedKTMLIndex].ui.toolbar){ktmls[focusedKTMLIndex].setToolbarVisibility(false);}
if(obj.ui.showToolbar=="focus"&&!obj.toolbar.lateLoaded){focusedKTMLIndex=obj.counter;obj.setToolbarVisibility(true);obj.ui.showToolbar="none";}else if(obj.ui.originalShowToolbar=="focus"&&!obj.ui.toolbar){focusedKTMLIndex=obj.counter;ktmls[focusedKTMLIndex].setToolbarVisibility(true);}}catch(e){}});if(this.ui.originalShowToolbar=="focus"){utility.dom.attachEvent(this.textarea,'onfocus',function(){obj.active=true;try{if(focusedKTMLIndex!=null&&obj.counter!=focusedKTMLIndex&&ktmls[focusedKTMLIndex].ui.toolbar){ktmls[focusedKTMLIndex].setToolbarVisibility(false);}
if(!obj.ui.toolbar){focusedKTMLIndex=obj.counter;ktmls[focusedKTMLIndex].setToolbarVisibility(true);}}catch(e){}});}
this.edit.addEventListener('blur',function(e){if(!obj.focused){return;}
if(obj.dontLeaveMeFlag){return;}
obj.focused=false;obj.hndlr_onblur(e);},true);this.edit.addEventListener('mouseover',function(e){obj.flags.mouseover=true;obj.hndlr_onmouseover(e);},false);if(stop_parent_scroll){this.edit.addEventListener('mouseout',function(e){obj.flags.mouseover=false;},false);}};ktml.prototype.hndlr_onkeydown=function(e){if((e.keyCode==76)&&e.ctrlKey&&e.shiftKey){e.stopPropagation();e.preventDefault();this.logic_doFormat('JustifyLeft');return false;}
if((e.keyCode==82)&&e.ctrlKey&&e.shiftKey){e.stopPropagation();e.preventDefault();this.logic_doFormat('JustifyRight');return false;}
if((e.keyCode==69)&&e.ctrlKey&&e.shiftKey){e.stopPropagation();e.preventDefault();this.logic_doFormat('JustifyCenter');return false;}
if((e.keyCode==74)&&e.ctrlKey&&e.shiftKey){e.stopPropagation();e.preventDefault();this.logic_doFormat('JustifyFull');return false;}
if(!e.ctrlKey&&e.keyCode==13&&this.undo){this.undo.update();}
if(e.ctrlKey&&e.keyCode==75){if(this.ui.showPI){this.flags.link_pi_next_focus_href=true;}
utility.dom.stopEvent(e);this.logic_InsertLink();return false;}
if(e.keyCode==9&&!e.ctrlKey){var _targetElem=this.logic_getSelectedNode();var _targetElemContainer=_targetElem;while(_targetElemContainer.parentNode&&_targetElemContainer.parentNode.nodeName.toLowerCase()!="body"){if(_targetElemContainer.nodeName.toLowerCase()=="td"){break;}
_targetElemContainer=_targetElemContainer.parentNode;}
var _currentCell=(_targetElem.nodeName.toLowerCase()=="td")?_targetElem:(_targetElemContainer.nodeName.toLowerCase()=="td")?_targetElemContainer:null;if(_currentCell){var _cellToFocus=this.logic_tableNavigation(_currentCell,!e.shiftKey);this.logic_domSelect(_cellToFocus,1,e.shiftKey?"end":"begin");}else{if(e.shiftKey==true){this.logic_doFormat('outdent');}else{this.logic_doFormat('indent');}}}
if(e.ctrlKey&&e.keyCode==90){if(this.undo){var tmp=this.undo.edits[this.undo.cursor].text;tmp=tmp.replace(/<[^>]*>/g,'');var tmp2=this.edit.body.innerHTML;tmp2=tmp2.replace(/<[^>]*>/g,'');if(tmp!=tmp2){this.undo.addEdit();}
this.undo.undo();}else{this.logic_recCommand(KtmlGetCommand('undo'));}}else if(e.ctrlKey&&e.keyCode==89){if(this.undo){this.undo.redo();}else{this.logic_recCommand(KtmlGetCommand('redo'));}}};ktml.prototype.hndlr_onblur=function(e){this.formElement.value=HandleOutgoingText(this);};ktml.prototype.hndlr_onfocus=function(){};ktml.prototype.hndlr_onkeypress=function(e){var keycode=e.keyCode;if(keycode==0){keycode=e.charCode;}
if(this.flags.cleanhtml){e.stopPropagation();e.preventDefault();return false;}
if(e.ctrlKey&&keycode==118){var ret=this.pasteClipboard(true);if(ret){e.stopPropagation();e.preventDefault();return false;}
this.flags.cleanhtml=true;this.edit.execCommand("inserthtml",false,'<span id="kt_removable1"></span><br><span id="kt_removable2"></span>');var sel=this.cw.getSelection();var rng=sel.getRangeAt(0);var span1=this.edit.getElementById('kt_removable1');var span2=this.edit.getElementById('kt_removable2');try{rng.setStartAfter(span1);}catch(err){}
try{rng.setEndBefore(span2);}catch(err){}
rng.select();return true;}
if((keycode==102)&&e.ctrlKey&&!e.altKey&&!e.shiftKey){e.stopPropagation();e.preventDefault();this.logic_FindReplace();return false;}
this.focused=true;if(e.ctrlKey){if(",114,116,".indexOf(","+keycode+",")>=0){return true;}}
if(e.ctrlKey||e.keyCode==8||e.keyCode==46||keycode==17||keycode==9||keycode==13&&!e.shiftKey){if(keycode!=8&&keycode!=46&&keycode!=35&&keycode!=36&&keycode!=37&&keycode!=38&&keycode!=39&&keycode!=40&&keycode!=97&&keycode!=86&&keycode!=88&&keycode!=67&&keycode!=120&&keycode!=99&&keycode!=118&&keycode!=70&&keycode!=102){if(keycode==13){var p2br=($KTML4_GLOBALS['add_new_paragraph_on_enter']+'')=='true';p2br=e.ctrlKey||p2br&&!e.shiftKey;if(p2br){var cmd_checklist=['bold','italic','underline','superscript','subscript'];var cmd_states1=[];for(var i=0;i<cmd_checklist.length;i++){cmd_states1[i]=this.edit.queryCommandState(cmd_checklist[i]);}
var sel=this.edit.selection;var selection=sel.getRangeAt(0);var selectionStart=selection.cloneRange();var selectionEnd=selection.cloneRange();selectionStart.collapse(true);selectionEnd.collapse(false);var tn="";var tmp=selectionStart.startContainer;var table1=utility.dom.getParentByTagName(tmp,"TABLE");while(tmp){if(tmp.nodeType!=1){tmp=tmp.parentNode;continue;}
tn=tmp.tagName.toLowerCase();if(",tr,tbody,table,".indexOf(","+tn+",")>=0){utility.dom.stopEvent(e);return false;}
if(",body,td,th,form,fieldset,".indexOf(","+tn+",")>=0){break;}
if(",div,li,img,".indexOf(","+tn+",")>=0){var tmpP=tmp.parentNode;if(tn=="li"&&tmp.innerHTML=="<br>"&&tmpP){if(last_child(tmpP)==tmp){tmpP.removeChild(tmp);selection.selectNode(tmpP);selection.collapse(false);selection.select();selectionStart=selection.cloneRange();selectionEnd=selection.cloneRange();selectionStart.collapse(true);selectionEnd.collapse(false);break;}else{tmpPC=tmpP.cloneNode(false);var fc=null;var bef_count=0;while((fc=first_child(tmpP))){if(fc==tmp){break;}
bef_count++;tmpPC.appendChild(fc);}
tmpP.removeChild(tmp);if(bef_count){tmpP.parentNode.insertBefore(tmpPC,tmpP);}
var newP=this.edit.createElement("P");newP.innerHTML="&nbsp;";newP=tmpP.parentNode.insertBefore(newP,tmpP);sel.collapse(newP,0);utility.dom.stopEvent(e);return false;}}
return true;}
tmp=tmp.parentNode;}
var table2=null;if(selectionEnd.startContainer!=selectionStart.startContainer){tmp=selectionEnd.startContainer;table2=utility.dom.getParentByTagName(tmp,"TABLE");while(tmp){if(tmp.nodeType!=1){tmp=tmp.parentNode;continue;}
tn=tmp.tagName.toLowerCase();if(",tr,tbody,table,".indexOf(","+tn+",")>=0){utility.dom.stopEvent(e);return false;}
if(",body,td,th,form,fieldset,".indexOf(","+tn+",")>=0){break;}
if(",div,li,img,".indexOf(","+tn+",")>=0){if(tmp.innerHTML=="<br>"){break;}
return true;}
tmp=tmp.parentNode;}}else{table2=table1;}
if(table1&&!table2||!table1&&table2||table1&&table2&&table1!=table2){utility.dom.stopEvent(e);return false;}
var parent_start_block=null;var parent_start_block_tag_name="";var table_found1=false;var table_found2=false;var new_block_end_html1="";var curtagname="",tmp_html="";var only_add_p_after1=false;tmp=selectionStart.startContainer;while(tmp){if(tmp.nodeType!=1){tmp=tmp.parentNode;continue;}
curtagname=tmp.tagName.toLowerCase();if(",body,td,th,form,fieldset,".indexOf(","+curtagname+",")>=0){parent_start_block_tag_name=curtagname;parent_start_block=tmp;only_add_p_after1=true;break;}
tmp_html=tmp.outerHTML;new_block_end_html1+=tmp_html.substring(tmp_html.lastIndexOf("<"),tmp_html.lastIndexOf(">")+1);if(",p,h1,h2,h3,h4,h5,address,pre,".indexOf(","+curtagname+",")>=0){parent_start_block_tag_name=curtagname;parent_start_block=tmp;break;}
tmp=tmp.parentNode;}
if(!parent_start_block){return true;}
tmp=selectionStart.startContainer;if(tmp.nodeType!=1){tmp=tmp.parentNode;}
var parent_end_block=null;var parent_end_block_tag_name="";tmp=selectionEnd.startContainer;var new_block_begin_html2="";var only_add_p_after2=false;while(tmp){if(tmp.nodeType!=1){tmp=tmp.parentNode;continue;}
curtagname=tmp.tagName.toLowerCase();if(",body,td,th,form,".indexOf(","+curtagname+",")>=0){parent_end_block_tag_name=curtagname;parent_end_block=tmp;only_add_p_after2=true;break;}
tmp_html=tmp.outerHTML;new_block_begin_html2=tmp_html.substring(0,tmp_html.indexOf(">")+1)+new_block_begin_html2;if(",p,h1,h2,h3,h4,h5,address,pre,".indexOf(","+curtagname+",")>=0){parent_end_block_tag_name=curtagname;parent_end_block=tmp;break;}
tmp=tmp.parentNode;}
var new_html="";if(!only_add_p_after1&&!only_add_p_after2){selection.deleteContents();if(!parent_end_block||parent_end_block==parent_start_block){if(parent_end_block==parent_start_block){var newSpan=this.edit.createElement("SPAN");newSpan.id="kt_removable";newSpan.innerHTML="kt_remove_me";selection.insertNode(newSpan);var parent_block_html=parent_start_block.outerHTML;var span_html=newSpan.outerHTML;var parent_block_html_begin=parent_block_html.substring(0,parent_block_html.indexOf(span_html));var parent_block_html_end=parent_block_html.substring(parent_block_html.indexOf(span_html)+span_html.length);var removable=this.edit.getElementById("kt_removable");var t1=removable.previousSibling;var t2=removable.nextSibling;removable.parentNode.removeChild(removable);if(t1&&t1.nodeType==3&&t2&&t2.nodeType==3){var tx2=t2.textContent;t2.parentNode.removeChild(t2);t1.appendData(tx2);}
parent_block_html_begin=parent_block_html_begin.replace(/\s$/,'&nbsp;');parent_block_html_end=parent_block_html_end.replace(/\s*$/,'');var new_block_end_text=String_trim(parent_block_html_end.replace(/\s*<\/?[^>]*>\s*/gi,''));if(new_block_end_text==""){if(",h1,h2,h3,h4,h5,".indexOf(","+parent_start_block_tag_name+",")>=0){new_block_begin_html2=new_block_begin_html2.replace(/^<[a-zA-Z0-9]+/,'<p');parent_block_html_end=parent_block_html_end.replace(/<\/[a-zA-Z0-9]+>/,'</p>');}}
selection.selectNode(parent_start_block);new_html=parent_block_html_begin+"<br id=kt_new_para_before>"+new_block_end_html1+""+new_block_begin_html2+"<br id=kt_new_para_after>"+parent_block_html_end;}else{alert("NOT IMPLEMENTED");}}else{var parent_block_html1=parent_start_block.outerHTML;var parent_block_html2=parent_end_block.outerHTML;while(/<\/[^>]*>\s*$/i.test(parent_block_html1)){parent_block_html1=parent_block_html1.replace(/<\/[^>]*>\s*$/i,'');}
while(/^\s*<[^>]*>/i.test(parent_block_html2)){parent_block_html2=parent_block_html2.replace(/^\s*<[^>]*>/i,'');}
selection.setStartBefore(parent_start_block);selection.setEndAfter(parent_end_block);new_html=parent_block_html1+"<br id=kt_new_para_before>"+new_block_end_html1+""+new_block_begin_html2+"<br id=kt_new_para_after>"+parent_block_html2;}}else{if(only_add_p_after2){var focusNode=sel.focusNode;new_html="<p><br id=kt_new_para_after>";if(focusNode.nodeType==3){var new_content=String_trim(focusNode.substringData(sel.focusOffset,focusNode.data.length));if(String_trim(new_content)!=""){new_html+=new_content;focusNode.deleteData(sel.focusOffset,focusNode.data.length);sel.collapseToEnd();}}}else{sel.deleteFromDocument();selectionStart.collapse(false);selectionEnd.collapse(true);selectionStart.select();selectionEnd.select();sel.collapse(selectionEnd.startContainer,0);}}
if(new_html){selection.pasteHTML("");selection.select();while(/<\/[^>]*><br><\/[^>]*>/i.test(new_html)){new_html=new_html.replace(/<\/[^>]*><br><\/[^>]*>/i,'');}
try{this.edit.execCommand("InsertHTML",false,new_html);}catch(e){}}
utility.dom.stopEvent(e);var new_block=this.edit.getElementById("kt_new_para_before");var selNode=null;if(new_block){selNode=new_block.parentNode;if(selNode.childNodes.length>1){selNode.removeChild(new_block);}else{if(selNode.tagName=="A"){selNode=selNode.parentNode;}
selNode.innerHTML="<br>";}}
new_block=this.edit.getElementById("kt_new_para_after");if(new_block){selNode=new_block.parentNode;if(selNode.nodeName=="A"&&selNode.childNodes.length==2&&selNode.firstChild.nodeName=="BR"&&selNode.lastChild.nodeName=="BR"){selNode=selNode.parentNode;sel.collapse(selNode,1);selNode.innerHTML="<br>";}else if(selNode.childNodes.length>=2){sel.collapse(selNode,1);selNode.removeChild(new_block);}else{sel.collapse(selNode,1);selNode.innerHTML="<br>";}
var cmd_state2=false;for(var i=0;i<cmd_checklist.length;i++){if(!cmd_states1[i]){cmd_state2=this.edit.queryCommandState(cmd_checklist[i]);if(cmd_state2){try{this.edit.execCommand(cmd_checklist[i],false,false);}catch(e){}}}}
this.displayChanged();}
return false;}else{return true;}}
utility.dom.stopEvent(e);}else if(e.keyCode==8||e.keyCode==46){var sel=this.cw.getSelection();if(sel.type=='Control'){return true;}
var rng=sel.getRangeAt(0);var focusNode=sel.focusNode;var selNode=null;if(e.ctrlKey){if(this.edit.body.firstChild==focusNode&&this.edit.body.childNodes.length==1){utility.dom.stopEvent(e);return false;}
return true;}
var select_next=null;var focus_location="";var select_next=null;if(e.keyCode==8){select_next=node_before;focus_location="after";}else{select_next=node_after;focus_location="before";}
if(!sel.isCollapsed){try{sel.deleteFromDocument();}catch(err){}
utility.dom.stopEvent(e);return false;}
var signature_br=this.edit.createElement("BR");signature_br.id="kt_removable";rng.insertNode(signature_br);var prev_element=select_next(signature_br);if(prev_element){signature_br=signature_br.parentNode.removeChild(signature_br);return true;}else{var current_block=utility.dom.getParentByTagName(signature_br,"P");if(!current_block){signature_br=signature_br.parentNode.removeChild(signature_br);return true;}
var prevLeaf=previousLeaf(signature_br);if(!prevLeaf){signature_br=signature_br.parentNode.removeChild(signature_br);return true;}
var prev_block=utility.dom.getParentByTagName(prevLeaf,"P");if(!prev_block){signature_br=signature_br.parentNode.removeChild(signature_br);return true;}
setBRSelection(signature_br,focus_location);signature_br=signature_br.parentNode.removeChild(signature_br);return true;}
return true;var selNode=node_after(signature_br);var force_delete_last_br=false;var last_br=null;if(prev_element&&prev_element.nodeName=="BR"){if(e.keyCode==46){var tmp=prev_element.parentNode;while(tmp){tmp=last_child(tmp);if(tmp&&tmp==prev_element){force_delete_last_br=true;last_br=tmp;break;}}}
if(!force_delete_last_br){setBRSelection(selNode,focus_location);signature_br=signature_br.parentNode.removeChild(signature_br);prev_element.parentNode.removeChild(prev_element);utility.dom.stopEvent(e);return false;}}
var current_block=utility.dom.getParentByTagName(focusNode,"P");if(!current_block){signature_br=signature_br.parentNode.removeChild(signature_br);return true;}
var tmp=current_block;while(!force_delete_last_br&&tmp){tmp=e.keyCode==8?first_child(tmp):last_child(tmp);if(tmp&&tmp.id==signature_br.id){break;}}
if(!tmp){signature_br=signature_br.parentNode.removeChild(signature_br);return true;}
var next_block=select_next(current_block);if(!next_block){signature_br=signature_br.parentNode.removeChild(signature_br);return true;}
signature_br=signature_br.parentNode.removeChild(signature_br);var survivor=e.keyCode==8?next_block:current_block;var doomed=e.keyCode==8?current_block:next_block;var survivor_html=String_trim(survivor.innerHTML||"");var doomed_html=String_trim(doomed.innerHTML||"");if(survivor_html=="<br>"){survivor_html="";}
survivor_html=survivor_html.replace(/ <br>/i,'&nbsp;');if(doomed_html=="<br>"){doomed_html="";}
if((doomed_html+survivor_html)==""){survivor_html="<br>";}
doomed.parentNode.removeChild(doomed);survivor.innerHTML=survivor_html+signature_br.outerHTML+doomed_html;selNode=this.edit.getElementById("kt_removable");if(selNode){setBRSelection(selNode,focus_location);if(selNode.parentNode.childNodes.length==1){selNode.removeAttribute("id");}else{selNode.parentNode.removeChild(selNode);}}
utility.dom.stopEvent(e);return false;}}else if(e.charCode){var sel=this.cw.getSelection();if(sel.focusNode){if(e.charCode!=32&&sel.focusNode.nodeType==1&&(sel.focusNode.innerHTML=='&nbsp;'||sel.focusNode.innerHTML=='<br>')){sel.focusNode.innerHTML='';}else if(e.charCode!=32&&sel.focusNode.nodeType==3&&/^[\s\t\r\n]*$/i.test(sel.focusNode.textContent)){var pn=sel.focusNode.parentNode;if(pn.innerHTML=="&nbsp;"||pn.innerHTML=="<br>"){pn.removeChild(sel.focusNode);}}}}
return true;};ktml.prototype.util_saveSelection=function(){};ktml.prototype.util_restoreSelection=function(){this.cw.focus();};ktml.prototype.util_expandSelection=function(tag,attributes){var rng,text,pe,i;if(this.edit.selection.type!="Control"){rng=this.edit.selection.createRange();text=rng.htmlText;pe=rng.parentElement();if(!text){if(pe&&pe.tagName=="FONT"){for(i=0;i<attributes.length;i+=2){pe.setAttribute(attributes[i],attributes[i+1]);}}else{var tmp="<"+tag+" ";for(i=0;i<attributes.length;i+=2){tmp+=attributes[i]+"='"+attributes[i+1].replace("'","\\'")+"'";}
tmp+='>';tmp+=pe.innerHTML;tmp+="</"+tag+">";pe.innerHTML=tmp;}}else{if(is.mozilla){var fnt=this.edit.createElement("FONT");for(i=0;i<attributes.length;i+=2){fnt.setAttribute(attributes[i],attributes[i+1]);}
fnt.appendChild(rng.extractContents());rng.insertNode(fnt);this.cw.focus();}}}};ktml.prototype.insertNodeAtSelection=function(insertNode,bOverwriteSelection){var win=this.edit;if(typeof(bOverwriteSelection)=="undefined"){bOverwriteSelection=false;}
var sel=win.selection;if(sel.rangeCount>1){return null;}
if(bOverwriteSelection){var range=sel.getRangeAt(0);range.deleteContents();}else{sel.collapseToEnd();}
sel=win.selection;var range=sel.getRangeAt(0);var uniqueID=this.getUniqueID();var insertHTML='<span id="'+uniqueID+'"></span>';range.pasteHTML(insertHTML);var placeholder=win.getElementById(uniqueID);placeholder.parentElement.replaceChild(insertNode,placeholder);return insertNode;};ktml.prototype.getSelectionAsNodes=function(rng){var toret=[];var tmp_rng=rng.cloneRange();var container=tmp_rng.commonAncestorContainer;if(container.nodeName=="HTML"){container=container.ownerDocument.body;}
if(container.nodeType!=TEXT_NODE){var allNodes=container.childNodes;for(var i=0;i<allNodes.length;i++){if(allNodes[i].nodeType!=1){continue;}
if(tmp_rng.compareNode(allNodes[i])==3||tmp_rng.intersectsNode(allNodes[i])==1){toret.push(allNodes[i]);}}}else{while(container.nodeType==TEXT_NODE){container=container.parentNode;}
toret[0]=container;}
if(toret.length==1){while(toret[0].outerHTML==toret[0].parentElement.innerHTML){toret[0]=toret[0].parentElement;}}
return toret;};ktml.prototype.hndlr_textonkeypress=function(e){this.invalidate();if(e.ctrlKey&&e.charCode==102){utility.dom.stopEvent(e);this.logic_FindReplace();return false;}};function util_preventEvent(o,e){var keycode=e.keyCode;if(keycode==0){keycode=e.charCode;}
if(keycode==13){if(o.onchange){o.onchange();}else if(o.onblur){o.blur();o.focus();}
if(e.target.tagName.toLowerCase()!="textarea"){e.preventDefault();e.stopPropagation();return false;}}};
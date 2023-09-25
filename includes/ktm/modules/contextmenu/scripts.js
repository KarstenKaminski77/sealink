
function HTMLPopupMenu(k,startMenuItems,parentMenuItem){if(typeof(KTStorage)=="undefined"){KTStorage=new ObjectStorage("ktml")}
KTStorage.add(this);this.menuItems_length=0;this.item_over_index=-1;this.parent=parentMenuItem;this.startMenuItems=startMenuItems;this.menuItems=[];this.painted=false;this.inKTML=true;if(typeof k["toolbar"]=="undefined"){this.inKTML=false;}
if(this.inKTML){this.owner=k.counter;var loca=window.location.toString();loca=loca.replace(/([^\/]*)$/i,"");}else{this.options=k;options=k;this.parentElem=options.parentElem;this.containerElem=options.containerElem;}};HTMLPopupMenu.prototype.paint=function(force){if(this.painted&&(typeof force!="undefined"&&force==false)){return;}
this.menu=document.createElement("DIV");this.menu.style.display="none";this.menu.className="ktmlContextMenuDIV";this.menu.innerHTML='<table cellspacing="0" cellpadding="0" border="0" onfocus="event.cancelBubble=true;return false;" onselect="return false" onselectstart="return false"></table>';utility.dom.attachEvent(this.menu,'oncontextmenu',function(){return false;});if(this.inKTML){var div=window.ktmls[this.owner].div;}else{var div=this.parentElem;}
div.appendChild(this.menu);this.table=this.menu.getElementsByTagName("TABLE")[0];var obj=this;utility.dom.attachEvent(this.table,'mouseover',function(e){obj.hndlr_onMouseOver(e)});utility.dom.attachEvent(this.table,'mouseout',function(e){obj.hndlr_onMouseOut(e)});this.painted=true;this.addMenuItems(this.startMenuItems);};HTMLPopupMenu.prototype.addMenuItem=function(mi){var obj=this;var trul=obj.table.insertRow(obj.table.rows.length);var cellu2=trul.insertCell(0);var cellu1=trul.insertCell(0);cellu1.className='ktmlContextMenuImages';cellu2.className='ktmlContextMenuText';if(is.mozilla){cellu1.style.backgroundImage='url('+KtmlRoot+'core/img/contextmenu_bg.gif'+')';}
mi.menu=obj.id;mi.index=obj.menuItems_length++;trul.id=obj.id+"_context_menu_row_"+mi.index;trul.mi=mi;if(mi.name=="-"){cellu2.innerHTML='<img src="'+KtmlRoot+'core/img/s.gif" width="100%" height="1" style="background-color:#A5A6A5"/>';cellu1.height=1;cellu2.colSpan=2;cellu2.height=1;trul.mi.disabled=true;}else{obj.menuItems[mi.name]=mi;cellu1.innerHTML='<img src="'+KtmlRoot+(typeof mi.image=="undefined"?'core/img/copy.gif':mi.image)+'" width="16" height="16"/>';cellu2.innerHTML=(obj.inKTML?window:opener).translate(mi.name,(obj.inKTML?window.ktmls[obj.owner]:ktml).config.UILanguage);var cellu3=trul.insertCell(-1);cellu3.className='ktmlContextMenuSubmenu';if(mi.action=="->"){cellu3.innerHTML='<img src="'+KtmlRoot+'core/img/arrow_right.gif'+'" />';var sub_menu=new HTMLPopupMenu(obj.inKTML?window.ktmls[obj.owner]:obj.options,mi.MenuItems,mi);obj.menuItems[mi.name].submenu=sub_menu.id;}else{cellu3.innerHTML='&nbsp;';}}
utility.dom.attachEvent(trul,mi.action_event?mi.action_event:'mousedown',function(e){obj.hndlr_onMouseDown(e);if(obj.inKTML){window.ktmls[obj.owner].util_restoreSelection(mi.action_event!='mouseup');}});};HTMLPopupMenu.prototype.addMenuItems=function(arr){if(typeof(arr)=="undefined"){return;}
if(!this.painted){this.paint();}
for(var i=0;i<arr.length;i++){this.addMenuItem(arr[i]);}
if(this.visible){window.setTimeout("HTMLPopupMenu_lateFixHeight('"+this.id+"', "+this.x+", "+this.y+")",50)}};function HTMLPopupMenu_lateFixHeight(menu_id,x,y){var popupmenu=KTStorage.get(menu_id);popupmenu.menu.style.height=popupmenu.table.offsetHeight+"px";var menu_height=popupmenu.menu.offsetHeight;var body_height=document.documentElement.clientHeight;var body_scroll_top=document.documentElement.scrollTop;if(y+menu_height>body_height){y=body_scroll_top-menu_height+body_height;}else{y+=body_scroll_top;}
var menu_width=popupmenu.menu.offsetWidth;var body_width=document.documentElement.clientWidth;var body_scroll_left=document.documentElement.scrollLeft;if(x+menu_width>body_width){x=body_scroll_left-menu_width+body_width;}else{x+=body_scroll_left;}
popupmenu.menu.style.left=x+"px";popupmenu.menu.style.top=y+"px";if(is.ie){utility.dom.toggleSpecialTags(popupmenu.menu,false,1);if(popupmenu.inKTML){utility.dom.toggleSpecialTags(popupmenu.menu,false,1,ktmls[popupmenu.owner].edit,ktmls[popupmenu.owner].iframe);};};};HTMLPopupMenu.prototype.show=function(e){if(this.inKTML){var box=utility.dom.getBox(window.ktmls[this.owner].iframe);}else{var box=utility.dom.getBox(this.containerElem);}
if(is.ie){this.x=e.clientX+box.x;this.y=e.clientY+box.y;}else{this.x=e.clientX+box.x-box.scrollLeft;this.y=e.clientY+box.y-box.scrollTop;}
this.showAt(this.x,this.y,e);return false;};HTMLPopupMenu.prototype.showAt=function(x,y,e){if(!this.painted){this.paint();}
this.visible=true;this.menu.style.left=-800+"px";this.menu.style.top=-800+"px";if(this.parent){var parent_menu=KTStorage.get(this.parent.menu);parent_menu.open_submenu=this.id;if(this.parent.submenu_show_timeout){window.clearTimeout(this.parent.submenu_show_timeout);this.parent.submenu_show_timeout=null;}}else{var pm=this.id;if(this.inKTML){window.ktmls[this.owner].util_saveSelection();window.ktmls[this.owner].preserveSelection(true);}
utility.popup.makeModal(function(){var menu=KTStorage.get(pm);menu.hide("down");if(menu.inKTML){window.ktmls[menu.owner].preserveSelection(false);}},this.menu);}
var visible_count=0;for(var i=0;i<this.table.rows.length;i++){var showIt=false;var theRow=this.table.rows[i];if(typeof theRow.mi=="undefined"){continue;}
theRow.mi.disabled=true;showIt=this.evaluate(theRow.mi.show_if,e);if(!showIt){theRow.className='ktmlContextMenuItem_invisible';continue;}
visible_count++;showIt=this.evaluate(theRow.mi.enable_if,e);if(!showIt){theRow.className='ktmlContextMenuItem_disabled';continue;}
if(theRow.mi.name!="-"){theRow.mi.disabled=false;}
visible_count++;theRow.className='';}
if(!visible_count){return;}
var parentStackLevel,z_index=999;if(this.parent){parentStackLevel=parseInt(utility.dom.getStyleProperty(this.menu,"z-index"));if(!isNaN(parentStackLevel)){z_index=parentStackLevel+1;}};this.menu.style.zIndex=String(z_index);this.menu.style.display="block";if(is.mozilla){this.menu.style.width=(this.table.offsetWidth)+"px";}
HTMLPopupMenu_lateFixHeight(this.id,x,y);};HTMLPopupMenu.prototype.hide=function(closeAll){if(!this.painted){return;}
this.visible=false;this.menu.style.display="none";this.item_over_index=-1;if(is.ie&&!this.parent){utility.dom.toggleSpecialTags(this.menu,false,0);if(this.inKTML){var docObj=ktmls[this.owner].edit;var docBox=ktmls[this.owner].iframe;utility.dom.toggleSpecialTags(this.menu,false,0,docObj,docBox);};}
if(closeAll){if(closeAll=="down"&&this.open_submenu){var open_menu=KTStorage.get(this.open_submenu);open_menu.hide("down");}
if(closeAll=="up"&&this.parent){var parent_menu=KTStorage.get(this.parent.menu);parent_menu.hide("up");}}};HTMLPopupMenu.prototype.evaluate=function(code,e){var res=false;switch(typeof code){case"string":if(code=="->"){break;}
res=eval(code);break;case"function":res=code(this,e);break;case"boolean":res=code;break;default:res=false;}
return res;};function HTMLPopupMenuItem(popupMenu){this.parentElem=popupMenu;};function HTMLPopupMenu_hndlr_onMouseOver(e){var oe=utility.dom.setEventVars(e);var el=oe.targ;var el=utility.dom.getParentByTagName(el,"TR");if(!el){return;}
if(!el||el&&(!el.mi||el.mi&&el.mi.disabled)){return;}
var menu=KTStorage.get(el.mi.menu);if(menu.inKTML){window.ktmls[menu.owner].preserveSelection(true);}
if(menu.item_over_index==el.mi.index){return;}
if(menu.item_over_index!=-1){contextmenu_dehighilite_item(menu.id,menu.item_over_index);}
contextmenu_highilite_item(menu.id,el.mi.index);if(menu.parent){var parent_menu=KTStorage.get(menu.parent.menu);window.clearTimeout(parent_menu.submenu_clear_timeout);}
window.clearTimeout(menu.submenu_show_timeout);if(el.mi.submenu){var box=utility.dom.getBox(el);menu.submenu_show_timeout=window.setTimeout("late_submenu_show('"+el.mi.submenu+"', "+(box.x+box.width-(is.mozilla?box.scrollLeft:2)-3)+", "+(box.y-(is.mozilla?box.scrollTop:0)-3)+")",30);}};function late_submenu_show(menu_id,x,y){var menu=KTStorage.get(menu_id);menu.showAt(x,y);};function HTMLPopupMenu_hndlr_onMouseOut(e){var oe=utility.dom.setEventVars(e);var el=oe.targ;var el=utility.dom.getParentByTagName(el,"TR");if(!el||el&&(!el.mi||el.mi&&el.mi.disabled)){return;}
var el_to=is.ie?oe.e.toElement:oe.e.relatedTarget;if(!el_to){return;}
var menu=KTStorage.get(el.mi.menu);var el_to=utility.dom.getParentByTagName(el_to,"TR");var deh="now";if(el_to){if(el_to.mi){if(el_to.mi.menu==el.mi.menu){if(el_to.mi.index==menu.item_over_index){deh="";}}else{if(el_to.mi.menu==el.mi.submenu){deh="";}}}else{deh="later";}}else{if(el.mi.submenu){deh="later";}}
if(deh=="now"){contextmenu_dehighilite_item(menu.id,el.mi.index);}else if(deh=="later"){menu.submenu_clear_timeout=window.setTimeout(function(){contextmenu_dehighilite_item(menu.id,el.mi.index);},150);}};function contextmenu_highilite_item(id,idx){var menu=KTStorage.get(id);var el=document.getElementById(id+'_context_menu_row_'+idx);if(el&&el.className!='ktmlContextMenuItem_highlight'){menu.item_over_index=idx;el.className='ktmlContextMenuItem_highlight';}};function contextmenu_dehighilite_item(id,idx){var menu=KTStorage.get(id);var el=document.getElementById(id+'_context_menu_row_'+idx);if(el&&el.className!=''){menu.item_over_index=-1;el.className='';if(el.mi.submenu&&menu.open_submenu==el.mi.submenu){late_submenu_hide(menu.open_submenu);menu.open_submenu=false;}}};function late_submenu_hide(menu_id){var menu=KTStorage.get(menu_id);menu.hide("down");};function HTMLPopupMenu_hndlr_onMouseDown(e){var oe=utility.dom.setEventVars(e);var el=oe.targ;while(el){if(el.mi&&el.mi.name!="-"){break;}
el=el.parentNode;}
if(!el){return;}
var menu=KTStorage.get(el.mi.menu);utility.dom.stopEvent(oe.e);if(el.className=="ktmlContextMenuItem_disabled"){menu.hide("down");menu.hide("up");utility.popup.force=true;utility.popup.removeModal();utility.popup.force=false;return;}
menu.hide("down");menu.hide("up");utility.popup.force=true;utility.popup.removeModal();utility.popup.force=false;this.evaluate(el.mi.action,e);};HTMLPopupMenu.prototype.hndlr_onMouseOver=HTMLPopupMenu_hndlr_onMouseOver;HTMLPopupMenu.prototype.hndlr_onMouseOut=HTMLPopupMenu_hndlr_onMouseOut;HTMLPopupMenu.prototype.hndlr_onMouseDown=HTMLPopupMenu_hndlr_onMouseDown;HTMLPopupMenu.prototype.dispose=function(){this.owner=null;this.parentElem=null;this.containerElem=null;this.menu=null;this.table=null;};function KtmlPopupMenuItems(){return[{"name":"Cut","action":"window.ktmls[this.owner].logic_doFormat('cut')","show_if":"true","enable_if":"window.ktmls[this.owner].flags.clipboard_allowed && window.ktmls[this.owner].edit.queryCommandEnabled(KtmlGetCommand('cut'))","image":"core/img/cut.gif"},{"name":"Copy","action":"window.ktmls[this.owner].logic_doFormat('copy')","show_if":"true","enable_if":"window.ktmls[this.owner].flags.clipboard_allowed && window.ktmls[this.owner].edit.queryCommandEnabled(KtmlGetCommand('copy'))","image":"core/img/copy.gif"},{"name":"Paste","action":"window.ktmls[this.owner].logic_doFormat('paste')","show_if":"true","enable_if":"window.ktmls[this.owner].flags.clipboard_allowed && window.ktmls[this.owner].edit.queryCommandEnabled(KtmlGetCommand('paste'))","image":"core/img/paste.gif"},{"name":"Clean All Formatting Tags","action":"window.ktmls[this.owner].logic_doClean('CleanFormattingTags')","show_if":"testShowClean(window.ktmls[this.owner])","enable_if":"testEnableClean(window.ktmls[this.owner])","image":"core/img/clean.gif"}];}
function testShowClean(ktml){return ktml.toolbar.indexOfName('clean_menu')>=0&&ktml.edit.selection.type=="Text"||ktml.edit.selection.type!="Text"&&ktml.inspectedNode&&ktml.inspectedNode.tagName.toLowerCase()=="table";};function testEnableClean(ktml){return true;};function testClipboardForWordHTML(ktml){var ret={clipboard_allowed:false,has_word:false,html_content:'',cleaned_content:''};if(typeof(ktml)=="object"&&ktml.flags.clipboard_allowed){if(ktml.flags.clipboard_allowed){if(is.ie){var fake_clipboard=document.getElementById(ktml.name+'_paste_container');var cur_range=document.body.createTextRange();cur_range.moveToElementText(fake_clipboard);try{cur_range.execCommand(KtmlGetCommand('paste'),false,null);}catch(err){ktml.flags.clipboard_allowed=false;}
if(fake_clipboard.innerHTML=='clipboard_not_allowed'){ktml.flags.clipboard_allowed=false;}
if(ktml.flags.clipboard_allowed){ret.html_content=fake_clipboard.innerHTML;fake_clipboard.innerHTML="";ret.has_word=/mso-|MsoNormal/i.test(ret.html_content);ret.has_word=ret.has_word||/<v:imagedata/i.test(ret.html_content);}}else{ktml.paste_container.body.innerHTML="";ktml.paste_container.designMode='on';try{ktml.paste_container.execCommand(KtmlGetCommand('paste'),false,null);}catch(err){ktml.flags.clipboard_allowed=false;}
if(ktml.flags.clipboard_allowed){ret.html_content=ktml.paste_container.body.innerHTML;ktml.paste_container.body.innerHTML="";ret.has_word=/mso-|MsoNormal/i.test(ret.html_content);ret.has_word=ret.has_word||/<v:imagedata/i.test(ret.html_content);}}}
ret.clipboard_allowed=ktml.flags.clipboard_allowed;return ret;}else{ret.has_word=/mso-|MsoNormal/i.test(ktml);ret.has_word=ret.has_word||/<v:imagedata/i.test(ktml);return ret;}};function contextmenu_runeach(k){var cm=new HTMLPopupMenu(k,KtmlPopupMenuItems());KTStorage.add(cm);k.contextMenu=cm.id;if(is.ie){utility.dom.attachEvent(k.edit,'oncontextmenu',function(){k.displayShouldChange=true;k.displayChanged();KTStorage.get(k.contextMenu).show(k.cw.event);utility.dom.stopEvent(k.cw.event);return false;});}
if(is.mozilla){utility.dom.attachEvent2(k.edit,'oncontextmenu',function(e){k.displayShouldChange=true;k.displayChanged();KTStorage.get(k.contextMenu).show(e);utility.dom.stopEvent(e);return false;});}};function canMergeCells(ktml){var cellSelection=detectTableCellsSelection(ktml);if(!cellSelection){return false;}
if(cellSelection.sel.length==1&&(cellSelection.sel[0].c1==cellSelection.sel[0].c2)){return false;}
return true;};function detectTableCellsSelection(ktml){var ret={r1:-1,c1:-1,r2:-1,c2:-1};var sel=[];var cs_matrix=[];var rs_matrix=[];if(is.ie){if(ktml.edit.selection.type=="Control"){return false;}
var selRange=ktml.edit.selection.createRange();var startRange=selRange.duplicate();startRange.collapse(true);var startCell=utility.dom.getParentByTagName(startRange.parentElement(),"TD");if(!startCell){startCell=utility.dom.getParentByTagName(startRange.parentElement(),"TH");}
if(!startCell){return false;}
var endRange=selRange.duplicate();endRange.collapse(false);var endCell=utility.dom.getParentByTagName(endRange.parentElement(),"TD");if(!endCell){endCell=utility.dom.getParentByTagName(endRange.parentElement(),"TH");}
if(!endCell){return false;}
tr1=startCell.parentNode;tr2=endCell.parentNode;var row0={r:tr1.rowIndex,c1:startCell.cellIndex,c2:tr1.rowIndex==tr2.rowIndex?endCell.cellIndex:(tr1.cells.length-1)};sel.push(row0);}else{var selection=ktml.cw.getSelection();if(selection.rangeCount<2){return false;}
var rowCount=-1;var minCellIndex=10000;var colSpan=-1;for(var i=0;i<selection.rangeCount;i++){var tdRange=selection.getRangeAt(i);td=tdRange.startContainer.childNodes[tdRange.startOffset];tr=td.parentNode;if(rowCount==-1||rowCount>-1&&sel[rowCount].r!=tr.rowIndex){var curColSpan=td.colSpan;if(colSpan==-1){colSpan=curColSpan;}
rowCount++;sel.push({r:tr.rowIndex,c1:td.cellIndex});}
sel[rowCount].c2=td.cellIndex;}
var startRange=selection.getRangeAt(0);if(startRange.startContainer.nodeType==3){return false;}
var endRange=selection.getRangeAt(selection.rangeCount-1);var startCell=startRange.startContainer.childNodes[startRange.startOffset];var endCell=endRange.startContainer.childNodes[endRange.startOffset];}
var table=utility.dom.getParentByTagName(startCell,"TABLE");var table2=utility.dom.getParentByTagName(endCell,"TABLE");if(table!=table2){return false;}
var maxCols=0;var maxRows=table.rows.length;for(var i=0;i<table.rows[0].cells.length;i++){maxCols+=table.rows[0].cells[i].colSpan;}
var one_row=[];for(var i=0;i<maxRows;i++){one_row=[];for(var j=0;j<maxCols;j++){one_row.push(1);}
cs_matrix.push(one_row);one_row=[];for(var j=0;j<maxCols;j++){one_row.push(1);}
rs_matrix.push(one_row);}
var cs=0;var rs=0;for(var i=0;i<maxRows;i++){var col_offset=0;var row_offset=0;for(var j=0;j<maxCols;j++){if(cs_matrix[i][j]==0){continue;}
if(rs_matrix[i][j]==0){row_offset++;continue;}
var td=table.rows[i].cells[j-row_offset];if(!td){continue;}
cs=td.colSpan;rs=td.rowSpan;cs_matrix[i][j]=cs;rs_matrix[i][j]=rs;for(var k=1;k<cs;k++){cs_matrix[i][j+k]=0;}
col_offset=cs-1;for(var k=1;k<rs;k++){rs_matrix[i+k][j]=0;}}}
for(var i=0;i<sel.length;i++){var row=sel[i];for(var j=row.c1;j<row.c2;j++){var curCell=table.rows[row.r].cells[j];if(curCell.rowSpan!=table.rows[row.r].cells[j+1].rowSpan){return false;}
if(i<sel.length-1){var bottomCell=null;var k=1;while(i+k<sel.length){var next_row=sel[i+k];if(j<table.rows[next_row.r].cells.length){bottomCell=table.rows[next_row.r].cells[j];break;}}
if(bottomCell){if(bottomCell.colSpan!=curCell.colSpan){return false;}}}}}
ret.cs_matrix=cs_matrix;ret.table=table;ret.sel=sel;return ret;};function mergeCells(ktml){var cellSelection=detectTableCellsSelection(ktml);if(!cellSelection){return;}
if(is.ie){mergeCellsOnTheSameRow(cellSelection.table.rows[cellSelection.sel[0].r],cellSelection.sel[0].c1,cellSelection.sel[0].c2);}else{for(var i=0;i<cellSelection.sel.length;i++){if(cellSelection.sel[i].c1!=cellSelection.sel[i].c2){mergeCellsOnTheSameRow(cellSelection.table.rows[cellSelection.sel[i].r],cellSelection.sel[i].c1,cellSelection.sel[i].c2);}}
mergeCellsOnTheSameColumn(cellSelection,cellSelection.sel[0].c1,cellSelection.sel[0].r,cellSelection.sel[cellSelection.sel.length-1].r);}};function mergeCellsOnTheSameRow(row,from,to){var new_cell_inner_html="";var new_colspan=0;for(var i=from;i<=to&&i<row.cells.length;i++){new_cell_inner_html+=row.cells[i].innerHTML;new_colspan+=row.cells[i].colSpan;}
var old_cell_outter_html=row.cells[from].outerHTML;var start_html=old_cell_outter_html.replace(/>.*/,'>');var end_html=old_cell_outter_html.replace(/.*<\/(..)>$/,'<\/$1>');start_html=start_html.replace(/colspan=['"]?\d*['"]?/i,'');start_html=start_html.replace(/\s?>/i,' colspan="'+new_colspan+'">');new_cell_inner_html=compressNBSPs(new_cell_inner_html);row.cells[from].innerHTML=new_cell_inner_html;row.cells[from].colSpan=new_colspan;for(var i=Math.min(to,row.cells.length-1);i>=from+1;i--){row.removeChild(row.cells[i]);}};function mergeCellsOnTheSameColumn(cellSelection,column,from,to){var table=cellSelection.table;var new_cell_inner_html="";var new_rowspan=0;var column_idxs=[];for(var i=from;i<=to;i++){if(cellSelection.sel[i-from]){var cs_row=cellSelection.cs_matrix[i];column_idxs[i]=0;for(var j=0;j<cellSelection.sel[i-from].c1;j++){column_idxs[i]+=cs_row[j]?1:0;}}}
for(var i=from;i<=to;i++){if(cellSelection.sel[i-from]){if(table.rows[i].cells.length==0){continue;}
new_cell_inner_html+=table.rows[i].cells[column_idxs[i]].innerHTML;new_rowspan+=table.rows[i].cells[column_idxs[i]].rowSpan;}}
var old_cell_outter_html=table.rows[from].cells[column_idxs[from]].outerHTML;var start_html=old_cell_outter_html.replace(/>.*/,'>');var end_html=old_cell_outter_html.replace(/.*<\/(..)>$/,'<\/$1>');start_html=start_html.replace(/rowspan=['"]?\d*['"]?/i,'');start_html=start_html.replace(/\s?>/i,' rowspan="'+new_rowspan+'">');new_cell_inner_html=compressNBSPs(new_cell_inner_html);table.rows[from].cells[column_idxs[from]].outerHTML=start_html+new_cell_inner_html+end_html;for(var i=Math.min(to,table.rows.length-1);i>=from+1;i--){if(table.rows[i].cells.length==0){continue;}
if(cellSelection.sel[i-from]){table.rows[i].removeChild(table.rows[i].cells[column_idxs[i]]);}}};function compressNBSPs(str){while(/&nbsp;\s*&nbsp;/i.test(str)){str=str.replace(/&nbsp;\s*&nbsp;/i,"&nbsp;");}
return str;}
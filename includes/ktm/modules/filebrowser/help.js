
var fld=null;var fld_area=null;var fld_area_id="fld_area_id_context_help";var fls=null;var files_area=null;var files_area_id="files_area_id_context_help";var flt=null;function help(){help_mode=!help_mode;var elms=document.body.getElementsByTagName("INPUT");local_for(elms);elms=document.body.getElementsByTagName("SELECT");local_for(elms);fld=document.getElementById('folders_list');if(fld){utility.window.blockInterface('help',fld,fld_area_id);fld_area=document.getElementById(fld_area_id);fld_area.setAttribute("help_topic_id","file_expl_tree_menu");if(help_mode){fld_area.onclick=show_help;}
else{while(fld_area){fld_area.parentNode.removeChild(fld_area);fld_area=document.getElementById(fld_area_id);}}}
fls=document.getElementById('files_list');if(fls){utility.window.blockInterface('help',fls,files_area_id);files_area=document.getElementById(files_area_id);files_area.setAttribute("help_topic_id","file_expl_thumbnails");if(help_mode){files_area.onclick=show_help;}
else{while(files_area){files_area.parentNode.removeChild(files_area);files_area=document.getElementById(files_area_id);}}}
function local_for(elms){var help_topic_id='';for(var i=0;i<elms.length;i++){help_topic_id=elms[i].getAttribute('help_topic_id');if(!help_topic_id){continue;}
if(help_mode){elms[i].oonclick=elms[i].onclick;elms[i].onclick=show_help;utility.dom.classNameAdd(elms[i],"btn_help");}else{elms[i].onclick=elms[i].oonclick;utility.dom.classNameRemove(elms[i],"btn_help");}}}}
function stop_event(e){var o=utility.dom.setEventVars(e);utility.dom.stopEvent(o.e);return false;}
function show_help(e){stop_event(e);var help_topic_id=o.targ.getAttribute('help_topic_id');if(help_topic_id){ktml.toolbar.showHelp(help_topic_id);}
return false;}

var qs=new QueryString();var objectName=qs.find('ktmlname');var ktmlObject=window.parent["ktml_"+objectName];var language=ktmlObject.config.UILanguage;function initializeUI(pi_id){var counter=ktmlObject.counter;var relativeImagePath=parent.KtmlDirDepth+"core/img";if(is.ie){var allEL=document.body.all;for(var i=0;i<allEL.length;i++){if(allEL[i].nodeType==3){var currTextNode=allEL[i];var _text=currTextNode.nodeValue;if(String_trim(_text)==''){while(currTextNode){var _parent=currTextNode.parentNode;_parent.removeChild(currTextNode);}}}}
var elements=utility.dom.getElementsByTagName(document.body,'*');var nodesToAvoid=['input','textarea','option'];for(var i=0;i<elements.length;i++){var current=elements[i];var curentName=current.nodeName.toLowerCase();if(current.nodeType!=1){continue}
if(Array_indexOf(nodesToAvoid,curentName)==-1){current.onselectstart="return false";current.style.cursor="default";}else{current.onmouseover="               \
           var _parent = this.parentNode;       \
           while(_parent){           \
            _parent.onselectstart='return true';    \
            if(_parent.className == 'propertyinspector'){  \
             break;           \
            };             \
            _parent=_parent.parentNode;       \
           }";current.onmouseout="               \
           var _parent = this.parentNode;       \
           while(_parent){           \
            _parent.onselectstart='return false';    \
            if(_parent.className == 'propertyinspector'){  \
             break;           \
            };             \
            _parent=_parent.parentNode;       \
           }";current.style.cursor="";}}}
var ARMP=document.getElementById('ARMP');if(ARMP){var _tmpChilds;var pannels=[];var _tmpRows=ARMP.rows;for(var z=0;z<_tmpRows.length;_tmpRows++){var _tmpTDs=_tmpRows[z].cells;for(var x=0;x<_tmpTDs.length;x++){_tmpChilds=_tmpTDs[x].childNodes;for(var y=0;y<_tmpChilds.length;y++){if(_tmpChilds[y].nodeType!=1){continue};if(_tmpChilds[y].nodeName.toLowerCase()=='div'){pannels.push(_tmpChilds[y]);};};};};Array_splice(pannels,0,1);Array_each(pannels,function(pannel){pannel.style.display='none';})};var resu=document.body.innerHTML;resu=resu.replace(/__objectName__/gi,objectName);resu=resu.replace(/__relativeImagePath__/gi,relativeImagePath);resu=resu.replace(/__evstr__/gi,parent.evstr);var regu=/\|\|([^\|\|]*)\|\|/gi;while((arr=regu.exec(resu))!=null){if(translated=parent.translate(arr[1],ktmlObject.config.UILanguage)){var repl=new RegExp("\\|\\|"+arr[1]+"\\|\\|","gi");resu=resu.replace(repl,translated);}}
var PI_display_name=parent.translate(document.title,ktmlObject.config.UILanguage);ktmlObject.addPI(pi_id,PI_display_name,resu);if(typeof ktmlObject.pis[pi_id]!="undefined"&&typeof ktmlObject.pis[pi_id]["ContextMenuItems"]!="undefined"){parent.KTStorage.get(ktmlObject.contextMenu).addMenuItems(ktmlObject.pis[pi_id]["ContextMenuItems"]);}
if(arguments.length>1){for(var i=1;i<arguments.length;i++){var functor=arguments[i];if(typeof functor=='function'){functor.apply(ktmlObject);}};}}
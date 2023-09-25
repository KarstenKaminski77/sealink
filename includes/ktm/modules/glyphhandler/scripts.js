
function GlyphHandler(obj){this.owner=obj.counter;this.glyphs={};this.glyphs["table"]=document.createElement("IMG");this.glyphs["table"].GLYPH_DELTAY=13;this.glyphs["table"].GLYPH_DELTAX=3;this.glyphs["tr"]=document.createElement("IMG");this.glyphs["tr"].GLYPH_DELTAY=20;this.glyphs["tr"].GLYPH_DELTAX=3;if(is.mozilla){this.glyphs["hr"]=document.createElement("IMG");this.glyphs["hr"].GLYPH_DELTAY=5;this.glyphs["hr"].GLYPH_DELTAX=3;this.glyphs["input"]=document.createElement("IMG");this.glyphs["input"].GLYPH_DELTAY=7;this.glyphs["input"].GLYPH_DELTAX=7;}
for(var tagName in this.glyphs){this.glyphs[tagName].id="glyphFor_"+tagName;this.glyphs[tagName].style.position="absolute";this.glyphs[tagName].style.top="0px";this.glyphs[tagName].style.left="0px";this.glyphs[tagName].style.display="none";this.glyphs[tagName].style.marginBottom="-20px";this.glyphs[tagName].style.zIndex=99;this.glyphs[tagName].style.cursor="move";this.glyphs[tagName]=document.body.insertBefore(this.glyphs[tagName],document.body.firstChild);this.glyphs[tagName].src=KtmlDirDepth+'modules/glyphhandler/img/glyph_'+tagName+'.gif';this.glyphs[tagName].ktmlCounter=window.ktmls[this.owner].counter;utility.dom.attachEvent(this.glyphs[tagName],"mousedown",function(e){obj.preserveSelection(true);obj.glyphhandler.action(e);});utility.dom.attachEvent(this.glyphs[tagName],"mouseout",function(){obj.preserveSelection(false);});}};GlyphHandler.prototype.hide=function(){var glyph=null;for(var glyphName in this.glyphs){if(glyph=document.getElementById("glyphFor_"+glyphName)){this.hideGlyphTimeout=window.setTimeout("GlyphHandler_latehide("+this.owner+", \"glyphFor_"+glyphName+"\")",800);}}};function GlyphHandler_latehide(ktmlCounter,glyphId){var glyph=null;if(typeof glyphId!="undefined"){if(glyph=document.getElementById(glyphId)){glyph.style.display="none";glyph.selectObject=null;}}else{for(var glyphName in ktmls[ktmlCounter].glyphhandler.glyphs){if(glyph=document.getElementById("glyphFor_"+glyphName)){glyph.style.display="none";glyph.selectObject=null;}}}};GlyphHandler.prototype.action=function(e){if(is.ie){var hoverElement=window.event.srcElement;}else{var hoverElement=e.target;}
utility.dom.stopEvent(is.ie?window.event:e);window.ktmls[this.owner].logic_domSelect(hoverElement.selectObject);GlyphHandler_latehide(this.owner,hoverElement.id);};GlyphHandler.prototype.update=function(e){var found=0;if(is.ie){var el=window.ktmls[this.owner].cw.event.srcElement;}else{var el=e.target;}
while(el){if((el.id+"").match(/^glyphFor_/)){window.clearTimeout(this.hideGlyphTimeout);return;}
for(gname in this.glyphs){var re=new RegExp('^'+gname.toString()+'$','i');if(el.tagName.match(re)){window.clearTimeout(this.hideGlyphTimeout);GlyphHandler_latehide(this.owner,'glyphFor_'+gname.toString());found=1;break;}}
if(found){break;}
if(el.tagName=="HTML"){this.hide();return;}
el=el.parentElement;}
if(typeof this["show"]=="undefined"){GlyphHandler.prototype.show=GlyphHandler_show;}
this.show(el);};GlyphHandler.prototype.dispose=function(){for(var tmp in this.glyphs){this.glyphs[tmp]=null;}
this.glyphs=null;};function glyphhandler_runeach(k){k.glyphhandler=new GlyphHandler(k);Array_push(k.hooks['onsetdisplaymode'],glyphhandler_callback_hide);Array_push(k.hooks['onmouseover'],glyphhandler_callback_update);utility.window.blockInterface();var scripts=[];if(is.ie){scripts=k.edit.all.tags('!');}else{var el=null;var str='';var els=k.edit.evaluate('//comment()',k.edit,null,XPathResult.UNORDERED_NODE_SNAPSHOT_TYPE,null);for(var i=0;i<els.snapshotLength;i++){el=els.snapshotItem(i);scripts.push(el);}}
var i=scripts.length;while(i--){var oneScript=scripts[i];var src=oneScript[is.ie?'data':'textContent'];if(!(/^SCRIPT_TRANSLATOR/.test(src)&&/SCRIPT_TRANSLATOR$/.test(src))){continue;}
src=src.replace(/^SCRIPT_TRANSLATOR/,'').replace(/SCRIPT_TRANSLATOR$/,'');src=WMedia_Translator_translateMarkup(src,'script');if(is.ie){oneScript.outerHTML=src;}else{var r=k.edit.createRange();r.setStartBefore(oneScript);var df=r.createContextualFragment(src);oneScript.parentNode.replaceChild(df.firstChild,oneScript);}}
var anchors=utility.dom.getElementsByTagName(k.edit,"A");var i=anchors.length;while(i--){var oneAnchor=anchors[i];if(!oneAnchor.getAttribute("name")){continue;}
if(oneAnchor.getAttribute("href")){continue;}
var src=oneAnchor.outerHTML;oneAnchor.outerHTML=WMedia_Translator_translateMarkup(src,'anchor');}
var hiddens=utility.dom.getElementsByTagName(k.edit,"input");var i=hiddens.length;while(i--){var hidden=hiddens[i];if(hidden.type!="hidden"){continue;}
var src=hidden.outerHTML;hidden.outerHTML=WMedia_Translator_translateMarkup(src,'hidden');}
utility.window.unblockInterface();}
function glyphhandler_callback_hide(){if(typeof this["glyphhandler"]!="undefined"){this.glyphhandler.hide();}}
function glyphhandler_callback_update(e){if(typeof this["glyphhandler"]!="undefined"){this.glyphhandler.update(e);}}
ORIGINAL_SOURCE_ATTR_NAME="orig";function WMedia_Translator(owner){this.owner=owner;this.tagName="img";this.translated=this.owner.inspectedNode;this.isPlaying=false;this.outerHTML=decodeURIComponent(this.translated.getAttribute("orig"));this.MediaType=this.translated.getAttribute("for").toLowerCase();this.params=WMedia_Translator_parseSource(this.outerHTML,this.MediaType);}
var TRANSLATOR_DESCRIPTORS={anchor:{marker_rx:(/\s*name=?(?:["']?)[^"'>]*(?:["']?)/i),tag_name:"a",exceptions:[/\s?href={1}/i],attribute_parsers:{name:[/<a[^>]*?\s*name=?(?:["']?)([^"'>\s]*)(?:["']?)(:?[\w\W\s\r\n]*?)<\/a>/i],id:[(/<a[^>]*?\s*id=?(?:["']?)([^"'>\s]*)(?:["']?)(:?[\w\W\s\r\n]*?)<\/a>/i)],content:[(/<a[^>]*?>([\w\W\s\r\n]*?)<\/a>/i)]},translator_image:"core/img/anchor.gif"},hidden:{marker_rx:(/\s*type=?(?:["']?)hidden(?:["']?)/i),tag_name:"input",attribute_parsers:{name:[(/<input[^>]*?\s+name=(?:["']?)([^\s"'\/]*)(?:["']?)\s*[^>]*>/i)],id:[(/<input[^>]*?\s+id=(?:["']?)([^\s"'\/]*)(?:["']?)\s*[^>]*>/i)],value:[(/\s+value=("[^"]*"|'[^']*'|[^\s"]*)(?:\s|\/|>)/i)]},translator_image:"core/img/insert_hiddenfield.gif"},script:{marker_rx:(/./),tag_name:"script",attribute_parsers:{content:[(/<script[^>]*?>([\w\W\s\r\n]*?)<\/script>/i)]},translator_image:"core/img/translator_script.gif"}};WMedia_Translator.prototype.untranslate=function(){var tmp=this.owner.edit.createElement("SPAN");var newID=this.owner.getUniqueID();tmp.id=newID;tmp.innerHTML=this.outerHTML;this.translated.parentNode.replaceChild(tmp,this.translated);tmp=this.owner.edit.getElementById(newID);var sel=tmp.firstChild;tmp.firstChild.href="http://";tmp.parentNode.replaceChild(tmp.firstChild,tmp);return sel;};function WMedia_Translator_translateMarkup(str,MediaType){MediaType=(MediaType+"").toLowerCase();var ret='';var transl_desc=TRANSLATOR_DESCRIPTORS[MediaType];var b_rx=new RegExp('<'+transl_desc.tag_name+'\\s?',"i");var e_rx=false;if(Array_indexOf(['input'],transl_desc.tag_name)==-1){e_rx=new RegExp('<\\/'+transl_desc.tag_name+'>',"i");}
var splits=str.split(b_rx);if(splits.length==1&&str==splits[0]&&transl_desc.tag_name!='script'){return str;}
for(var i=0;i<splits.length;i++){if(e_rx){if(!(e_rx.test(splits[i]))){ret+=splits[i];}else{var exclude=false;if(transl_desc.exceptions){for(var j=0;j<transl_desc.exceptions.length;j++){if(transl_desc.exceptions[j].test(splits[i])){exclude=true;break;}}}
if(!exclude&&transl_desc.marker_rx.test(splits[i])){var splits2=splits[i].split(e_rx);var objectTag='<'+transl_desc.tag_name+' '+splits2[0]+'</'+transl_desc.tag_name+'>';var params=WMedia_Translator_parseSource(objectTag,MediaType);var s_id=params["id"]?' id="'+params["id"]+'"':'';var s_width=params["width"]?' width="'+params["width"]+'"':'';var s_height=params["height"]?' height="'+params["height"]+'"':'';var s_align=params["align"]?' align="'+params["align"]+'"':'';var s_hspace=params["hspace"]?' hspace="'+params["hspace"]+'"':'';var s_vspace=params["vspace"]?' vspace="'+params["vspace"]+'"':'';var s_style=transl_desc.translator_logo?"background-color: #cecece; border: 1px inset #999999; ":"margin: 1px;";ret+='<img style="'+s_style+'background-image: url('+KtmlRoot+transl_desc.translator_logo+'); background-repeat: no-repeat; background-position: center; " '+s_id+' for="'+MediaType+'" orig="'+encodeURIComponent(objectTag)+'" src="'+KtmlRoot+transl_desc.translator_image+'" '+s_width+s_height+s_align+s_hspace+s_vspace+' />';if(splits2[1]){ret+=splits2[1];}}else{ret+='<'+transl_desc.tag_name+' '+splits[i];}}}else{var exclude=false;if(transl_desc.exceptions){for(var j=0;j<transl_desc.exceptions.length;j++){if(transl_desc.exceptions[j].test(splits[i])){exclude=true;break;}}}
if(!exclude&&transl_desc.marker_rx.test(splits[i])){var splitFrom=splits[i].match(/^([\w\W]*?)(?:\/?>)([\w\W]*)/i);var objectTag='<'+transl_desc.tag_name+' '+splitFrom[1]+'/>';var params=WMedia_Translator_parseSource(objectTag,MediaType);var s_id=params["id"]?' id="'+params["id"]+'"':'';var s_width=params["width"]?' width="'+params["width"]+'"':'';var s_height=params["height"]?' height="'+params["height"]+'"':'';var s_align=params["align"]?' align="'+params["align"]+'"':'';var s_hspace=params["hspace"]?' hspace="'+params["hspace"]+'"':'';var s_vspace=params["vspace"]?' vspace="'+params["vspace"]+'"':'';ret+='<img '+s_id+' for="'+MediaType+'" orig="'+encodeURIComponent(objectTag)+'" src="'+KtmlRoot+transl_desc.translator_image+'" '+s_width+s_height+s_align+s_hspace+s_vspace+' />'+splitFrom[2];}else{if(splits[i].indexOf(">")<splits[i].indexOf("<")||splits[i].indexOf(">")>=0&&splits[i].indexOf("<")==-1){ret+='<'+transl_desc.tag_name+' '+splits[i];}else{ret+=splits[i];}}}}
return ret;};function WMedia_Translator_parseSource(str,MediaType){MediaType=(MediaType+"").toLowerCase();var m=null;var params={};var paramValue=null;for(var paramName in TRANSLATOR_DESCRIPTORS[MediaType].attribute_parsers){regexes=TRANSLATOR_DESCRIPTORS[MediaType].attribute_parsers[paramName];paramValue="";for(var i=0;i<Math.max(1,regexes.length);i++){m=str.match(regexes[i]);paramValue="";if(m){if(m.length>1){paramValue=m[1];paramValue=paramValue.replace(/(^["']|["']$)/g,'');}else if(regexes.length>1){m=m[0].match(regexes[regexes.length-1]);if(m){paramValue=m[1];break;}}}}
params[paramName.toLowerCase()]=paramValue;}
return params;}
function WMedia_Translator_translate(params,MediaType){MediaType=(MediaType+"").toLowerCase();var uniqueID="uniqid"+parseInt(Math.random()*100000);var str='';switch(MediaType){case"hidden":str='<input type="hidden" name="'+util_defaultValue(params['name'],'')+'" id="'+util_defaultValue(params['id'],'')+'" value="'+util_defaultValue(params['value'],'')+'" />';break;case"script":str='<script>'+util_defaultValue(params['content'],'')+'</script>';break;case"anchor":str='<a name="'+util_defaultValue(params['name'],'')+'"';var pid=util_defaultValue(params['id'],'');str+=(pid=='')?'':(' id="'+pid+'"');str+='>'+util_defaultValue(params['content'],'')+'</a>';break;}
return str;}
function WMedia_Translator_getAttribute(propName){if(typeof(this.params[propName.toLowerCase()])=="undefined"){return null;}else{return this.params[propName.toLowerCase()];}}
function WMedia_Translator_setAttribute(propName,propValue){this.params[propName.toLowerCase()]=propValue;this.outerHTML=WMedia_Translator_translate(this.params,this.MediaType);this.translated.setAttribute("orig",encodeURIComponent(this.outerHTML));}
function WMedia_Translator_removeAttribute(propName){var UNDEFINED;var newParams={};propName=propName.toLowerCase();for(var pn in this.params){if(pn.toLowerCase()!=propName){newParams[pn]=this.params[pn];}}
this.params=newParams;this.outerHTML=WMedia_Translator_translate(this.params,this.MediaType);this.translated.setAttribute("orig",encodeURIComponent(this.outerHTML));}
WMedia_Translator.prototype.translateMarkup=WMedia_Translator_translateMarkup;WMedia_Translator.prototype.parseSource=WMedia_Translator_parseSource;WMedia_Translator.prototype.translate=WMedia_Translator_translate;WMedia_Translator.prototype.getAttribute=WMedia_Translator_getAttribute;WMedia_Translator.prototype.setAttribute=WMedia_Translator_setAttribute;WMedia_Translator.prototype.removeAttribute=WMedia_Translator_removeAttribute;function WordHTML_Translator_translateMarkup(str,MediaType,k){var not_found='';var placeholder_image=ktmls[k].getModuleProperty('media','UploadFolderUrl')+'placeholder.jpg';var placeholder_title=translate("To replace this placeholder, please upload the original image (%s) on server and insert it in the document.",KtmlGlobalLanguage);if(is.ie){str=str.replace(/<v:shape ([^>]*)>[\w\W]*?<v:imagedata ([^>]*)>[\w\W]*?<\/v:imagedata>[\w\W]*?<\/v:shape>/ig,function(a,b,c){var word_image=b.indexOf('/msohtml')>=0;var file_system=c.match(/src="(file:\/\/\/[^"]*)"/i);if(file_system){var w=b.match(/\swidth=\s*\"?([0-9\.]+)([a-z]*)\"?/i);if(w){var mv=parseFloat(w[1]);var mu=w[2];if(mu=="pt"){mv=mv*screen.deviceXDPI/72;}
w='width:'+mv+'px;';}else{w='';}
var h=b.match(/\sheight=\s*\"?([0-9\.]+)([a-z]*)\"?/i);if(h){var mv=parseFloat(h[1]);var mu=h[2];if(mu=="pt"){mv=mv*screen.deviceYDPI/72;}
h='height:'+mv+'px;';}else{h='';}
var title=utility.string.sprintf(placeholder_title,unescape(file_system[1].replace(/file:\/\/\//i,'')));var html='<img src="'+placeholder_image+'" style_preserve="'+w+h+';background-image:url('+not_found+');background-repeat:no-repeat;background-position:center"';html+='alt="'+title+'" title="'+title+'">';return html;}
return a;});}
str=str.replace(/<\/?\w+:\w+[^>]*>/ig,'');if(is.mozilla){str=str.replace(/<!-{0,2}\[[^\]]*\]-{0,2}>/ig,'');str=str.replace(/<img([^>]*?)v:shapes([^>]*?)>/ig,function(a,b,c){var word_image=b.indexOf('/msohtml')>=0;var file_system=b.match(/src="(file:\/\/\/[^"]*)"/i);if(word_image&&file_system){var w=a.match(/width="([^"]*)"/i);var h=a.match(/height="([^"]*)"/i);if(w){w='width:'+w[1]+'px;';}else{w='';}
if(h){h='height:'+h[1]+'px;';}else{h='';}
var title=utility.string.sprintf(placeholder_title,unescape(file_system[1].replace(/file:\/\/\//i,'')));var html='<img src="'+placeholder_image+'" style_preserve="'+w+h+';background-image:url('+not_found+');background-repeat:no-repeat;background-position:center"';html+='alt="'+title+'" title="'+title+'">';return html;}
return a;});}
str=str.replace(/style_preserve=/gi,'style=');return str;}
if(typeof KTML_Translators=="undefined"){KTML_Translators={ANCHOR:"WMedia_Translator",HIDDEN:"WMedia_Translator",WORDHTML:"WordHTML_Translator",SCRIPT:"WMedia_Translator"}}
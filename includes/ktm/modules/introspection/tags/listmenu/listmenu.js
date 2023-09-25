
function init(){utility.window.setModal();getvars=new QueryString();ktml=window.opener.ktmls[getvars.find('counter')];document.title=opener.translate("List Values",ktml.config.UILanguage);document.body.innerHTML=lang_translatepage(document.body.innerHTML,ktml.config.UILanguage,window.opener);st=document.getElementById("txt_text");sv=document.getElementById("txt_value");dt=document.getElementById("sel_text");dv=document.getElementById("sel_value");badd=document.getElementById("btnAdd");bdel=document.getElementById("btnDel");bup=document.getElementById("btnUp");bdown=document.getElementById("btnDown");sel=ktml.inspectedNode;for(i=0;i<sel.options.length;i++){o=sel.options[i];dt.options.add(new Option(o.text,""));dv.options.add(new Option(o.value,""));}
inspect(1);}
function clickedOK(){var ret=[];for(i=0;i<dt.options.length;i++){ret.push([dt.options[i].text,dv.options[i].text]);}
ktml.pis["listmenu"].apply("options",ret);utility.window.close();}
function openHelp(helpStr){ktml.toolbar.showHelp(helpStr);}
function window_keyHandler(e){if(e.keyCode==27){utility.window.close();}}
function addOption(){dt.options.add(new Option("",""));dv.options.add(new Option("",""));dt.selectedIndex=dt.options.length-1;dv.selectedIndex=dv.options.length-1;inspect(1);st.focus();}
function delOption(){var selIdx=dt.selectedIndex;dt.options[selIdx]=null;dv.options[selIdx]=null;selIdx=selIdx==dt.options.length?dt.options.length-1:selIdx;dt.selectedIndex=selIdx;dv.selectedIndex=selIdx;inspect(1);if(!st.disabled){st.focus();st.select();}else{btnAdd.focus();}}
function apply(){var selIdx=dt.selectedIndex;if(selIdx<0){return;}
dv.selectedIndex=selIdx;ot=st.value;ov=sv.value;dt.options[selIdx].text=ot;dv.options[selIdx].text=ov;btnStatus();}
function inspect(who){var selIdx=-1;if(who==1){selIdx=dt.selectedIndex;dv.selectedIndex=selIdx;}else{selIdx=dv.selectedIndex;dt.selectedIndex=selIdx;}
ot="";ov="";if(selIdx>=0){ot=dt.options[selIdx].text;ov=dv.options[selIdx].text;}
st.value=ot;sv.value=ov;btnStatus();}
function moveOption(dir){selIdx=dt.selectedIndex;nextPos=selIdx+dir;if(nextPos<0||nextPos>=dt.options.length){return;}
o1=dt.options[selIdx];o2=dt.options[nextPos];o={text:o1.text,value:o1.value};o1.text=o2.text;o1.value=o2.value;o2.text=o.text;o2.value=o.value;dt.selectedIndex=nextPos;o1=dv.options[selIdx];o2=dv.options[nextPos];o={text:o1.text,value:o1.value};o1.text=o2.text;o1.value=o2.value;o2.text=o.text;o2.value=o.value;dv.selectedIndex=nextPos;btnStatus();}
function btnStatus(){selIdx=dt.selectedIndex;st.disabled=selIdx<0;sv.disabled=selIdx<0;var disable_bADD=dt.options.length==1&&(st.value==""&&sv.value=="");if(disable_bADD){badd.disabled=true;var imgInside=badd.getElementsByTagName('img')[0];imgInside.src="img/plus_disabled.gif";}else{badd.disabled=false;var imgInside=badd.getElementsByTagName('img')[0];imgInside.src="img/plus.gif";}
var disable_bDEL=selIdx==-1;if(disable_bDEL){bdel.disabled=true;var imgInside=bdel.getElementsByTagName('img')[0];imgInside.src="img/minus_disabled.gif";}else{bdel.disabled=false;var imgInside=bdel.getElementsByTagName('img')[0];imgInside.src="img/minus.gif";}
var disable_bUP=dt.selectedIndex<=0;status=disable_bUP;if(disable_bUP){bup.disabled=true;var imgInside=bup.getElementsByTagName('img')[0];imgInside.src="img/up_disabled.gif";}else{bup.disabled=false;var imgInside=bup.getElementsByTagName('img')[0];imgInside.src="img/up.gif";}
var disable_bDOWN=dt.selectedIndex<0||dt.selectedIndex==(dt.options.length-1);if(disable_bDOWN){bdown.disabled=true;var imgInside=bdown.getElementsByTagName('img')[0];imgInside.src="img/down_disabled.gif";}else{bdown.disabled=false;var imgInside=bdown.getElementsByTagName('img')[0];imgInside.src="img/down.gif";}}
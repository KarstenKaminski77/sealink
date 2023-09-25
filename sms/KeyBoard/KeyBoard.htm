<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>KeyBoard Input</TITLE>
<META http-equiv=Content-Type content="text/html; charset=windows-1252">


<STYLE>
BODY {
	SCROLLBAR-FACE-COLOR: #ecd6a6; SCROLLBAR-HIGHLIGHT-COLOR: silver; SCROLLBAR-SHADOW-COLOR: #d8ad49; SCROLLBAR-ARROW-COLOR: #af9538; SCROLLBAR-BASE-COLOR: #e9d096; scrollbar-3d-light-color: #FFFFFF; scrollbar-dark-shadow-color: #80FF00
}
.TextIP {
	FONT-SIZE: 10px; WIDTH: 20px; HEIGHT: 18px
}
.TextIP2 {
	BORDER-RIGHT: silver 0px solid; BORDER-TOP: silver 0px solid; FONT-SIZE: 10px; LEFT: 2px; BORDER-LEFT: silver 0px solid; WIDTH: 200px; BORDER-BOTTOM: silver 0px solid; POSITION: relative; TOP: 2px; HEIGHT: 18px; BACKGROUND-COLOR: silver
}
.Butt {
	FONT-SIZE: 10px; WIDTH: 40px; HEIGHT: 18px
}

.msmhTitle   {position:relative;left:0px;top:0px;font-size:19px;cursor:hand; }
.msmhSTitle   {position:relative;left:0px;top:0px;font-size:16px;cursor:hand; }
.msmhTxtArea {width:550px;font-size:12px;background-color:#F8F4F8;border:solid black 0px;overflow:auto; }
 .title { position:absolute;color:gray;font-size:40px;font-weight:bold;font-family:times, Times New Roman, serif; }

</STYLE>

<SCRIPT language=JavaScript type=text/javascript>
<!--
UpdateJS=0;
   day='30'; month='12'; year='2004';    // Date Updated
   Pday='15'; Pmonth='02'; Pyear='2004'; // Date Posted
UpDateDate='30-12-2004';

//-->
</SCRIPT>

<SCRIPT language=JavaScript src="../Update.js"
type=text/javascript>
<!--
<!---->
</SCRIPT>


<script language="JavaScript" type="text/javascript">
<!--
// KeyBoard Input kbPart1 (30-12-2004)
// by Vic Phillips http://www.vicsjavascripts.org.uk

// Application Notes
// There are three types of KeyBoards available
// Full Querty, Custom & Calender

// The entries typed into the KeyBoard may be 'Entered' into a text box field.
// The Keyboard position relative to an anchor, this anahor would normally be the required text box field.
// Any number of keyboards may be defined on a page.
// Only one keyboard may be displayed at any time.

// Full Querty
// All keys are predefined and has the functionality of a standard QWERTY Keyboard

// Custom
// The required keys are defined in an array.
// As any number of keyboards may be defined each array name is enterd an array 'kbCustomArys'

// Calender
// The calender may be displayed in American or European Format and the month numeric or alpha
// Memo Calender may be displayed as a Basic or Full Calender
// Both versions have the standard Calender display and the ability to set a PopUp Memo for any day
// This Memo may contain a message and be allocated a link
// Clicking the Memo will action the link.
// For the Basic version only, clicking the memo date will also action the link

// All keyboards with the exception of the Basic Calender have the following facilities
// Move - allows the Calender position to be adjusted
// Scroll - allows the Calender to float as the page is scrolled
// Hide   - allows the Calender to be hidden.
// Enter  - allows the selected date to be input to a text box


// All KeyBoards are called by the function
// kbShowKB('*Type*','*Anchor*',*XOffSet,*YOffset*,'*TextBox*')
// where
// *Type* = QUERTY    -  'Q'  (string)
//          Customise -  'C*' where * = the field of 'kbCustomArys' containing the key definition (string)
//          Calender  -  'DF' for Full, 'DB' for Basic (string)
// *Anchor* = the id name of the element to position(anchor) the Memo Calander (string)
// *XOffSet* = The Left(X) offset the Memo Calander from the anchor (digits)
// *YOffSet* = The Top(Y) offset from the Memo Calander the anchor (digits)
// *TextBox* = optional, the id name of the Text Box to enter the date when 'Enter' is depressed (string)
//             if omitted the 'Enter' button will not be displayed.

// The Code
// The code has been divided into five parts
// kbPart1 - Application Notes and Customising Variables
// kbPart2 - Functional Control for all Keyboards.
// kbPart3 - QWERTY KeyBoard
// kbPart4 - Custom KeyBoard
// kbPart5 - Calender KeyBoard Basic & Full

// Parts 2 - 5 are


// All function, variable etc names are prefixed with 'kb'
// to minimise conflicts with other JavaScripts

// Tested with IE6, Opera7, NS7 and Mozilla FireFox

// Customise to Requirement
// Note Use HEX for color values to ensure Opera Compatibility
var kbZIndex=2;                      // the layer of the Calender, normally above othe page elements
var kbBodyBGColor='#C0C0C0';         // the base color of the Calender
var kbKeyBGColor='#E5E5E5';          // the background color of buttons and inactive keys
var kbKeyTxtColor='#000000';         // the text color of keys
var kbKeyHiBGColor='#FFFFFF';        // the background color of active keys
var kbKeyHiTxtColor='#000000';        // the text color of active keys

// Customised Keyboard Customising Variables

// Customised Arrays - Any Name Can be used for the customised array
var CustomAry1=new Array('1','2','3','4','5','6','7','8','9','0','A','B','C','D','E','F','#');
var CustomAry2=new Array('1','2','3','4','5','6','7','8','9','0','');
var CustomAry3=new Array('h','t','p',':','/','.','w','j','s','-','e','x','a','m','p','l','s');

// Enter the  customised array names in cell 0 to .... - To use the array enter the cell number in field 2 of the call
var kbCustomArys=new Array(CustomAry1,CustomAry2,CustomAry3);

// Memo Calender Customising Variables
var kbKeyMemoBGColor='#FF0000';      // the background color of Memo day keys.
var kbKeyMemoTxtCol='#000000';       // the text color of Memo day keys.
var kbMemoPadWidth=200;              // the width of the Memo Pad
var kbMemoPadHeight=50;              // the height of the Memo Pad
var kbMemoPadBGColor='#FFFFCC';      // the background color of the Memo Pad
var kbMemoPadTxtColor='#FF0000';     // the text color of the Memo Pad
var kbMemoPadTxtSize=12;             // the text size of the Memo Pad
var kbMemoPadBorder='solid red 1px'; // the Memo Pad border

var kbDateFormat='MDY';              // 'DMY' = dd mm yyyy, MDY = mm dd yyyy
var kbDateSep='/'                    // the charactor separating dd mm yyy
var kbMonthFormat='Alpha';           // 'Alpha' for Alpha or 'Num' Number Month Display
// kbMemoAry contains Memo details
// It is a nested array each field containg a memo array
// memo array field 0 = the memo data format mm/dd/yyyy
//            field 1 = the memo message
//            field 2 = the memo link (optional)
var kbMemoAry=new Array(
['11/09/2004','Dont forget my Birthday'],
['12/09/2004','Dont forget my Birthday'],
['12/12/2004','too late <br>Click Me to Activate Link','http://www.vicsjavascripts.org.uk']
)

// Do Not Change
var kbCkQWERTY=0;
var kbCkCustom=0;
var kbCkDate=0;

<!---->
</script>

<script language="JavaScript" type="text/javascript">
<!--
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

//-->
</script>


<script language="JavaScript" type="text/javascript">
<!--
// Floating KeyBoard InPut (Full QWERTY) kbPart3.js (30-12-2004)
// by Vic Phillips http://www.vicsjavascripts.org.uk

// No Need to Change

var kbCaplock=0;
var kbshift=0;
var kbNuAry=new Array('1','2','3','4','5','6','7','8','9','0','-','=','[',']',';','\'','#','',',','.','/');
var kbNuSAry=new Array();
var kbNSAry=new Array('!','"','£','$','%','^','&','*','(',')','_','+','{','}',':','@','~','|','&lt;','>','?');
var kbLeAry=new Array('q','w','e','r','t','y','u','i','o','p','a','s','d','f','g','h','j','k','l','z','x','c','v','b','n','m','Space')

// KeyBoard Inner - QWERTY Key Buttons
kbInnF='';
for (kbi=0;kbi<kbNuAry.length;kbi++){
 kbInnF+='<div id="kbNu'+kbi+'"  onclick="javascript:kbTypeIP(\''+kbi+'\',1);" >'+kbNuAry[kbi]+'</div> ';
}
for (kbi1=0;kbi1<kbLeAry.length;kbi1++){
 kbInnF+='<div id="kbLe'+kbi1+'" onclick="javascript:kbTypeIP(\''+kbi1+'\',2);" >'+kbLeAry[kbi1].toUpperCase( )+'</div> ';
}

function kbFCPosition(){
 kbLObj=kbgEBId('kbCapLock');
 kbSObj=kbgEBId('kbShift');
 kbBObj=kbgEBId('kbBack');
 kbCObj=kbgEBId('kbClear');
 kbFCAry=new Array(kbLObj.style,kbSObj.style,kbBObj.style,kbCObj.style);
 for (kb0=0;kb0<kbFCAry.length;kb0++){
  kbFCStyle(kbFCAry[kb0]);
  kbFCAry[kb0].width='42px';
 }
 kbFCAry[2].width=(46+kbBdr)+'px';
 kbFCAry[3].width=(38+kbBdr)+'px';
 for (kb1=0;kb1<kbNuAry.length;kb1++){
  kbNuSAry[kb1]=kbgEBId('kbNu'+kb1).style;
  kbFCStyle(kbNuSAry[kb1]);
 }
 for (kb2=0;kb2<kbLeAry.length;kb2++){
  kbLeAry[kb2]=kbgEBId('kbLe'+kb2).style;
  kbFCStyle(kbLeAry[kb2]);
 }
 for (kbi=0;kbi<12;kbi++){
  kbNuSAry[kbi].left=(2+kbi*18)+'px';
  kbNuSAry[kbi].top=(22)+'px';
 }
 for (kbi=12;kbi<14;kbi++){
  kbNuSAry[kbi].left=(kbi*18-26)+'px';
  kbNuSAry[kbi].top=(39)+'px';
 }
 for (kbi=14;kbi<17;kbi++){
  kbNuSAry[kbi].left=(kbi*18-72)+'px';
  kbNuSAry[kbi].top=(56)+'px';
 }
 for (kbi=17;kbi<kbNuSAry.length;kbi++){
  kbNuSAry[kbi].left=(kbi*18-172)+'px';
  kbNuSAry[kbi].top=(73)+'px';
 }
 kbgEBId(('kbNu17')).style.left=(kbi*18-370)+'px';
 for (kbi1=0;kbi1<10;kbi1++){
  kbLeAry[kbi1].left=(10+kbi1*18)+'px';
  kbLeAry[kbi1].top=(39)+'px';
 }
 for (kbi2=10;kbi2<19;kbi2++){
  kbLeAry[kbi2].left=(kbi2*18-162)+'px';
  kbLeAry[kbi2].top=(56)+'px';
 }
 for (kbi3=19;kbi3<26;kbi3++){
  kbLeAry[kbi3].left=(kbi3*18-316)+'px';
  kbLeAry[kbi3].top=(73)+'px';
 }
 kbLeAry[26].width=(104)+'px';
 kbLeAry[26].left=(82)+'px';
 kbLeAry[26].top=(90)+'px';
 kbFCAry[0].top='90px';
 kbFCAry[0].left='37px';
 kbFCAry[1].top='90px';
 kbFCAry[1].left='189px';
 kbFCAry[2].top='22px';
 kbFCAry[2].left='218px';
 kbFCAry[3].top='39px';
 kbFCAry[3].left='226px';
 kbBAry[0].top='56px';
 kbBAry[0].left='234px';
 kbBAry[0].height=(31+kbBdr)+'px';
 kbBAry[1].top='73px';
 kbBAry[1].width=(24+kbBdr)+'px';
 kbBAry[1].left='206px';
 kbBAry[2].top='90px';
 kbBAry[2].left='234px';
 kbBAry[3].top='90px';
 kbBAry[3].left='2px';
}

function kbFCStyle(kb){
 kb.position='absolute'
 kb.overflow='hidden';
 kb.width=(kbKSz+kbBdr)+'px';
 kb.height=(kbKSz+kbBdr)+'px';
 kb.top='5px';
 kb.color=kbKeyTxtColor;
 kb.backgroundColor=kbKeyBGColor;
 kb.border='solid black 1px';
 kb.textAlign='center';
 kb.fontSize='10px';
 kb.cursor=kbCur;
}

function kbFullKeyBd(){
 kbKBObj.innerHTML=kbQC+kbInnF+kbCom;
 kbString='';
 kbKBW=268+kbBdr;
 kbKBH=108+kbBdr;
 kbKBObjS.width=(kbKBW)+'px';
 kbKBObjS.height=(kbKBH)+'px';
 kbButPos();
 kbFCPosition();
 kbKBObjS.border='solid black 1px';
}

kbCkQWERTY=1;

<!---->
</script>



<script language="JavaScript" type="text/javascript">
<!--
// KeyBoard InPut (Custom) kbPart4 (30-12-2004)
// by Vic Phillips http://www.vicsjavascripts.org.uk


// No Need to Change

function kbCustKeyBd(kb){
 kbString='';
 kbAry=new Array();
 kbAry=kbCustomArys[kb*1];
 kbMakeCustomKeys();
 kbKBObj.innerHTML=kbInnC+kbQC+kbCom;
 kbButNu=Math.floor(kbAry.length/4)+1;
 if (kbButNu<4){ kbButNu=4; }
 kbKBW=((kbButNu*18)+38);
 kbKBH=(110);
 kbKBObjS.width=(kbKBW)+'px';
 kbKBObjS.height=(kbKBH)+'px';
 kbCustKeyStyle();
}

// KeyBoard Inner - Custom Key Buttons
function kbMakeCustomKeys(){
 kbInnC='';
 for (kb=0;kb<kbAry.length;kb++){
  kbInnC+='<div id=kbCust'+kb+' class=kbBut onclick=javascript:kbTypeIP(\''+kb+'\',0); >'+(kbAry[kb])+'</div> ';
 }
}

function kbCustKeyStyle(){
 kbLObj=kbgEBId('kbCapLock');
 kbSObj=kbgEBId('kbShift');
 kbBObj=kbgEBId('kbBack');
 kbCObj=kbgEBId('kbClear');
 kbFCAry=new Array(kbLObj.style,kbSObj.style,kbBObj.style,kbCObj.style);
 for (kb0=0;kb0<kbFCAry.length;kb0++){ kbCKStyle(kbFCAry[kb0]); }
 kbFCAry[0].top='-2000px';
 kbFCAry[1].top='-2000px';
 kbFCAry[2].top='22px';
 kbFCAry[2].left=(kbKBW-36)+'px';
 kbFCAry[3].top='39px';
 kbFCAry[3].left=(kbKBW-36)+'px';
 kbButPos();
 kbBAry[0].top='56px';
 kbBAry[0].left=(kbKBW-36)+'px';
 kbBAry[0].height=(31+kbBdr)+'px';
 kbBAry[1].left=(kbKBW/2-17)+'px';
 kbBAry[1].top='90px';
 kbBAry[2].left=(kbKBW-36)+'px';
 kbBAry[2].top='90px';
 kbBAry[3].left=(2)+'px';
 kbBAry[3].top='90px';
 kbButT=22; kbButL=0; kbButR=kbButNu;
 for (kbi=0;kbi<kbAry.length;kbi++){
  kbCKStyle(kbgEBId(('kbCust'+kbi)).style);
  kbgEBId('kbCust'+kbi).style.left=(kbButL*18+2)+'px';
  kbgEBId('kbCust'+kbi).style.top=(kbButT)+'px';
  kbgEBId('kbCust'+kbi).style.width=(kbKSz+kbBdr)+'px';
  kbButL++;
  if (kbi==kbButR-1){
   kbButL=0;
   kbButR=kbButR+kbButNu;
   kbButT=kbButT+17;
  }
 }
}

function kbCKStyle(kb){
 kb.position='absolute'
 kb.overflow='hidden';
 kb.width=(30+kbBdr)+'px';
 kb.height=(kbKSz+kbBdr)+'px';
 kb.top='90px';
 kb.left='2px';
 kb.color=kbKeyTxtColor;
 kb.backgroundColor=kbKeyBGColor;
 kb.border='solid black 1px';
 kb.textAlign='center';
 kb.fontSize='10px';
 kb.cursor=kbCur;
}

kbCkCustom=1;

<!---->
</script>

<script language="JavaScript" type="text/javascript">
<!--
// Floating KeyBoard (Memo Calender) kbPart5.js (30-12-2004)
// by Vic Phillips http://www.vicsjavascripts.org.uk

var kbCkDate=0;
var kbMemoLk=null;

var kbMPObj=null;
var kbDayAry=new Array('','Mo','Tu','We','Th','Fr','Sa','Su');
var kbOSDay='Su';
if (kbDateFormat=='MDY'){
 kbDayAry=new Array('','Su','Mo','Tu','We','Th','Fr','Sa');
 kbOSDay='Fr';
}
var kbDaysinMonth=new Array(0,31,29,31,30,31,30,31,31,30,31,30,31);
var kbMonthAry=new Array('','January','February','March','April','May','June','July','August','September','October','November','December');
var kbDKAry=new Array();
var kbDKSAry=new Array();


// KeyBoard Inner - Date Control Buttons

kbInnD='';
kbInnD+='<select name="kbDaySel" kb="yes" id="kbDaySel" size="1" onchange="kbDayIP();"  ></select>';
kbInnD+='<select name="kbMonthSel" kb="yes" id="kbMonthSel" size="1" onchange="kbMonthIP();" ></select>';
kbInnD+='<select name="kbYearSel" kb="yes" id="kbYearSel" size="1" onchange="kbYearIP();" ></select>';

for (kb1=1;kb1<=46;kb1++){
 kbDKAry[kb1]=kbgEBId('kbDay'+kb1);
}

for (kbi1=1;kbi1<=46;kbi1++){
 kbInnD+='<div id=kbDay'+kbi1+' style="position:absolute;overflow:hidden;width:'+(kbKSz+kbBdr)+'px;height:'+(kbKSz+kbBdr)+'px;top:5px;color:'+kbKeyTxtColor+';background-color:'+kbKeyBGColor+';border:solid black 1px;text-Align:center;font-size:10px;cursor:'+kbCur+'" onmouseover="javascript:kbKeyOver(this);" onclick="javascript:kbDateIP('+kbi1+');" ></div> ';
}

function kbDateKeyBd(){
 kbKBObj.innerHTML=kbInnD+kbCom;
 if (kbMPObj==null){
 kbMPObj=kbKBObj.cloneNode(false);
  document.getElementsByTagName('BODY')[0].appendChild(kbMPObj);
 }
 kbDSObj=kbgEBId('kbDaySel');
 kbMSObj=kbgEBId('kbMonthSel');
 kbYSObj=kbgEBId('kbYearSel');
 kbCSAry=new Array(kbDSObj.style,kbMSObj.style,kbYSObj.style,kbMPObj.style);
 kbMPObj.onclick=function(){ kbMemoClick(); }
 kbMPObj.onmouseout=function(){ kbMemoHide() }
 kbMPObj.onmouseover=function(){ clearTimeout(kbTO); }
 kbCSAry[3].visibility='hidden';
 kbCSAry[3].zIndex=kbZIndex;
 kbCSAry[3].width=kbMemoPadWidth;
 kbCSAry[3].height=kbMemoPadHeight;
 kbCSAry[3].backgroundColor=kbMemoPadBGColor;
 kbCSAry[3].color=kbMemoPadTxtColor;
 kbCSAry[3].border=kbMemoPadBorder;
 kbCSAry[3].fontSize=kbMemoPadTxtSize;
 kbCSAry[3].textAlign='center';
 kbButNu=7; kbButT=24; kbButL=0;
 kbButR=kbButNu;
 kbKBH=148; kbDKP=19; kbKBW=(kbButNu*kbDKP)+38;
 kbButPos();
 kbKBObjS.width=(kbKBW)+'px';
 kbKBObjS.height=(kbKBH)+'px';
 kbDObj.style.top='126px';
 kbDObj.style.left=(kbKBW-88-5)+'px';
 kbBAry[4].width='88px';
 kbBAry[4].left=(kbKBW-88-(kbBdr+3))+'px';
 if (kbType=='DB'){
  kbDKP=24;
  for (kb0=0;kb0<4;kb0++){ kbBAry[kb0].visibility='hidden'; }
  kbKBW=((kbButNu*kbDKP)-0);
  kbKBObjS.width=(kbKBW)+'px';
  kbBAry[4].width='70px';
  kbBAry[4].left=(kbKBW-70-(kbBdr+3))+'px';
 }
 kbBAry[4].backgroundColor=kbKeyHiBGColor;
 kbBAry[4].color=kbKeyHiTxtColor;
 kbBAry[4].textAlign='center';
 for (kbi=1;kbi<=46;kbi++){
  kbDKAry[kbi]=kbgEBId('kbDay'+kbi);
  kbDKSAry[kbi]=kbDKAry[kbi].style;
  kbDKSAry[kbi].left=(kbButL*kbDKP+2)+'px';
  kbDKSAry[kbi].top=(kbButT)+'px';
  kbDKSAry[kbi].width=(17)+'px';
  kbButL++;
  if (kbi==kbButR){
   kbButL=0;
   kbButR=kbButR+kbButNu;
   kbButT=kbButT+17;
  }
 }
 for (kbi=1;kbi<kbDayAry.length;kbi++){ kbDKAry[kbi].innerHTML=kbDayAry[kbi]; }
 kbBAry[0].top='75px';
 kbBAry[0].left=(kbKBW-32-(kbBdr+2))+'px';
 kbBAry[0].height=(48+kbBdr)+'px';
 kbBAry[1].top='58px';
 kbBAry[1].left=(kbKBW-32-(kbBdr+2))+'px';
 kbBAry[1].width=(30+kbBdr)+'px';
 kbBAry[2].top='24px';
 kbBAry[2].left=(kbKBW-32-(kbBdr+2))+'px';
 kbBAry[3].top='41px';
 kbBAry[3].left=(kbKBW-32-(kbBdr+2))+'px';
 for (kb0=0;kb0<31;kb0++){ kbDSObj.options[kb0]=new Option(kb0+1,kb0+1,true,true); }
 for (kb1=1;kb1<kbMonthAry.length;kb1++){ kbMSObj.options[kb1-1]=new Option(kbMonthAry[kb1],kb1,true,true); }
 for (kb2=1930;kb2<2100;kb2++){ kbYSObj.options[kb2-1930]=new Option(kb2,kb2,true,true); }
 for (kb0=0;kb0<3;kb0++){
  kbCSAry[kb0].position='relative';
  kbCSAry[kb0].top='2px';
  kbCSAry[kb0].fontSize='10px';
 }
 kbCSAry[0].width='38px';
 kbCSAry[1].width='74px';
 kbCSAry[2].width='50px';
 kbCSAry[0].left=(77)+'px';
 kbCSAry[1].left=(-36)+'px';
 kbCSAry[2].left=(2)+'px';
 if (kbDateFormat=='DMY'){
  kbCSAry[0].left=(2)+'px';
  kbCSAry[1].left=(2)+'px';
 }
 kbDate();
}

function kbDate(){
 kbnow = new Date
 kbDay = kbnow.getDate();
 kbMonth = kbnow.getMonth()+1;
// kbMonth ++ ; // Java script counts months from 0
 kbYear = kbnow.getFullYear();
 for (kbi=0;kbi<170;kbi++){
  if (kbYSObj.options[kbi].value==kbYear){ kbYSObj.selectedIndex=kbi; }
 }
 kbDSObj.selectedIndex=kbDay-1;
 kbMSObj.selectedIndex=kbMonth-1;
 kbYearIP();
}

function kbYearIP(){
 kbYear=kbYSObj.options[kbYSObj.selectedIndex].value;
 kb1stDay();
 kbMonthIP();
}

function kbMonthIP(){
 kbMonth=eval(kbMSObj.options[kbMSObj.selectedIndex].value);
 // kbStartDisplay = year day number of month Start
 kbStartDisplay=0;
 for (kbi=0;kbi<kbMonth;kbi++){
  kbStartDisplay+=kbDaysinMonth[kbi];
 }
 if (navigator.userAgent.toLowerCase().indexOf('opera')==-1){ kbDaySelValues(); }
 kbYearMonthDay();
 kbDayIP();
}

function kbDayIP(){
 if (kbDSObj.options[kbDSObj.selectedIndex].value!='0'){
  kbDay=kbDSObj.options[kbDSObj.selectedIndex].value;
 }
 else {
  kbDSObj.selectedIndex=''+(kbDay-1);
 }
 if (kbDay>kbDaysinMonth[kbMonth]){
  kbDay=1;
  kbDSObj.selectedIndex='0';
 }
 kbDateDisplay();
}

function kbDaySelValues(){
 for (kbi=0;kbi<31;kbi++){
  if (kbi<=kbDaysinMonth[kbMonth]-1){
   kbDSObj.options[kbi].value=''+(kbi+1);
   kbDSObj.options[kbi].text=''+(kbi+1);
   if (kbi<9){
    kbDSObj.options[kbi].text='0'+(kbi+1);
   }
  }
  else {
   kbDSObj.options[kbi].value='0';
   kbDSObj.options[kbi].text='';
  }
 }
}

function kb1stDay(){ // find the name of the 1st day of year
 kb=new Date(eval(kbYear))
 kb=kb.getDay();
 kbYMDNAry=new Array('x');
 for (kbi=1;kbi<380;kbi++){
  kbYMDNAry[kbYMDNAry.length]=kbDayAry[kb];
  kb++;
  if (kb==8){ kb=1; }
 }
}

function kbYearMonthDay(){
 kbYMDAry=new Array(24,25,26,27,28,29,30,31);
 // Change Feb Days if Leap Year
 kbDaysinMonth[2]=28;
 if (kbYear/4==Math.floor(kbYear/4)){
  kbDaysinMonth[2]=29;
 }
 // fill kbYMDAry with Month Day Numbers
 for (kbi=1;kbi<=kbDaysinMonth.length;kbi++){
  for (kbj=1;kbj<=kbDaysinMonth[kbi];kbj++){
   kbYMDAry[kbYMDAry.length]=kbj;
  }
 }
 // Add end of year Day Numbers
 for (kbi=1;kbi<=10;kbi++){
  kbYMDAry[kbYMDAry.length]=kbi;
 }
 kbSuOS=0;
 for (kbi=kbStartDisplay;kbi<kbStartDisplay+7;kbi++){
  if (kbYMDNAry[kbi]==kbOSDay){
   kbSuOS=kbi-kbStartDisplay+1;
  }
 }
 kbSuOS=8-kbSuOS;
}

function kbDateDisplay(){
 for (kbi=1;kbi<=46;kbi++){
  kbDKSAry[kbi].fontSize='7pt';
  kbDKSAry[kbi].fontWeight='normal';
  kbDKSAry[kbi].backgroundColor=kbKeyBGColor;
 }
 kbCnt=1;
 for (kbi=kbStartDisplay;kbi<kbStartDisplay+39;kbi++){
  kbDKAry[kbi-kbStartDisplay+1+7].innerHTML=kbYMDAry[kbi+8-kbSuOS];
  if (kbYMDAry[kbi+8-kbSuOS]==1&&kbCnt){
   kbCnt=0;
   for (kbj=kbi;kbj<kbi+kbDaysinMonth[kbMonth];kbj++){
    kbDKAry[kbj-kbStartDisplay+1+7].style.backgroundColor=kbKeyHiBGColor;
    kbDKAry[kbj-kbStartDisplay+1+7].style.color=kbKeyHiTxtColor;
   }
  }
 }
 for (kbi=1;kbi<=46;kbi++){
  if (kbDKAry[kbi].style.backgroundColor.toUpperCase()==kbKeyHiBGColor.toUpperCase()&&kbDKAry[kbi].innerHTML==kbDay){
   kbDKSAry[kbi].fontWeight='bold';
   kbDKSAry[kbi].fontSize='10pt';
  }
  if (kbDKAry[kbi].innerHTML<10){
   kbDKAry[kbi].innerHTML='0'+kbDKAry[kbi].innerHTML;
  }
 }
 for (kbi=1;kbi<=7;kbi++){
  kbDKSAry[kbi].backgroundColor=kbKeyHiBGColor;
  kbDKSAry[kbi].color=kbKeyHiTxtColor;
 }
 kbMonthD=kbMonth;
 if (kbMonthFormat=='Alpha'){
  kbMonthD=kbMonthAry[kbMonth].substring(0,3);
 }
 else if (kbMonth<10){
  kbMonthD='0'+kbMonth;
 }
 kbDayD=kbDay;
 if (kbDay<10){
  kbDayD='0'+kbDay;
 }
 if (kbDateFormat=='DMY'){
  kbString=kbDayD+kbDateSep+kbMonthD+kbDateSep+kbYear;
  kbDObj.innerHTML=kbString;
 }
 else {
  kbString=kbMonthD+kbDateSep+kbDayD+kbDateSep+kbYear;
  kbDObj.innerHTML=kbString;
 }
 kbMemoColor();
}

function kbMemoColor(){
 for (kbi=1;kbi<=46;kbi++){
  for (kb0=0;kb0<kbMemoAry.length;kb0++){
   if (kbDKSAry[kbi].backgroundColor.toLowerCase()==kbKeyHiBGColor.toLowerCase()&&(kbDKAry[kbi].innerHTML)*1==kbMemoAry[kb0][0].split('/')[1]&&kbMemoAry[kb0][0].split('/')[0]==kbMonth&&kbMemoAry[kb0][0].split('/')[2]==kbYear){
    kbDKSAry[kbi].backgroundColor=kbKeyMemoBGColor;
    kbDKSAry[kbi].color=kbKeyMemoTxtCol;
    kbMemoAry[kb0][6]=kbgEBId('kbDay'+kbi);
   }
  }
 }
}

function kbDateIP(kb){
 kbMemoHide()
 if (kb>7&&kbgEBId('kbDay'+kb).style.backgroundColor.toUpperCase()!=kbKeyBGColor.toUpperCase()){
  kbDSObj.selectedIndex=''+(kbgEBId('kbDay'+kb).innerHTML-1);
 }
 if (kbType=='DB'&&kbgEBId('kbDay'+kb).style.backgroundColor.toUpperCase()==kbKeyMemoBGColor.toUpperCase()){
  kbMemoClick();
 }
 kbDayIP();
}

function kbKeyOver(kb){
 kbMemoLk=null;
 if (kb.style.backgroundColor.toLowerCase()==kbKeyMemoBGColor.toLowerCase()){
  kbDay=kb.innerHTML*1;
  kb.style.backgroundColor=kbMemoPadBGColor;
  kb.style.color=kbMemoPadTxtColor;
  kbMemo();
 }
 else { kb.onmouseout=function(){ kbMemoHide(); } }
}

function kbMemo(){
 clearTimeout(kbTO);
 for (kb0=0;kb0<kbMemoAry.length;kb0++){
  if (kbDay==kbMemoAry[kb0][0].split('/')[1]&&kbMonth==kbMemoAry[kb0][0].split('/')[0]&&kbYear==kbMemoAry[kb0][0].split('/')[2]){
   kbMemoLk=kbMemoAry[kb0][2];
   kbDSAry[3].top=(kbTop(kbMemoAry[kb0][6])+15)+'px';
   kbDSAry[3].left=(kbLeft(kbMemoAry[kb0][6])-kbMPObj.offsetWidth+kbDKP)+'px';
   if (kbLeft('kbMemoPad')<10){ kbDSAry[3].left=(10)+'px'; }
   kbDSAry[3].visibility='visible';
   kbMPObj.innerHTML=kbMemoAry[kb0][1];
  }
 }
}

function kbMemoHide(){
 kbCSAry[3].visibility='hidden';
 for (kb0=0;kb0<kbMemoAry.length;kb0++){
  if (kbMemoAry[kb0][6]!=null&&kbMonth==kbMemoAry[kb0][0].split('/')[0]&&kbYear==kbMemoAry[kb0][0].split('/')[2]){
   kbMemoAry[kb0][6].style.backgroundColor=kbKeyMemoBGColor;
   kbMemoAry[kb0][6].style.color=kbKeyMemoTxtCol;
  }
 }
}

function kbMemoClick(){
 if (kbMemoLk){ window.top.location=kbMemoLk; }
 kbCSAry[3].visibility='hidden';
}


kbCkDate=1;

<!---->
</script>

<script language="JavaScript" type="text/javascript">
<!--
// Demo Only

 now = new Date
 Day = 10;
 Month = now.getMonth()+1;
 Year = now.getFullYear();
 ToDay=Month+'/'+Day+'/'+Year;
 Ary=new Array(ToDay,'Demonstration Memo PopUp<br>and Link','http://www.vicsjavascripts.org.uk')
 kbMemoAry[kbMemoAry.length]=Ary;

function CngDateFormat(){
 kbDateFormat='DMY';
 kbDayAry=new Array('','Mo','Tu','We','Th','Fr','Sa','Su');
 kbOSDay='Su';
 if (DemoR[0].checked==true){
  kbDateFormat='MDY';
  kbDayAry=new Array('','Su','Mo','Tu','We','Th','Fr','Sa');
  kbOSDay='Fr';
 }
 for (i=1;i<=8;i++){
  kbDKAry[i].innerHTML=kbDayAry[i];
 }
 kbCSAry[0].left=(77)+'px';
 kbCSAry[1].left=(-36)+'px';
 if (kbDateFormat=='DMY'){
  kbCSAry[0].left=(2)+'px';
  kbCSAry[1].left=(2)+'px';
 }
 kbDate();
}

//-->
</script>


<script language="JavaScript" type="text/javascript">
<!--
// Search for Text sftPart3 (14-12-2004)
// by Vic Phillips http://www.vicsjavascripts.org.uk

// Functional Code for each page to be searched

// Do NOT Change

function sftInitPage(){
 if (navigator.userAgent.toLowerCase().indexOf('opera')>-1||navigator.userAgent.toLowerCase().indexOf('netscape')>-1||navigator.userAgent.toLowerCase().indexOf('mozilla')<0){ return; }
 sftLk=self.location;
 if (parent.location!=sftLk&&parent.document.getElementById('sftPanel')){
  if (document.getElementById('sftsearch')){ sftTarget=document.getElementById('sftsearch'); }
  else { sftTarget=document.getElementsByTagName('BODY')[0]; }
  parent.sftTag(sftTarget);
  sftA=sftTarget.getElementsByTagName('A');
  parent.sftSearch(sftA);
 }
}

//-->
</script>

<script language="JavaScript" type="text/javascript">
<!--
// Demo Only

function StartRoutine(){
 kbShowKB('Q','demoAnchor',-135,10,'TextBox1');
 setTimeout('StartRoutine1()',1000);
}

function StartRoutine1(){
 kbShowKB('C0','demoAnchor',-55,10,'TextBox1');
 setTimeout('StartRoutine2()',1000);
}

function StartRoutine2(){
 kbShowKB('DB','demoAnchor',-75,10,'TextBox1');
 setTimeout('StartRoutine3()',1000);
}

function StartRoutine3(){
 kbShowKB('C1','demoAnchor',-55,10,'TextBox1');
 setTimeout('StartRoutine4()',1000);
}

function StartRoutine4(){
 kbShowKB('DF','demoAnchor',-75,10,'TextBox1');
 setTimeout('StartRoutine5()',1000);
}

function StartRoutine5(){
 kbShowKB('C2','demoAnchor',-55,10,'TextBox1');
 setTimeout('StartRoutine6()',1000);
}

function StartRoutine6(){
 kbShowKB('Q','demoAnchor',-135,10,'TextBox1');
// setTimeout('StartRoutine()',1000);
}



//-->
</script>


<body bgColor="#f8cd76" onLoad="javascript:sftInitPage();StartRoutine();" >
<br>


<div id="sftsearch" >
<CENTER>
<B><FONT size=+2>JavaScript Code for</FONT></B>
<BR>
<br>

<script>
title='KeyBoard Input';
titleY=60;
titleX=145;

if (document.all){ center=Math.round(document.body.clientWidth/2); }
 else if (document.getElementById){ center=Math.round(window.innerWidth/2); }


</script>

<script>
document.write(
'<div class="title" style="top:'+(titleY-1)+'px;left:'+(center-titleX-2)+'px;color:gray;" >'+title+'</div>',
'<div class="title" style="top:'+(titleY+1)+'px;left:'+(center-titleX+2)+'px;color:black;" >'+title+'</div>',
'<div class="title" style="top:'+(titleY)+'px;left:'+(center-titleX)+'px;color:red;" >'+title+'</div>',
'');

</script>


<BR>
<BR>
<FONT color=#000000 size=+1>

<SCRIPT language=JavaScript type=text/javascript>
<!--

if (UpdateJS){
 UpdateDate();
}
else {
 document.write('Off Line Version');
}

<!---->
</SCRIPT>

</FONT>
<BR>
<BR>
<B><FONT size=+2>By Vic Phillips</FONT></B>
&nbsp;&nbsp;&nbsp; <A href="http://vicsjavascripts.org.uk/">
<FONT size=+1>http://www.vicsjavascripts.org.uk/</FONT>
</A>
</CENTER>
<BR>
<br>

<center>
<span style="font-Size:17px;" >
Memo Calender may be displayed as a Basic or Full Calender<br>
<br>
Tested with IE6, NS7, Opera7 and Mozilla FireFox<br>
</span>
<a ref='#' id="demoAnchor"></a>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
There are three types of KeyBoards available<br>
Full Querty, Custom and Calender<br>
<br>
The entries typed into the KeyBoard may be 'Entered' into a text box field.<br>
The Keyboard position relative to an anchor, this anchor would normally be the required text box field.<br>
Any number of keyboards may be defined on a page.<br>
Only one keyboard may be displayed at any time.<br>
<br>
<br>
<table width="400" border=1 bgcolor=#FFCC66>
  <tr height="70">
    <td width="100%" align="center" style="font-size:11px;" >
      <input id="TextBoxC1" style=" BACKGROUND-COLOR: #ffe992;font-size:12px;width:100px;" >
      <input type="button" value="Show KeyBoard" onClick="javascript:kbShowKB('C0','TextBoxC1',-132,-0,'TextBoxC1');"
     style="font-size:12px;width:100px;background-color:#FFCC66;"
      >
      <br>
      <br>
      using: Ary2=new Array('1','2','3','4','5','6','7','8','9','0','');
      </td>
  </tr>
</table>
<br>
<br>
<div class="msmhTitle" >Application Notes and Customising Variables</div>
<br>
<textarea class="msmhTxtArea" id="msmhImplTB" rows="38" wrap="off" style="width:630px;background-Color:#f8cd76;font-Size:12px;" >

&ltscript language="JavaScript" type="text/javascript">
&lt!--
// KeyBoard Input kbPart1 (30-12-2004)
// by Vic Phillips http://www.vicsjavascripts.org.uk

// Application Notes
// There are three types of KeyBoards available
// Full Querty
// Custom
// Calender

// The entries typed into the KeyBoard may be 'Entered' into a text box field.
// The Keyboard position relative to an anchor, this anahor would normally be the required text box field.
// Any number of keyboards may be defined on a page.
// Only one keyboard may be displayed at any time.

// Full Querty
// All keys are predefined and has the functionality of a standard QWERTY Keyboard

// Custom
// The required keys are defined in an array.
// As any number of keyboards may be defined each array name is enterd an array 'kbCustomArys'

// Calender
// The calender may be displayed in American or European Format and the month numeric or alpha
// Memo Calender may be displayed as a Basic or Full Calender
// Both versions have the standard Calender display and the ability to set a PopUp Memo for any day
// This Memo may contain a message and be allocated a link
// Clicking the Memo will action the link.
// For the Basic version only, clicking the memo date will also action the link

// All keyboards with the exception of the Basic Calender have the following facilities
// Move - allows the Calender position to be adjusted
// Scroll - allows the Calender to float as the page is scrolled
// Hide   - allows the Calender to be hidden.
// Enter  - allows the selected date to be input to a text box


// All KeyBoards are called by the function
// kbShowKB('*Type*','*Anchor*',*XOffSet,*YOffset*,'*TextBox*')
// where
// *Type* = QUERTY    -  'Q'  (string)
//          Customise -  'C*' where * = the field of 'kbCustomArys' containing the key definition (string)
//          Calender  -  'DF' for Full, 'DB' for Basic (string)
// *Anchor* = the id name of the element to position(anchor) the Memo Calander (string)
// *XOffSet* = The Left(X) offset the Memo Calander from the anchor (digits)
// *YOffSet* = The Top(Y) offset from the Memo Calander the anchor (digits)
// *TextBox* = optional, the id name of the Text Box to enter the date when 'Enter' is depressed (string)
//             if omitted the 'Enter' button will not be displayed.

// The Code
// The code has been divided into five parts
// kbPart1 - Application Notes and Customising Variables
// kbPart2 - Functional Control for all Keyboards.
// kbPart3 - QWERTY KeyBoard
// kbPart4 - Custom KeyBoard
// kbPart5 - Calender KeyBoard Basic & Full

// Parts 2 - 5 are best as External JavaSripts


// All function, variable etc names are prefixed with 'kb'
// to minimise conflicts with other JavaScripts

// Tested with IE6, Opera7, NS7 and Mozilla FireFox

// Customise to Requirement
// Note Use HEX for color values to ensure Opera Compatibility
var kbZIndex=2;                      // the layer of the Calender, normally above othe page elements
var kbBodyBGColor='#C0C0C0';         // the base color of the Calender
var kbKeyBGColor='#E5E5E5';          // the background color of buttons and inactive keys
var kbKeyTxtColor='#000000';         // the text color of keys
var kbKeyHiBGColor='#FFFFFF';        // the background color of active keys
var kbKeyHiTxtColor='#000000';        // the text color of active keys

// Customised Keyboard Customising Variables

// Customised Arrays - Any Name Can be used for the customised array
var CustomAry1=new Array('1','2','3','4','5','6','7','8','9','0','A','B','C','D','E','F','#');
var CustomAry2=new Array('1','2','3','4','5','6','7','8','9','0','');
var CustomAry3=new Array('h','t','p',':','/','.','w','j','s','-','e','x','a','m','p','l','s');

// Enter the  customised array names in cell 0 to .... - To use the array enter the cell number in field 2 of the call
var kbCustomArys=new Array(CustomAry1,CustomAry2,CustomAry3);

// Memo Calender Customising Variables
var kbKeyMemoBGColor='#FF0000';      // the background color of Memo day keys.
var kbKeyMemoTxtCol='#000000';       // the text color of Memo day keys.
var kbMemoPadWidth=200;              // the width of the Memo Pad
var kbMemoPadHeight=50;              // the height of the Memo Pad
var kbMemoPadBGColor='#FFFFCC';      // the background color of the Memo Pad
var kbMemoPadTxtColor='#FF0000';     // the text color of the Memo Pad
var kbMemoPadTxtSize=12;             // the text size of the Memo Pad
var kbMemoPadBorder='solid red 1px'; // the Memo Pad border

var kbDateFormat='MDY';              // 'DMY' = dd mm yyyy, MDY = mm dd yyyy
var kbDateSep='/'                    // the charactor separating dd mm yyy
var kbMonthFormat='Alpha';           // 'Alpha' for Alpha or 'Num' Number Month Display
// kbMemoAry contains Memo details
// It is a nested array each field containg a memo array
// memo array field 0 = the memo data format mm/dd/yyyy
//            field 1 = the memo message
//            field 2 = the memo link (optional)
var kbMemoAry=new Array(
['11/09/2004','Dont forget my Birthday'],
['12/09/2004','Dont forget my Birthday'],
['12/12/2004','too late &lt;br>Click Me to Activate Link','http://www.vicsjavascripts.org.uk']
)

// Do Not Change
var kbCkQWERTY=0;
var kbCkCustom=0;
var kbCkDate=0;


&lt!---->
&lt/script>



</textarea>
<br>

<br>
<div class="msmhTitle" >The Code</div>
<br>
The code has been divided into five parts
<br>
<br>
<table width="470" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td>
     kbPart1 - Application Notes and Customising Variables
     kbPart2 - Functional Control for all Keyboards.
     kbPart3 - QWERTY KeyBoard
     kbPart4 - Custom KeyBoard
     kbPart5 - Calender KeyBoard Basic & Full

     Parts 2 - 5 are best as External JavaSripts


     <br>
    </td>
  </tr>
</table>
<br>
All functions, variable etc names are prefixed with 'kb'<br>
to minimise conflicts with other JavaScripts.<br>
<br>
<br>
<br>
<INPUT class=Butt  type=button value="DownLoad KeyBoard.zip"
style="LEFT: 0px; WIDTH: 180px; POSITION: relative; BACKGROUND-COLOR: #ffe992"
onclick="javascript:window.top.location='KeyBoard.zip';"
>

<br>



</center>
</div>
<SCRIPT language=JavaScript type=text/javascript>
<!--

// Code Generated with:
// Drop & Slide Menu Generator (07/11/2003)
// by Vic Phillips
// http://homepage.ntlworld.com/vwphillips/

// Drop & Slide Menu Part 1

// Edit to new date when required ;
var DSPageUpDate=UpDateDate;

var DSMenuLayer=100; // Menu Layer
var DSMenuX=10; // Position from Left of Screen
var DSMenuY=10; // Position from Top of Screen
var DSImagePath='../DSMenu/Images/'; // Directory Path for Button Image Files

<!---->
</SCRIPT>

<SCRIPT language=JavaScript src="../DSMenuPart2.js">
<!--

<!---->
</SCRIPT>

<SCRIPT language=JavaScript src="../DSMenuPart3.js">
<!--

<!---->
</SCRIPT>







</body>

</html>




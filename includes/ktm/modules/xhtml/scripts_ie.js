
function ktml_setTextareaSelection(start,end){if(typeof start=='undefined'){start=-1;}
if(typeof end=='undefined'){end=-1;}
this.textarea.focus();var selText=this.edit.selection.createRange();selText.collapse(false);selText.moveStart("character",start);selText.moveEnd("character",end-start);selText.select();}
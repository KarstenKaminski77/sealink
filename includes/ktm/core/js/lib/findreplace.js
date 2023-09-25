// Copyright 2001-2005 Interakt Online. All rights reserved.

function window_keyHandler(e){
	var keyCode = is.ie?e.keyCode:e.charCode;
	if(e.ctrlKey) {
		//stop CTRL+F
		if (is.ie && keyCode in {70:1}) {
			e.cancelBubble = true;
			e.returnValue = false;
			try{ e.keyCode = 90909090; } catch(err){}
			return false;
		}
		if (is.mozilla && keyCode in {102:1}) {
			utility.dom.stopEvent(e);
			return false;
		}
	}

	var evType = e.type;
	if(evType=="keypress" && is.mozilla) {
		if(keyCode == 114){
			return false;
		} else if (e.keyCode == 13 && e.target.id=="strSearch") {
			textFound = findtext(ktml, false, false);
		} else if(e.keyCode == 27) {
			utility.window.close();	
		} else {
			return true
		}
	}
	if(evType=="keydown" && is.ie){
		if(keyCode == 114){
			try {
				e.keyCode = 999999999;
			} catch(e){}
			return false;
		} else if (e.keyCode == 13 && e.srcElement.id=="strSearch") {
			textFound = findtext(ktml, false, false);
			return false;
		} else if(e.keyCode == 27){
			utility.window.close();	
			return false;
		} else {
			return true; 
		}
	}
}

function FindReplaceCheckField() {
	var searchField = document.getElementById("strSearch");
	if (searchField.value.length < 1) {
		alert(opener.translate('Please enter text in the "Find what:" field.', ktml.config.UILanguage));
		searchField.focus();
		return false;
	}
	return true;
}

var wordchar_reg = /\w/;

function FindTextMozillaCode(ktml, fromtop, replaceAll) {
	if (typeof fromtop == "undefined") {
		fromtop = false;
	}
	var searchField = document.getElementById("strSearch");
	if (FindReplaceCheckField()) {
		var searchval = searchField.value;
		var allHTML = ktml.textarea.value;
		if (typeof nextFindStart == "undefined") {
			nextFindStart = 0;
		}

		var mw = document.getElementById("cbMatchWord").checked;
		var mc = document.getElementById("cbMatchCase").checked;

		var iof = nextFindStart;
		var strFound = false;
		
		do {
			strFound = true;
			iof = mc ? allHTML.indexOf(searchval, iof) : allHTML.toLowerCase().indexOf(searchval.toLowerCase(), iof);
			if (iof == -1) {	
				strFound = false;
				nextFindStart = allHTML.length;
				break;
			}
			var pc = iof ? allHTML.substr(iof-1, 1) : "";
			var nc = allHTML.substr(iof + searchval.length, 1);
			//if match whole word only, check the previous and the next character to be non-word characters
			if (mw) {
				strFound = strFound && (pc!="" && !wordchar_reg.test(pc) || pc=="");
				strFound = strFound && (nc!="" && !wordchar_reg.test(nc) || nc=="");
				if (!strFound) {
					iof += searchval.length;
				} else {
					break;
				}
			}
		} while (!strFound)
		
		if (strFound) {
			nextFindStart = iof + searchval.length;
		
			if (!replaceAll) {
				ktml.textarea.focus();
				ktml.textarea.setSelectionRange(iof, nextFindStart);
			}
		}

		if (strFound) {
			return true;
		} else {
			if (fromtop) {
				var startfromtop = false;
			} else {
				var startfromtop = confirm("The text was not found.\nWould you like to start again from the top?");
			}
			if (startfromtop) {
				nextFindStart = 0;
				ktml.textarea.focus();
				ktml.textarea.setSelectionRange(0, 0);

				textFound = findtext(ktml, true, replaceAll);

				if (!textFound) {
					alert(opener.translate("The text was not found.", ktml.config.UILanguage));
					return false;
				} else {
					return true;
				}
			} else {
				return false;
			}
		}
	}
}

function FindTextMozillaRich(ktml, fromtop, replaceAll) {
	if (typeof fromtop == "undefined") {
		fromtop = false;
	}
	if (typeof replaceAll == "undefined") {
		replaceAll = false;
	}
	
	var searchField = document.getElementById("strSearch");
	if (FindReplaceCheckField()) {
		var searchval = searchField.value;
		var strFound = ktml.cw.find(searchval, document.getElementById("cbMatchCase").checked, false, false, document.getElementById("cbMatchWord").checked, false, false);
		if (strFound) {
			return true;
		} else {
			if (fromtop) {
				var startfromtop = false;
			} else {
				selectionRange.collapse(false);
				selectionRange.select();
				var startfromtop = confirm("The text was not found.\nWould you like to start again from the top?");
			}
			if (startfromtop) {
				selectionRange = ktml.edit.createRange();
				selectionRange.setStart(ktml.edit.body, 0);
				selectionRange.collapse(true);
				selectionRange.select();
				
				textFound = findtext(ktml, true, replaceAll);
				if (!textFound) {
					alert(opener.translate("The text was not found.", ktml.config.UILanguage));
					selectionRange.collapse(true);
					selectionRange.select();
					return false;
				} else {
					selectionRange.collapse(true);
					selectionRange.select();
					return true;
				}
			} else {
				selectionRange.collapse(false);
				selectionRange.select();
				return false;
			}
		}
	}

	// There are 7 arguments available:
	// searchString: type string and it's the item to be searched
	// caseSensitive: boolean -- is search case sensitive?
	// backwards: boolean --should we also search backwards?
	// wrapAround: boolean -- should we wrap the search?
	// wholeWord: boolean: should we search only for whole words
	// searchInFrames: boolean -- should we search in frames?
	// showDialog: boolean -- should we show the Find Dialog?
}

function ReplaceTextMozilla(ktml, replaceAll) {
	var searchField = document.getElementById("strSearch");
	if (!FindReplaceCheckField()) {
		return;
	}

	var theReplaceField = document.getElementById("strReplace");
	if (theReplaceField.value == searchField.value || theReplaceField.value != searchField.value && theReplaceField.value.toLowerCase() == searchField.value.toLowerCase() && !document.getElementById("cbMatchCase").checked) {
		return;
	} 

	if (textFound == null) {
		textFound = findtext(ktml, false, replaceAll);
	}

	if (textFound) {
		if (ktml.displayMode == "CODE") {
			var searchval = searchField.value;
			var selLength = searchval.length;
			//escape slash an backslash
			searchval = searchval.replace(/([\\\/]{1})/gi, "\\$1");
			var slength = searchval.length - selLength;

			var oldHTMLValue = ktml.textarea.value;
			ktml.textarea.value = oldHTMLValue.substring(0, nextFindStart - searchField.value.length - slength) + theReplaceField.value + oldHTMLValue.substring(nextFindStart - slength);
			if (!replaceAll) {
				ktml.textarea.setSelectionRange (nextFindStart - (theReplaceField.value.length), nextFindStart - searchField.value.length + theReplaceField.value.length - slength);
				ktml.textarea.focus();
				nextFindStart = nextFindStart - searchField.value.length + theReplaceField.value.length - slength;
			} else {
				nextFindStart = nextFindStart - searchField.value.length + theReplaceField.value.length - slength;
			}
		} else {
			selectionRange = ktml.edit.selection.createRange();
			selectionRange.text = theReplaceField.value;
		}
	}
	textFound = findtext(ktml, false, replaceAll);
	if (textFound && (typeof replaceAll != "undefined") && replaceAll==true) {
		replacetext(ktml, true);
	}
}

function IeSelectionStart(node, rng) {
	var rstart = rng.duplicate();
	rstart.moveToElementText(ktml.textarea);
	rstart.setEndPoint("EndToStart", rng);
	var ret = rstart.text.length;
	return ret;
}

function IeSearchTypeFlags() {
	var retval = 0;
	var matchcase = 0;
	var matchword = 0;
	if (document.getElementById("cbMatchCase").checked) {
		matchcase = 4;
	}
	if (document.getElementById("cbMatchWord").checked) {
		matchword = 2;
	}
	retval = matchcase + matchword;
	return retval;
}

var textFound = null;

function ReplaceTextIe(ktml, replaceAll) {
	var searchField = document.getElementById("strSearch");
	if (!FindReplaceCheckField()) {
		return;
	}
	var theReplaceField = document.getElementById("strReplace");
	if (theReplaceField.value != "" && theReplaceField.value != searchField.value && theReplaceField.value.indexOf(searchField.value)!=-1) {
		//return;
	}
	if (theReplaceField.value == searchField.value || theReplaceField.value != searchField.value && theReplaceField.value.toLowerCase() == searchField.value.toLowerCase() && !document.getElementById("cbMatchCase").checked) {
		return;
	} 

	if (textFound == null) {
		textFound = findtext(ktml, false, replaceAll);
	}

	if (textFound) {
		selectionRange.text = theReplaceField.value;
	}
	textFound = findtext(ktml, false, replaceAll);
	if (textFound && (typeof replaceAll != "undefined") && replaceAll==true) {
		replacetext(ktml, true, replaceAll);
	}
}

function FindTextIe(ktml, fromtop, replaceAll) {
	if (typeof fromtop == "undefined") {
		fromtop = false;
	}
	
	if (typeof replaceAll == "undefined") {
		replaceAll = false;
	}
	var searchField = document.getElementById("strSearch");
	if (FindReplaceCheckField()) {
		var searchval = searchField.value;
		var searchType = IeSearchTypeFlags();
		selectionRange.collapse(false);
		textFound = selectionRange.findText(searchval, 1000000000, searchType);
		if (ktml.displayMode == "CODE") {
			if (textFound) {
				var posFound = IeSelectionStart(ktml.textarea, selectionRange);
				if (posFound>ktml.textarea.value.length) {
					textFound = false;
					ktml.textarea.focus();
					selectionRange = window.topOpener.document.selection.createRange();
					selectionRange.collapse();
				}
			}
		}
		if (textFound) {
			selectionRange.select();
			return true;
		} else {
			if (fromtop) {
				var startfromtop = false;
			} else {
				var startfromtop = confirm("The text was not found.\nWould you like to start again from the top?");
			}
			if (startfromtop) {
				if (ktml.displayMode == "RICH") {
					selectionRange.expand("textedit"); // selects everything
					selectionRange.collapse(true); // collapse at the beginning
					selectionRange.select(); // create the selection
				} else {
					selectionRange.moveToElementText (ktml.textarea);
					selectionRange.collapse(true);
					selectionRange.select();
				}
				textFound = findtext(ktml, true, replaceAll); // start again 
				if (!textFound) {
					alert(opener.translate("The text was not found.", ktml.config.UILanguage));
					selectionRange.select();
					return false;
				} else {
					return true;
				}
			} else {
				selectionRange.select();
				return false;
			}
		}
	}
}


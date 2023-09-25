document.title = opener.document.title;
document.cookie = opener.document.cookie;

slave = opener.slaveKtml
slaveName = slave.name;
//copy the opener ktml configuration variable
dest_config = {};
src = opener.slaveKtml.config_var;
Object_weave_safe(dest_config, src);
dest_config.module_props = {};
for(var pn in src.module_props) {
	dest_config.module_props[pn] = {};
	Object_weave_safe(dest_config.module_props[pn], src.module_props[pn]);
}
dest_config.width = window.screen.availWidth - 5;
dest_config.height = window.screen.availHeight - 45;

window[slaveName + '_config'] = dest_config;
window[slaveName + '_id'] = opener.slaveKtml.id;
$KTML4_GLOBALS = {};
Object_weave_safe($KTML4_GLOBALS, opener.$KTML4_GLOBALS);
$KTML4_GLOBALS.editor_inner_dimensions = 'false';
canceling = true;

function full_init() {
	if (opener && opener.slaveKtml) {
		if (is.ie) {
			document.charset = opener.document.charset;
		}
		utility.window.setModal();
		document.forms[0].innerHTML = '<textarea name="' + slaveName + '" id="' + slaveName + '" style="width:600px; height:80px;display:none;"></textarea>';
		document.getElementById(slaveName).value = opener.slaveKtml.formElement.value;
		window['ktml_' + slaveName] = new ktml(slaveName);
		window['ktml_' + slaveName].fullScreenState = true;
	}
}

function full_terminate() {
	if (opener && opener.slaveKtml) {
		opener.ktmls[opener.slaveKtml.counter].fullScreenState=false;
	}
}
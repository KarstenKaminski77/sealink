// Copyright 2001-2005 Interakt Online. All rights reserved.
function showHelpTopic() {
	utility.window.setModal();
	
	var topic_id = location+"";
	var m = topic_id.match(/topic_id=([^&]*)/i);
	topic_id = m[1]
	var topic_div = document.getElementById("topic_" + topic_id);
	if (!topic_div) {
		alert('Topic "' + topic_id +  '" not found!');
		return;
	}
	var title_div = utility.dom.getElementsByClassName(topic_div, "title");
	document.title = title_div[0].innerText;
	topic_div.style.display = "block";
	document.getElementById('closeme').focus();
}

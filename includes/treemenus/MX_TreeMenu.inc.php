<?php

class MX_TreeMenu {

  var $menuName;
  var $menuItems;
  var $menuOutput;
  
  var $query;
  var $menuPK;
  var $menuFK;
  var $menuNameField;
  var $menuTitleField;
  var $menuLink;
  var $menuURLParameter;
  var $menuTarget;
  var $menuLinkField;
  var $menuTargetField;
  var $menuLevel;
  var $menuHighlight;

  var $isStatic;
  var $isStaticURL;
  var $previousItemLevel;

  function MX_TreeMenu ($menuName, $menuDepthSeparator = ' ') {
    $this->menuName = $menuName;
    $this->menuDepthSeparator = $menuDepthSeparator;
    $this->menuItems = array();
    $this->previousItemLevel = -1;
    $this->menuLevel = -1; // -1 means menuLevel is not taken into account
    $this->menuHighlight = false;

    $this->menuOutput = '';
  }




  /*
  * Static Mx Menu
  */

  function addItem ($itemName, $itemURL, $itemTitle = '', $itemTarget='') {
    // sets static menu renderer
    $this->isStatic = true;
    $level = 0;
    while (strpos($itemName, $this->menuDepthSeparator) === 0) {
      $level++;
      $itemName = substr($itemName, 1);
    }
	$useThisEntry = true;
    if (($level > $this->previousItemLevel) && (($level - $this->previousItemLevel) > 1)) {
		$useThisEntry = false; // ignore this entry because more than 2 spaces from the previous level have been detected
    	$level = $this->previousItemLevel + 1;
    }
	if ($useThisEntry) {
		$myItem = array();
		$myItem['itemName'] = htmlentities(stripslashes(trim($itemName)));
		$myItem['itemURL'] = trim($itemURL);
		$myItem['itemLevel'] = $level;
		$myItem['itemTitle'] = htmlentities(stripslashes(trim($itemTitle)));
		$myItem['itemTarget'] = $itemTarget;
		array_push($this->menuItems, $myItem);
		$this->previousItemLevel = $level;
	}
  }

  /*
  * Dynamic Mx Menu
  */

  function setQuery (&$query) {
    $this->query = $query;
    $this->isStatic = false;
    $this->isStaticURL = true;
  }

  function setPK ($menuPK) {
    $this->menuPK = trim($menuPK);
  }

  function setFK ($menuFK) {
    $this->menuFK = trim($menuFK);
  }

  function setNameField ($menuNameField) {
    $this->menuNameField = trim($menuNameField);
  }

  function setTitleField ($menuTitleField) {
    $this->menuTitleField = trim($menuTitleField);
  }

  function setLink ($menuLink) {
    $this->menuLink = trim($menuLink);
  }

  function setURLParameter ($menuURLParameter) {
    $this->menuURLParameter = trim($menuURLParameter);
  }

  function setTarget($menuTarget) {
    $this->menuTarget = trim($menuTarget);
  }

  function setLinkField ($menuLinkField) {
    $this->isStaticURL = false;
    $this->menuLinkField = trim($menuLinkField);
  }

  function setTargetField ($menuTargetField) {
    $this->menuTargetField = trim($menuTargetField);
  }

  function setLevel ($menuLevel = 3) {
  	// if $menuLevel < 1 => reset menuLevel
    $this->menuLevel = $menuLevel;
  }

  function highlightCurrent ($highlight = false) {
    if ($highlight === true) {
      $this->menuHighlight = true;
    } else {
      $this->menuHighlight = false;
    }
  }


  /*
  * Render wrapper
  */

  function render () {
    if (strlen($this->menuOutput) == 0) {
      if ($this->isStatic) {
        $this->menuOutput = $this->_renderStatic();
      } else {
        $this->menuOutput = $this->_renderDynamic();
      }
    }
    return $this->menuOutput;
  }


  /*
  *   Renderes
  */

  function _renderStatic () {
    $request_uri = $_SERVER['SCRIPT_NAME'];
    $query_string = $_SERVER['QUERY_STRING'];

    if (strlen($query_string) > 0) {
      $request_uri .= '?' . $query_string;
    }

    $return = '<div class="ktcsstree">';
	
    $return .= "<ul>\r\n";
    $nextItemLevel = 0;
    for ($i=0; $i < count($this->menuItems); $i++) {
		  $myItem = $this->menuItems[$i];
		  if ($i < (count($this->menuItems) - 1) ) {
			$nextItemLevel = $this->menuItems[$i + 1]['itemLevel'];
		  } else {
			$nextItemLevel = 0;
		  }
		  $url = $myItem['itemURL'];
		  $itemLi = '<li>';
		  if ($this->menuHighlight &&
			strpos(strtolower($url), strtolower($request_uri)) !== false &&
			strlen(substr(strtolower($url), strpos(strtolower($url), strtolower($request_uri)) + strlen($request_uri))) ==0 ) {
			$itemLi = '<li class="selected">';
			$this->menuHighlight = false;
		  }
		  $return .= $itemLi;
		  $return .= '<a style="font-family:arial; font-size:12px; font-weight:bold; color:#FFFFFF;" href="' . $url . '"';
		  if (strlen($myItem['itemTarget']) > 0) {
			$return .= ' target="' . $myItem['itemTarget'] . '"';
		  }
		  if (strlen($myItem['itemTitle']) > 0) {
			$return .= ' title="' . $myItem['itemTitle'] . '"';
		  }
		  $return .= '>';
		  $return .= $myItem['itemName'];
		  $return .= '</a>';
		  if ($nextItemLevel <= $myItem['itemLevel']) {
			$return .= "</li>\r\n";
		  }
		  if ($nextItemLevel < $myItem['itemLevel']) {
			$repeat = $myItem['itemLevel'] - $nextItemLevel;
			$return .= str_repeat("</ul></li>\r\n", $repeat);
		  }
		  if ($nextItemLevel > $myItem['itemLevel']) {
			$repeat = $nextItemLevel - $myItem['itemLevel'];
			$return .= str_repeat("<ul>\r\n", $repeat);
		  }
	}
    $return .= "</ul>\r\n";
    $return .= "</div>\r\n";
    if (count($this->menuItems) == 0) {
      $return = 'No data to render.';
    }
    return $return;
  }

  function _renderDynamic () {
    $arr = $this->_getUnformatedData();
    $formated_arr = array();
    if (count($arr) > 0) {
      $this->_formatData($formated_arr, $arr, 0, 0, $this->menuLevel);
    }
    $this->menuItems = $formated_arr;
    return $this->_renderStatic();
  }

  function _getUnformatedData () {
    $arr = array();

    if (is_resource($this->query)) {
      include_once('MX_TreeMenu_recordset.class.php');
      $rs = new MX_TreeMenu_recordset($this->query);
    } else {
      $rs = &$this->query;
	  $rs->MoveFirst();
    }

    while (!$rs->EOF) {
      $myItem = array();
      $myItem['itemName'] = htmlentities(stripslashes(trim($rs->Fields($this->menuNameField))));
      $target = '';
      if ($this->isStaticURL) {
        $url = $this->menuLink . $rs->Fields($this->menuURLParameter);
		$target = $this->menuTarget;
      } else {
        $url = $rs->Fields($this->menuLinkField);
        if (isset($this->menuTargetField) && strlen($this->menuTargetField) > 0) {
          $target = $rs->Fields($this->menuTargetField);
        }
      }
      $myItem['itemURL'] = $url;
      $myItem['itemTarget'] = $target;
      $myItem['itemLevel'] = 0;
      $title = '';
      if (isset($this->menuTitleField) && strlen($this->menuTitleField) > 0) {
        $title = htmlentities(stripslashes(trim($rs->Fields($this->menuTitleField))));
      }
      $myItem['itemTitle'] = $title;
      $pk = $rs->Fields($this->menuPK);
      $fk = (int)$rs->Fields($this->menuFK);
      $myArr = array();
      $myArr['fk'] = $fk;
      $myArr['pk'] = $pk;
      $myArr['data'] = $myItem;
      array_push($arr, $myArr);
      $rs->MoveNext();
    }
 	if (!is_resource($this->query)) {
      $rs->MoveFirst();
    }	
    return $arr;
  }

  function _formatData (&$formated_arr, $arr, $key, $level, $maxLevel) {

    foreach ($arr as $value) {
      $pk = $value['pk'];
      $fk = $value['fk'];
      if ($key == $fk) {
        $value['data']['itemLevel'] = $level;
        array_push($formated_arr, $value['data']);
        if ($maxLevel < 0 || $level + 1 < $maxLevel) { // use $maxLevel only if positive
          $this->_formatData($formated_arr, $arr, $pk, $level + 1, $maxLevel);
        }
      }
    }
  }

}


?>
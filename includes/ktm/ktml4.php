<?php
/*
	Copyright (c) InterAKT Online 2000-2005
*/

	$KTML_CMN_uploadErrorMsg = '<strong>File not found:</strong> <br />%s<br /><strong>Please upload the includes/ folder to the testing server.</strong> <br /><a href="http://www.interaktonline.com/error/?error=upload_includes" onclick="return confirm(\'Some data will be submitted to InterAKT. Do you want to continue?\');" target="KTDebugger_0">Online troubleshooter</a>';
	$KTML_CMN_uploadFileList = array(
		'ktml4.config.php',
		'ktml4_functions.inc.php',
		'ktml4_error.class.php',
		'ktml4.class.php',
		'ktml4_sp.class.php',
		'ktml4_security.class.php',
		'../common/KT_common.php',
		'../common/lib/file/KT_File.php',
		'../common/lib/file_upload/KT_FileUpload.php',
		'../common/lib/folder/KT_Folder.php',
		'../common/lib/image/KT_Image.php',
		'../common/lib/resources/KT_Resources.php',
		'../common/lib/shell/KT_Shell.php'
		);

	for ($KTML_CMN_i=0;$KTML_CMN_i<sizeof($KTML_CMN_uploadFileList);$KTML_CMN_i++) {
		$KTML_CMN_uploadFileName = dirname(realpath(__FILE__)). '/' . $KTML_CMN_uploadFileList[$KTML_CMN_i];
		if (file_exists($KTML_CMN_uploadFileName)) {
			require_once($KTML_CMN_uploadFileName);
		} else {
			die(sprintf($KTML_CMN_uploadErrorMsg,$KTML_CMN_uploadFileList[$KTML_CMN_i]));
		}
	}
	
	KT_setServerVariables();
	KT_session_start();
?>
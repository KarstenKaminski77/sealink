<?php
/**
 * Folder where the images' thumbnails are kept
 * @var string
 */
define('KTML4_THUMBNAIL_FOLDER', '.thumbnails/');

/**
 * Image browser thumbnail width (in pixels)
 * @var numeric
 */
define('KTML4_THUMBNAIL_WIDTH', '100');

/**
 * Image browser thumbnail height (in pixels)
 * @var numeric
 */
define('KTML4_THUMBNAIL_HEIGHT', '100');

/**
 * Custom modules list.
 * @var array
 */
$GLOBALS['KTML4_custom_modules'] = array(
  'date' => array(
    'date' => array ('getCurrentDate')
  )
);

/**
 * Global configuration variables list.
 * @var array
 */
$GLOBALS['KTML4_GLOBALS'] = array(
	'allowed_tags_list' => '',
	'denied_tags_list' => 'iframe,frame',
	'add_new_paragraph_on_enter' => 'true'
);
?>

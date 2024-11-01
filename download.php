<?php
/**
 * Plugin Name: Download
 * Description: Downloader plugin is simple downloader plugin.
 * Version: 1.4.2
 * Author: edmon
 * Author URI: 
 * License: GPLv2
 */

/*If this file is called directly, abort.*/
if(!defined('WPINC')) {
    wp_die();
}

if(!defined('YDN_FILE_NAME')) {
    define('YDN_FILE_NAME', plugin_basename(__FILE__));
}

if(!defined('YDN_FOLDER_NAME')) {
    define('YDN_FOLDER_NAME', plugin_basename(dirname(__FILE__)));
}
require_once(dirname(__FILE__).'/com/boot.php');
require_once(YDN_CLASS_PATH.'DownloadInit.php');

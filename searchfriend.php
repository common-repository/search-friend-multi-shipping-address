<?php
/*
Plugin Name: Search Friend - Multi shipping address
Plugin URI: http://www.wordpress.org
Description: WooCommerce SearchFriend Plugin. Multi shipping address friends order
Version: 1.0
Author: SearchFriend
Author URI: http://www.vt-group.vn
License: Under Copy Rigth Licence
*/
global $wpdb, $core_controller;
define('searchfriend_path', plugin_dir_path(__FILE__));
define('searchfriend_url', plugin_dir_url(__FILE__));
require_once(searchfriend_path.'/classes/core_controller.php');
$core_controller = Core_Controller::getInstance('global', $wpdb);

/**
 * Backend requests
 */
if (is_admin()){
}
/**
 * Both loading
 */

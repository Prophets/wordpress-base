<?php
/**
 * Plugin Name: Prophets Base
 * Author: Stijn Huyberechts
 * Text Domain: prophets
 */

define('PROPHETS_PLUGIN_PATH', __DIR__);
define('PROPHETS_PLUGIN_URL', plugin_dir_url(__FILE__));

use Prophets\WPBase\Base;

$base = new Base();
$base->run(WP_ROOT_DIR . '/config/prophets-base');

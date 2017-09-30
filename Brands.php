<?php
/*
Plugin Name: Custom Brands
Plugin URI: http://mkhuda.com
Description: Plugin for Custom Post of some Brands
Version: 1.0
Author: Mkhuda
Author URI: -
License: GPLv2
*/

// Init Plugin Name
require_once 'inc/init_action.php';

// Enqueue all scripts
require_once 'inc/enqueue_action.php';

// Brand Form
require_once 'inc/brand-form_action.php';

// Brand sanitizing and getter
require_once 'inc/brand-sanitize_action.php';

// Init for Product Metabox
require_once 'inc/product-form_action.php';

// Custom templating for Brands and Products Pages
require_once 'inc/templates_action.php';

// Shortcode
require_once 'inc/shortcode_action.php';

// remove the html filtering
remove_filter( 'pre_term_description', 'wp_filter_kses' );
remove_filter( 'term_description', 'wp_kses_data' );

?>

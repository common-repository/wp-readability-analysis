<?php
/*
Plugin Name: WP Readability Analysis
Plugin URI: https://www.salvatoredp.it/wp-readability-analysis
Description: Shows readability analysis of the post or page using Gulpease, Gunning-Fog, Flesch and Flesch-Kincaid readability measurements.
Author: Salvatore Della Pepa
Author URI: https://www.salvatoredp.it
Version: 1.0
License: GPLv2 or later
*/ 


/*
==================================================
                Include Class Core
==================================================
*/
include_once( plugin_dir_path( __FILE__ ) . 'includes/core.php');
/*
==================================================
                Include Functions
==================================================
*/
include_once( plugin_dir_path( __FILE__ ) . 'includes/functions.php');
/*
==================================================
                Add action hooks
==================================================
*/
add_action('add_meta_boxes', 'readanalysis_add_meta_box');


?>
<?php
/**
 * A sample initialization file for TS Shortcode generator
 */

require_once( dirname(__FILE__) . '/bootstrap.php' );

$template = require_once( dirname(__FILE__) . '/sample-template.php' );

new TS_Shortcode_Generator(array(
	'name' => 'ts_sample_shortcode',    // Unique ID of the instance
	'title' => __( 'Sample shortcode generator', 'textdomain' ),    // Title of the popup window
	'author' => 'Theme Stones',     // TinyMCE plugin author
	'website' => 'http://themestones.net/',     // TinyMCE plugin author website
	'icon' => TS_SCG_URL . '/assets/img/icon.png',     // TinyMCE plugin icon. Must use a file path on server not a URI
	'version' => 1.0,     // TinyMCE plugin version. Not so necessary
	'template' => $template,     // Shortcode template array
));
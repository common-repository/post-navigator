<?php
/*
Plugin Name: Post Navigator
Plugin URI: http://plugify.io/plugin/post-navigator
Description: Adds a simple dropdown menu to the Publish post box allowing the user to choose an action to take place upon update, if any
Author: Plugify
Version: 1.3.4
Author URI: http://plugify.io
*/

// Ensure WordPress has been bootstrapped
if( !defined( 'ABSPATH' ) )
	exit;

// Save root plugin path
$path = trailingslashit( dirname( __FILE__ ) );

// Ensure the Post Navigator class has been defined
if( !class_exists( 'Plugify_Post_Navigator' ) )
require_once( $path . 'class.post-navigator.php' );

new Plugify_Post_Navigator();

?>

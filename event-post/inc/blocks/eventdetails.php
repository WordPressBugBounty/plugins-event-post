<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the Gutenberg block.
 *
 * @package event-post
 * @version 6.1.1
 * @since   5.2
 * @see     https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
 */

 /**
 * Registers eventpost/details block
 */

 if ( ! defined( 'ABSPATH' ) ) exit; 

function eventdetails_block_init() {
	$dir_path = EventPost()->plugin_path;

	\register_block_type( $dir_path . '/build/details', array(
		'render_callback' => array(EventPost()->Shortcodes, 'shortcode_single'),
	));
}
add_action( 'wp_loaded', 'eventdetails_block_init' );

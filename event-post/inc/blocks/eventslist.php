<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the Gutenberg block.
 *
 * @package event-post
 * @version 5.9.9
 * @since   5.2
 * @see     https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
 */

 /**
 * Registers eventpost/list block
 */

function eventpost_list_block_init() {
	$dir_path = EventPost()->plugin_path;

	\register_block_type( $dir_path . '/build/list', array(
		'render_callback' => array(EventPost()->Shortcodes, 'shortcode_list'),
	));
}
add_action( 'init', 'eventpost_list_block_init' );

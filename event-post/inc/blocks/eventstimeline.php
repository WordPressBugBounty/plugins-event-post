<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the Gutenberg block.
 *
 * @package event-post
 * @version 5.10.4
 * @since   5.2
 * @see     https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
 */

 /**
 * Registers eventpost/timeline block
 */

function eventpost_timeline_block_init() {
	$dir_path = EventPost()->plugin_path;
	$dir = dirname( __DIR__ );

	wp_register_script('event-post-timeline', plugins_url('/build/timeline/event-timeline.js', $dir), ['jquery'], false, true);

	\register_block_type( $dir_path . '/build/timeline', array(
		'render_callback' => array(EventPost()->Shortcodes, 'shortcode_timeline'),
		'script' => 'event-post-timeline',
	));
}
add_action( 'wp_loaded', 'eventpost_timeline_block_init' );

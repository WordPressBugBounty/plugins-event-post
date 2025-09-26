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
 * Registers eventpost/map block
 */

function eventsmap_block_init() {
	$dir = dirname( __DIR__ );
	$dir_path = EventPost()->plugin_path;
	$block_js = 'build/map/index.js';

	wp_register_script('eventpost-map-editor-script',plugins_url( $block_js, $dir ),array(	'wp-blocks', 'wp-i18n',	'wp-element', 'event-post-map'),filemtime( "$dir_path/$block_js" ));
	wp_add_inline_script('eventpost-map-editor-script', 'var EventPost = EventPost || {}; EventPost.GutParams='.wp_json_encode(array('maptiles' => EventPost()->maps,'map_interactions'=>EventPost()->map_interactions,)), 'before');

	wp_register_style('event-post-map', plugins_url('/build/map/event-map.css', $dir), []);
	wp_register_script('event-post-map', plugins_url( '/build/map/event-map.js', $dir ), ['jquery'], false, true);

	\register_block_type( $dir_path . '/build/map', array(
		'editor_script' => 'eventpost-map-editor-script',
		'script' => 'event-post-map',
		'style' => 'event-post-map',
		'render_callback' => array(EventPost()->Shortcodes, 'shortcode_map'),
	));
}
add_action( 'wp_loaded', 'eventsmap_block_init' );

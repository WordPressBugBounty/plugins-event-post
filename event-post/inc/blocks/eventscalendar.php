<?php
/**
 * Functions to register client-side assets (scripts and stylesheets) for the Gutenberg block.
 *
 * @package event-post
 * @version 5.9.10
 * @since   5.2
 * @see     https://wordpress.org/gutenberg/handbook/blocks/writing-your-first-block-type/#enqueuing-block-scripts
 */

/**
 * Registers eventpost/calendar block
 */
function eventscalendar_block_init() {
	$dir_path = EventPost()->plugin_path;
	$dir = dirname( __DIR__ );

	$block_js = 'build/calendar/index.js';
	wp_register_script('event-post-calendar-block-editor-script',plugins_url( $block_js, $dir ),array('wp-blocks','wp-editor','wp-components','wp-i18n','wp-element',),filemtime( "$dir_path/$block_js" ));

	wp_register_script('event-post-calendar-block-editor-script-front-end', plugins_url( '/build/calendar/event-calendar.js', $dir ), ['jquery'], false, true);
	wp_set_script_translations( 'event-post-calendar-block-editor-script', 'event-post' );
	
	\register_block_type( $dir_path . '/build/calendar', array(
		'editor_script' => 'event-post-calendar-block-editor-script',
		'script' => 'event-post-calendar-block-editor-script-front-end',
		'render_callback' => array(EventPost()->Shortcodes, 'shortcode_cal'),
	));
}
add_action( 'init', 'eventscalendar_block_init' );

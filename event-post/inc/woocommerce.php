<?php
/**
 * Support for WooCommerce
 *
 * @package event-post
 * @version 5.10.3
 * @since   5.8.0
 */

namespace EventPost;

\add_filter( 'woocommerce_product_tabs', '\EventPost\woocommerce_product_tabs' );
\add_filter( 'event-post-rich-result', '\EventPost\woocommerce_rich_result', 10, 2 );

function woocommerce_product_tabs($tabs){
    $event = \EventPost()->retreive();
    if($event && $event->start){
        $tabs['event-post'] = [
            'title' => __('Event', 'event-post'),
            'priority' => 20,
            'callback' => '\EventPost\product_event_tab',
        ];
    }
    return $tabs;
}

function product_event_tab($key, $tab){
    $event = \EventPost()->retreive();
    if($event){
        \EventPost()->load_map_scripts();
        include plugin_dir_path( __DIR__ ) . 'views/product-tab.php';
    }
}

function woocommerce_rich_result($rich_data, $event){
	$gmt_offset = get_option('gmt_offset ');
    if($event->post_type == 'product'){
        $rich_data['offers'] = array(
            '@type'=>'Offer',
            'url'=>get_permalink($event->ID),
            'price'=>\EventPost()->get_price($event),
            'priceCurrency'=>get_woocommerce_currency(),
            'availability'=>'https://schema.org/InStock',
            'validFrom'=>str_replace(' ', 'T', $event->post_date_gmt).$gmt_offset,
        );
    }
    return $rich_data;
}

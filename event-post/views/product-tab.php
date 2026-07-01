
<?php
/**
 * Support for WooCommerce products
 *
 * @package event-post
 * @version 6.0.0
 * @since   5.8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

?>
 <?php echo wp_kses(\EventPost()->get_singledate($event, 'single'), EventPost()->kses_tags); ?>

<?php echo wp_kses(\EventPost()->get_singlecat($event, 'single'), EventPost()->kses_tags); ?>

<?php echo wp_kses(\EventPost()->print_location($event, 'single'), EventPost()->kses_tags); ?>
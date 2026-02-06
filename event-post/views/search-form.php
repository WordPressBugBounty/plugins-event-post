<?php
/**
 * Search form for event posts
 * 
 * @package event-post
 * @version 5.11.0
 * @since   5.0.0
 */
?>
<form class="eventpost-search-form" id="eventpost-search-form-<?php echo esc_attr($list_id); ?>">
    <input type="hidden" name="evenpost_search" value="<?php echo esc_attr($list_id); ?>">
    <?php if($params['q']) : ?>
    <div class="eventpost-search-group">
        <label for="eventpost-search-label-<?php echo esc_attr($list_id); ?>">
            <?php esc_html_e('Name or keywords', 'event-post'); ?>
        </label>
        <input id="eventpost-search-label-<?php echo esc_attr($list_id); ?>" type="search" name="q" value="<?php echo esc_attr($q); ?>"/>
    </div>
    <?php endif; ?>

    <?php if($params['dates']) : ?>
    <div class="eventpost-search-group">
        <label for="eventpost-search-from-<?php echo esc_attr($list_id); ?>">
            <?php esc_html_e('Between:', 'event-post'); ?>
        </label>
        <input id="eventpost-search-from-<?php echo esc_attr($list_id); ?>" type="text" name="from" value="<?php echo esc_attr($from); ?>" class="eventpost-datepicker-simple"/>

        <label for="eventpost-search-to-<?php echo esc_attr($list_id); ?>">
            <?php esc_html_e('to:', 'event-post'); ?>
        </label>
        <input id="eventpost-search-to-<?php echo esc_attr($list_id); ?>" type="text" name="to" value="<?php echo esc_attr($to); ?>" class="eventpost-datepicker-simple"/>
    </div>
    <?php endif; ?>

    <?php if($params['tax']) : ?>
    <label for="eventpost-search-tax-<?php echo esc_attr($list_id); ?>">
        <?php esc_html_e('In:', 'event-post'); ?>
    </label>
    <select id="eventpost-search-tax-<?php echo esc_attr($list_id); ?>" name="tax">
        <option value=""></option>
    </select>
    <?php endif; ?>

    <button class="btn btn-primary" type="submit">
	<?php esc_html_e('Find events', 'event-post'); ?>
    </button>
</form>

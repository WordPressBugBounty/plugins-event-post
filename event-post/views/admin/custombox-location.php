<?php
/**
 * Location custombox
 *
 * @package event-post
 * @version 5.10.3
 * @since   5.3.1
 */
?>
 <div>
  <label for="<?php esc_attr_e($this->META_ATTENDANCE_MODE); ?>">
    <?php esc_attr_e('Attendance Mode:', 'event-post') ?>
    <select name="<?php esc_attr_e($this->META_ATTENDANCE_MODE); ?>" id="<?php esc_attr_e($this->META_ATTENDANCE_MODE); ?>">
      <option value=""></option>
      <?php foreach ($this->attendance_modes as $mode_name => $mode_label): ?>
        <option value="<?php esc_attr_e($mode_name); ?>" <?php selected($mode_name, $event->attendance_mode, true); ?>><?php esc_attr_e($mode_label); ?></option>
    <?php endforeach; ?>
    </select>
  </label>
</div>
<div class="eventpost-misc-pub-section eventpost-location-type-online">
  <p>
    <label for="<?php esc_attr_e($this->META_VIRTUAL_LOCATION); ?>">
    <?php esc_attr_e('Virtual Location:', 'event-post') ?>
      <input type="url" value ="<?php esc_attr_e($event->virtual_location); ?>" name="<?php esc_attr_e($this->META_VIRTUAL_LOCATION); ?>" id="<?php esc_attr_e($this->META_VIRTUAL_LOCATION); ?>" class="widefat"/>
    </label>
  </p>
</div>

<div class="eventpost-misc-pub-section eventpost-location-type-offline">
  <label for="<?php esc_attr_e($this->META_ADD); ?>">
<?php esc_attr_e('Address, as it will be displayed:', 'event-post') ?>
    <textarea name="<?php esc_attr_e($this->META_ADD); ?>" id="<?php esc_attr_e($this->META_ADD); ?>" class="widefat"><?php esc_attr_e($event->address); ?></textarea>
  </label>
</div>

<div id="event_address_searchwrap" class="eventpost-location-type-offline">
  <span class="dashicons dashicons-location eventpost-edit-icon"></span>
  <?php esc_attr_e('GPS coordinates:', 'event-post') ?>
  <a id="event_address_search" title="<?php _e('Search or fill exact coordinates', 'event-post') ?>">
    <?php _e('Search / Edit', 'event-post') ?>
  </a>

  <div class="misc-pub-section" id="event_address_coords">
    <p>
      <span id="eventaddress_result"></span>
    </p>
    <label for="<?php esc_attr_e($this->META_LAT); ?>">
  <?php esc_attr_e('Latitude:', 'event-post') ?>
      <input type="text" value ="<?php esc_attr_e($event->lat); ?>" name="<?php esc_attr_e($this->META_LAT); ?>" id="<?php esc_attr_e($this->META_LAT); ?>" class="widefat"/>
    </label>

    <label for="<?php esc_attr_e($this->META_LONG); ?>">
  <?php esc_attr_e('Longitude:', 'event-post') ?>
      <input type="text" value ="<?php esc_attr_e($event->long); ?>" name="<?php esc_attr_e($this->META_LONG); ?>" id="<?php esc_attr_e($this->META_LONG); ?>" class="widefat"/>
    </label>
    <p>
      <a id="event_address_unsearch" class="button button-small">
        <span class="dashicons dashicons-yes"></span>
        <?php esc_attr_e('Done', 'event-post') ?>
      </a>
    </p>
  </div>
</div>

<div class="eventpost-misc-pub-section eventpost-location-type-offline" id="event-post-map-preview-wrapper">
  <div id="event-post-map-preview" data-marker="<?php esc_attr_e($this->get_marker($event->color)); ?>"></div>
</div>

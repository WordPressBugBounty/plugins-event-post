<?php
/**
 * Date custombox
 *
 * @package event-post
 * @version 5.10.3
 * @since   5.3.1
 */
?>
<div class="eventpost-misc-pub-section">
  <p>
    <label for="<?php esc_attr_e($this->META_STATUS); ?>">
      <?php esc_attr_e('Status:', 'event-post') ?>
      <select name="<?php esc_attr_e($this->META_STATUS); ?>" id="<?php esc_attr_e($this->META_STATUS); ?>">
        <option value=""></option>
        <?php foreach ($this->statuses as $status_name => $status_label): ?>
          <option value="<?php esc_attr_e($status_name); ?>" <?php selected($status_name, $event->status, true); ?>><?php esc_attr_e($status_label); ?></option>
      <?php endforeach; ?>
      </select>
    </label>
  </p>
  <p>
    <label>
      <input type="checkbox" id="event-post-date-all-day" <?php checked( $event->time_start && $event->time_end && date('H:i:s', $event->time_start) == '00:00:00' && date('H:i:s', $event->time_end) == '00:00:00', true, true); ?>>
      <?php esc_attr_e('All day event', 'event-post') ?>
    </label>
  </p>
  <p>
    <span class="dashicons dashicons-calendar eventpost-edit-icon"></span>
    <label for="<?php esc_attr_e($this->META_START); ?>_date">
        <?php esc_attr_e('Begin:', 'event-post') ?>
        <span id="<?php esc_attr_e($this->META_START); ?>_date_human" class="human_date">
            <?php
          if ($event->time_start != '') {
              esc_attr_e( $this->human_date($event->time_start) . (date('H:i', $event->time_start)=='00:00'?'':date(' H:i', $event->time_start)));
            }
            else{
              esc_attr_e('Pick a date','event-post');
            }
            ?>
          </span>
      <input type="<?php esc_attr_e($this->settings['datepicker']=='browser'?'datetime':''); ?>" class="eventpost-datepicker-<?php esc_attr_e($this->settings['datepicker']); ?>" data-lang="<?php esc_attr_e($language); ?>" value="<?php esc_attr_e(substr($start_date,0,16)); ?>" name="<?php esc_attr_e($this->META_START); ?>" id="<?php esc_attr_e($this->META_START); ?>_date"/>
    </label>
  </p>
  <p>
    <span class="dashicons dashicons-calendar eventpost-edit-icon"></span>
    <label for="<?php esc_attr_e($this->META_END); ?>_date">
        <?php esc_attr_e('End:', 'event-post') ?>
        <span id="<?php esc_attr_e($this->META_END); ?>_date_human" class="human_date">
            <?php
            if ($event->time_start != '') {
              esc_attr_e($this->human_date($event->time_end) . (date('H:i', $event->time_end)=='00:00'?'':date(' H:i', $event->time_end)));
            }
            else{
              esc_attr_e('Pick a date','event-post');
            }
            ?>
          </span>
      <input type="<?php esc_attr_e($this->settings['datepicker']=='browser'?'datetime':''); ?>" class="eventpost-datepicker-<?php esc_attr_e($this->settings['datepicker']); ?>" data-lang="<?php esc_attr_e($language); ?>"  value ="<?php esc_attr_e(substr($end_date,0,16)) ?>" name="<?php esc_attr_e($this->META_END); ?>" id="<?php esc_attr_e($this->META_END); ?>_date"/>
    </label>
  </p>
  <p>
    <label for="<?php esc_attr_e($this->META_ORGANIZATION); ?>">
    <?php esc_attr_e('Organization:', 'event-post') ?>
      <input type="text" value="<?php esc_attr_e($event->organization); ?>" name="<?php esc_attr_e($this->META_ORGANIZATION); ?>" id="<?php esc_attr_e($this->META_ORGANIZATION); ?>" class="widefat"/>
    </label>
  </p>
  <p>
    <strong>
      <?php esc_attr_e('Offer:', 'event-post') ?>
    </strong>
    <br>
    <label for="<?php esc_attr_e($this->META_OFFER); ?>_url">
    <?php esc_attr_e('URL:', 'event-post') ?>
      <input type="text" value="<?php esc_attr_e($event->offer['url']); ?>" name="<?php esc_attr_e($this->META_OFFER); ?>_url" id="<?php esc_attr_e($this->META_OFFER); ?>_url" class="widefat"/>
    </label>
    <label for="<?php esc_attr_e($this->META_OFFER); ?>_price">
      <?php esc_attr_e('Price:', 'event-post') ?>
    </label>
    <span style="display: flex; flex-flow: row;">
      <input type="number" value="<?php esc_attr_e($event->offer['price']); ?>" name="<?php esc_attr_e($this->META_OFFER); ?>_price" id="<?php esc_attr_e($this->META_OFFER); ?>_price" class="widefat"/>
      <select name="<?php esc_attr_e($this->META_OFFER); ?>_currency" id="<?php esc_attr_e($this->META_OFFER); ?>_currency" style="width: 45px;">
        <option value=""></option>
        <?php foreach (EventPost()->currencies as $currency=>$symbol): ?>
          <option value="<?php esc_attr_e($currency); ?>" <?php selected($currency, $event->offer['currency'], true); ?>>
            <?php esc_attr_e($currency); ?>
            (<?php esc_attr_e($symbol); ?>)
          </option>
          <?php endforeach; ?>
        </select>
      </span>
  </p>
</div>

  <?php
    $this->icon_color_fields($event->ID, $this->META_COLOR,$eventcolor,$this->META_ICON,$eventicon );
   ?>

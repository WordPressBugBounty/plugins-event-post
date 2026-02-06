<?php
/**
 * @deprecated
 * @package event-post
 */
class EventPost_Search extends WP_Widget {
    var $defaults;
   function __construct() {
  	parent::__construct(false, __( 'Events Search Form', 'event-post' ),array('description'=>__( 'List of future events posts', 'event-post' )));
        $this->defaults = array(
            'numberposts' => '',
            'widgettitle'  => '',
            'cat'   => '',
            'tag' => '',
            'future' => 0,
            'past' => 0,
            'thumbnail' => 0,
            'thumbnail_size' => '',
            'excerpt' => 0,
            'feed' => 0,
            'order' => 'ASC',
            'excerpt' => '',
            'excerpt' => '',
            'excerpt' => '',
        );

   }
   function EventPost_Search(){
       $this->__construct();
   }
   function widget($args, $local_instance) {
        if(!defined('ALLOW_DEPRECATED') || !ALLOW_DEPRECATED) {
            _deprecated_function(__FUNCTION__, '5.9.0', esc_html__('Legacy widgets have been deprecated. Consider using blocks instead.', 'event-post'));
        }
        extract( $args );
	    $instance = wp_parse_args( (array) $local_instance, $this->defaults );

        global $EventPost;
        $numberposts = intval($instance['numberposts']);
        $future = intval($instance['future']);
        $past = intval($instance['past']);
        $cat = sanitize_text_field($instance['cat']);
        $tag = sanitize_text_field($instance['tag']);
        $order = sanitize_text_field($instance['order']);
	
        $events = $EventPost->get_events(
            array(
                'nb'=>$numberposts,
                'future'=>$future,
                'past'=>$past,
                'geo'=>0,
                'cat'=>$cat,
                'tag'=>$tag,
                'order'=>$order
            )
	    );
        if(count($events)==0){
                return;
        }

        echo wp_kses($args['before_widget'], EventPost()->kses_tags);
        if(!empty($instance['widgettitle'])){
            echo wp_kses($args['before_title'], EventPost()->kses_tags);
            echo esc_html($instance['widgettitle']);
            if(!empty($instance['cat']) && $instance['feed']){
                $rss_link = admin_url('admin-ajax.php') . '?action=EventPostFeed&cat=' . $instance['cat'];
                echo' <a href="' . esc_url($rss_link) . '" title="'.esc_attr(sprintf(__('feed of %s', 'event-post'), $instance['cat'])).'"><span class="dashicons dashicons-rss"></span></a>';
            }
            echo wp_kses($args['after_title'], EventPost()->kses_tags);
        }
        $atts=array(
            'events' => $events,
            'class' => 'eventpost_widget',
            'thumbnail' => esc_attr($instance['thumbnail']),
            'thumbnail_size' => esc_attr($instance['thumbnail_size']),
            'excerpt' => esc_attr($instance['excerpt']),
            'order' => $order
        );
        echo wp_kses($EventPost->list_events($atts, 'event_list', 'widget'), EventPost()->kses_tags);
        echo wp_kses($args['after_widget'], EventPost()->kses_tags);
   }

   function update($new_instance, $old_instance) {
       return $new_instance;
   }

   function form($local_instance) {
        global $EventPost;
	$instance = wp_parse_args( (array) $local_instance, $this->defaults );

        $cats = get_categories();
        $tags = get_tags();
        $thumbnail_sizes = $EventPost->get_thumbnail_sizes();
       ?>
       <input type="hidden" id="<?php echo esc_attr($this->get_field_id('widgettitle')); ?>-title" value="<?php echo esc_attr($instance['widgettitle']); ?>">
       <p>
            <label for="<?php echo esc_attr($this->get_field_id('widgettitle')); ?>"><?php esc_html_e('Title','event-post'); ?>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('widgettitle')); ?>" name="<?php echo esc_attr($this->get_field_name('widgettitle')); ?>" type="text" value="<?php echo esc_attr($instance['widgettitle']); ?>" />
            </label>
       </p>

       <p style="margin-top:10px;">
            <label for="<?php echo esc_attr($this->get_field_id('numberposts')); ?>"><?php esc_html_e('Number of posts','event-post'); ?>
                <input id="<?php echo esc_attr($this->get_field_id('numberposts')); ?>" name="<?php echo esc_attr($this->get_field_name('numberposts')); ?>" type="number" value="<?php echo esc_attr($instance['numberposts']); ?>" />
            </label> <?php esc_html_e('(-1 is no limit)','event-post'); ?>
       </p>


       <p style="margin-top:10px;">
            <label for="<?php echo esc_attr($this->get_field_id('future')); ?>">
                    <input id="<?php echo esc_attr($this->get_field_id('future')); ?>" name="<?php echo esc_attr($this->get_field_name('future')); ?>" type="checkbox" value="1" <?php checked($instance['future'], true, true); ?> />
                    <?php esc_html_e('Display future events','event-post'); ?>
            </label>
       </p>
       <p style="margin-top:10px;">
            <label for="<?php echo esc_attr($this->get_field_id('past')); ?>">
                    <input id="<?php echo esc_attr($this->get_field_id('past')); ?>" name="<?php echo esc_attr($this->get_field_name('past')); ?>" type="checkbox" value="1" <?php checked($instance['past'], true, true); ?> />
                    <?php esc_html_e('Display past events','event-post'); ?>
            </label>
       </p>

       <p>
       	<label for="<?php echo esc_attr($this->get_field_id('cat')); ?>">
            <span class="dashicons dashicons-category"></span>
                <?php esc_html_e('Only in:','event-post'); ?>
            <select  class="widefat" id="<?php echo esc_attr($this->get_field_id('cat')); ?>" name="<?php echo esc_attr($this->get_field_name('cat')); ?>">
                <option value=''><?php esc_html_e('All categories','event-post') ?></option>
        <?php foreach($cats as $_cat){ ?>
                <option value="<?php echo esc_attr($_cat->slug); ?>" <?php selected($_cat->slug, $instance['cat'], true); ?>><?php echo esc_html($_cat->cat_name); ?></option>
        <?php  }  ?>
        </select>
       </label>
       </p>

       <p style="margin-top:10px;">
       <label for="<?php echo esc_attr($this->get_field_id('feed')); ?>">
            <input id="<?php echo esc_attr($this->get_field_id('feed')); ?>" name="<?php echo esc_attr($this->get_field_name('feed')); ?>" type="checkbox" value="1" <?php checked($instance['feed'], true, true); ?> />
            <?php esc_html_e('Show category ICS link','event-post'); ?>
       </label>
       </p>
       <hr>

       <p>
       	<label for="<?php echo esc_attr($this->get_field_id('tag')); ?>">
            <span class="dashicons dashicons-tag"></span>
            <?php esc_html_e('Only in:','event-post'); ?>
            <select  class="widefat" id="<?php echo esc_attr($this->get_field_id('tag')); ?>" name="<?php echo esc_attr($this->get_field_name('tag')); ?>">
                <option value=''><?php esc_html_e('All tags','event-post') ?></option>
        <?php foreach($tags as $_tag){?>
                <option value="<?php echo esc_attr($_tag->slug); ?>" <?php selected($_tag->slug, $instance['tag'], true); ?>><?php echo esc_html($_tag->name); ?></option>
        <?php  }  ?>
        </select>
       </label>
       </p>

       <hr>
       <p style="margin-top:10px;">
       <label for="<?php echo esc_attr($this->get_field_id('thumbnail')); ?>">
            <input id="<?php echo esc_attr($this->get_field_id('thumbnail')); ?>" name="<?php echo esc_attr($this->get_field_name('thumbnail')); ?>" type="checkbox" value="1" <?php checked($instance['thumbnail'], true, true); ?> />
            <?php esc_html_e('Show thumbnails','event-post'); ?>
       </label>
       </p>
       <p>
       	<label for="<?php echo esc_attr($this->get_field_id('thumbnail_size')); ?>">
            <?php esc_html_e('Thumbnail size:','event-post'); ?>
       	<select  class="widefat" id="<?php echo esc_attr($this->get_field_id('thumbnail_size')); ?>" name="<?php echo esc_attr($this->get_field_name('thumbnail_size')); ?>">
       		<option value=''></option>
       <?php foreach($thumbnail_sizes as $size){?>
       	    <option value="<?php echo esc_attr($size); ?>" <?php selected($size, $instance['thumbnail_size'], true); ?>><?php echo esc_html($size); ?></option>
       <?php  }  ?>
       </select>
       </label>
       </p>


       <p style="margin-top:10px;">
       <label for="<?php echo esc_attr($this->get_field_id('excerpt')); ?>">
       <input id="<?php echo esc_attr($this->get_field_id('excerpt')); ?>" name="<?php echo esc_attr($this->get_field_name('excerpt')); ?>" type="checkbox" value="1" <?php checked($instance['excerpt'], true, true); ?> />
       <?php esc_html_e('Show excerpt','event-post'); ?>
       </label>
       </p>

       <p>
       	<label for="<?php echo esc_attr($this->get_field_id('order')); ?>">
            <?php esc_html_e('Order:','event-post'); ?>
            <select  class="widefat" id="<?php echo esc_attr($this->get_field_id('order')); ?>" name="<?php echo esc_attr($this->get_field_name('order')); ?>">
                <option value='DESC' <?php selected('DESC', $instance['order'], true); ?>><?php esc_html_e('Reverse chronological','event-post') ?></option>
                <option value='ASC' <?php selected('ASC', $instance['order'], true); ?>><?php esc_html_e('Chronological','event-post') ?></option>
            </select>
       </label>
       </p>
       <?php
   }

}

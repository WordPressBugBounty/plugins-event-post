<?php
/**
 * Implements all shortcodes features
 *
 * @package event-post
 * @version 5.10.2
 * @since   5.0.0
 */

namespace EventPost;

class Shortcodes{

    function __construct() {
        //Shortcodes
        add_shortcode(apply_filters('eventpost_shortcode_slug', 'events_list'), array(&$this, 'shortcode_list'));
        add_shortcode(apply_filters('eventpost_shortcode_slug', 'events_timeline'), array(&$this, 'shortcode_timeline'));
        add_shortcode(apply_filters('eventpost_shortcode_slug', 'events_map'), array(&$this, 'shortcode_map'));
        add_shortcode(apply_filters('eventpost_shortcode_slug', 'events_cal'), array(&$this, 'shortcode_cal'));
        add_shortcode(apply_filters('eventpost_shortcode_slug', 'event_details'), array(&$this, 'shortcode_single'));
        add_shortcode(apply_filters('eventpost_shortcode_slug', 'event_term'), array(&$this, 'shortcode_term'));
        add_shortcode(apply_filters('eventpost_shortcode_slug', 'event_cat'), array(&$this, 'shortcode_cat'));
        add_shortcode(apply_filters('eventpost_shortcode_slug', 'event_search'), array(&$this, 'shortcode_search'));
    }


    /**
     * Shortcode single
     * 
     * @param array $atts
     * 
     * @filter : eventpost_params
     * 
     * @return string
     */
    public function shortcode_single($atts){
        $default_atts = array(
            'attribute' => '',
        );
        $atts = shortcode_atts($default_atts, $atts);
        $attribute = sanitize_text_field($atts['attribute']);
        $event = EventPost()->retreive();
        switch($attribute){
            case 'start':
                return wp_kses(EventPost()->human_date($event->time_start), EventPost()->kses_tags);
            case 'end':
                return wp_kses(EventPost()->human_date($event->time_end), EventPost()->kses_tags);
            case 'address':
                return wp_kses($event->address, EventPost()->kses_tags);
            case 'location':
                return wp_kses(EventPost()->get_singleloc($event, '', 'single'), EventPost()->kses_tags);
            case 'date':
                return wp_kses(EventPost()->get_singledate($event, '', 'single'), EventPost()->kses_tags);
            default:
                return wp_kses(EventPost()->get_single($event, '', 'single'), EventPost()->kses_tags);
        }
    }

    /**
     * Get terms from a shortcode
     *
     * @param array $atts
     *
     * @return string
     */
    public function shortcode_term($atts){
        $defaults = array(
            'tax' => null,
            'term' => null,
            'post_type' => null,
        );
        $atts = shortcode_atts(apply_filters('eventpost_params', $defaults, 'shortcode_term'), $atts);
        $atts = array(
            'tax' => sanitize_text_field($atts['tax']),
            'term' => sanitize_text_field($atts['term']),
            'post_type' => sanitize_text_field($atts['post_type']),
        );
        extract($atts);
        if(false !== $the_term = EventPost()->retreive_term($term, $tax, $post_type)){
             return EventPost()->delta_date($the_term->time_start, $the_term->time_end);
        }
    }
    public function shortcode_cat($_atts){
        $atts = shortcode_atts(array(
            'cat' => null,
        ), $_atts);
        $atts['tax']='category';
        $atts['post_type']='post';
        $atts['term']=$atts['cat'];
        unset($atts['cat']);
        return $this->shortcode_term($atts);
    }

    /**
     * Shortcode to display a list of events
     *
        ### Query parameters

        - **nb=5** *(number of post, -1 is all, default: 5)*
        - **future=1** *(boolean, retreive, or not, events in the future, default = 1)*
        - **past=0** *(boolean, retreive, or not, events in the past, default = 0)*
        - **cat=''** *(string, select posts only from the selected category, default=null, for all categories)*
        - **tag=''** *(string, select posts only from the selected tag, default=null, for all tags)*
        - **geo=0** *(boolean, retreives or not, only events which have geolocation informations, default=0)*
        - **order="ASC"** *(string (can be "ASC" or "DESC")*
        - **orderby="meta_value"** *(string (if set to "meta_value" events are sorted by event date, possible values are native posts fileds : "post_title","post_date" etc...)*

        ### Display parameters

        - **thumbnail=""** * (Bool, default:false, used to display posts thumbnails)*
        - **thumbnail_size=""** * (String, default:"thmbnail", can be set to any existing size : "medium","large","full" etc...)*
        - **excerpt=""** * (Bool, default:false, used to display posts excerpts)*
        - **style=""** * (String, add some inline CSS to the list wrapper)*
        - **type=div** *(string, possible values are : div, ul, ol default=div)*
        - **title=''** *(string, hidden if no events is found)*
        - **before_title="&lt;h3&gt;"** *(string (default &lt;h3&gt;)*
        - **after_title="&lt;/h3&gt;"** *(string (default &lt;/h3&gt;)*
        - **container_schema=""** *(string html schema to display list)*
        - **item_schema=""** *(string html schema to display item)*
     *
     * @param array $_atts
     *
     * @filter eventpost_params
     *
     * @return string
     */
    public function shortcode_list($_atts) {
        $atts = shortcode_atts(apply_filters('eventpost_params', array(
            // Filters
            'nb' => 0,
            'future' => true,
            'past' => false,
            'geo' => 0,
            'cat' => '',
            'tag' => '',
            'tax_name' => '',
            'tax_term' => '',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'title' => '',
            // Display
            'type' => 'div',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
            'thumbnail' => '',
            'thumbnail_size' => '',
            'excerpt' => '',
            'width' => '',
            'height' => 'auto',
            'style' => '',
            'pages' => false,
            'container_schema' => EventPost()->list_shema['container'],
            'item_schema' => EventPost()->list_shema['item'],
            'className' => '',
        ), 'shortcode_list'), $_atts);
        
        if ($atts['container_schema'] != EventPost()->list_shema['container'])
            $atts['container_schema'] = html_entity_decode($atts['container_schema']);
            $atts['container_schema'] = wp_kses($atts['container_schema'], EventPost()->kses_tags);
        
        if ($atts['item_schema'] != EventPost()->list_shema['item']) {
            $atts['item_schema'] = html_entity_decode($atts['item_schema']);
            $atts['item_schema'] = wp_kses($atts['item_schema'], EventPost()->kses_tags);
        }
        
        return EventPost()->list_events($atts, 'event_list', 'shortcode');
    }

    /**
     * Shortcode to display a list of events
     *
        ### Query parameters

        - **nb=5** *(number of post, -1 is all, default: 5)*
        - **future=1** *(boolean, retreive, or not, events in the future, default = 1)*
        - **past=0** *(boolean, retreive, or not, events in the past, default = 0)*
        - **cat=''** *(string, select posts only from the selected category, default=null, for all categories)*
        - **tag=''** *(string, select posts only from the selected tag, default=null, for all tags)*
        - **geo=0** *(boolean, retreives or not, only events which have geolocation informations, default=0)*

        ### Display parameters

        - **excerpt=""** * (Bool, default:false, used to display posts excerpts)*
        - **style=""** * (String, add some inline CSS to the list wrapper)*
        - **type=div** *(string, possible values are : div, ul, ol default=div)*
        - **title=''** *(string, hidden if no events is found)*
        - **before_title="&lt;h3&gt;"** *(string (default &lt;h3&gt;)*
        - **after_title="&lt;/h3&gt;"** *(string (default &lt;/h3&gt;)*
        - **container_schema=""** *(string html schema to display list)*
        - **item_schema=""** *(string html schema to display item)*
     *
     * @param array $_atts
     *
     * @filter eventpost_params
     *
     * @return string
     */
    public function shortcode_timeline($_atts) {
        $atts = shortcode_atts(apply_filters('eventpost_params', array(
            // Filters
            'nb' => 0,
            'future' => true,
            'past' => false,
            'geo' => 0,
            'cat' => '',
            'tag' => '',
            'tax_name' => '',
            'tax_term' => '',
            'title' => '',
            // Display
            'type' => 'div',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
            'excerpt' => '',
            'width' => '',
            'height' => 'auto',
            'style' => '',
            'container_schema' => EventPost()->timeline_shema['container'],
            'item_schema' => EventPost()->timeline_shema['item'],
            'className' => '',
        ), 'shortcode_list'), $_atts);

        if ($atts['container_schema'] != EventPost()->timeline_shema['container'])
            $atts['container_schema'] = html_entity_decode($atts['container_schema']);
        if ($atts['item_schema'] != EventPost()->timeline_shema['item'])
            $atts['item_schema'] = html_entity_decode($atts['item_schema']);
        return EventPost()->list_events($atts, 'event_timeline', 'shortcode');
    }

    /**
     * Shortcode to display a map of events
     * 
     * @param array $_atts
     *
     * @filter eventpost_params
     *
     * @return string
     */
    public function shortcode_map($_atts) {
      
        $ep_settings = EventPost()->settings;
        $defaults = array(
            // Display
            'width' => '',
            'height' => '',
            'tile' => $ep_settings['tile'],
            'pop_element_schema' => '',
            'htmlPop_element_schema' => '',
            'title' => '',
            'before_title' => '<h3>',
            'after_title' => '</h3>',
            'style' => '',
            'thumbnail' => '',
            'thumbnail_size' => '',
            'excerpt' => '',
            'zoom' => '',
            'map_position' => '',
            'latitude' => '',
            'longitude' => '',
            'list' => '0',
            // Filters
            'nb' => 0,
            'future' => true,
            'past' => false,
            'cat' => '',
            'tag' => '',
            'tax_name' => '',
            'tax_term' => '',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'className' => '',
        );
            // UI options
        foreach(EventPost()->map_interactions as $int_key=>$int_name){
            $defaults[$int_key]=true;
        }
            // - UI options
        foreach(EventPost()->map_interactions as $int_key=>$int_name){
            $defaults['disable_'.strtolower($int_key)]=false;
        }

        $atts = shortcode_atts(apply_filters('eventpost_params', $defaults, 'shortcode_map'), $_atts);
            // UI options
        foreach(EventPost()->map_interactions as $int_key=>$int_name){
            if($atts['disable_'.strtolower($int_key)]==true){
                $atts[$int_key]=false;
            }
            unset($atts['disable_'.strtolower($int_key)]);
        }
        $atts['geo'] = 1;
        $atts['type'] = 'div';
        return EventPost()->list_events($atts, 'event_geolist', 'shortcode'); //$nb,'div',$future,$past,1,'event_geolist');
    }

    /**
     * Shortcode to display a calendar of events
     * 
     * @param array $_atts
     *
     * @filter eventpost_params
     *
     * @return string
     */
    public function shortcode_cal($_atts) {
        $defaults = array(
            'date' => date('Y-n'),
            'cat' => '',
            'mondayfirst' => 0, // 1 : weeks starts on monday
            'display_title' => 0,
            'datepicker' => 1,
            'tax_name' => '',
            'tax_term' => '',
            'thumbnail' => '',
            'className' => '',
        );
        $atts = shortcode_atts(apply_filters('eventpost_params', $defaults, 'shortcode_cal'), $_atts);
        $atts = array(
            'date' => sanitize_text_field($atts['date']),
            'cat' => sanitize_text_field($atts['cat']),
            'mondayfirst' => intval($atts['mondayfirst']),
            'display_title' => intval($atts['display_title']),
            'datepicker' => intval($atts['datepicker']),
            'tax_name' => sanitize_text_field($atts['tax_name']),
            'tax_term' => sanitize_text_field($atts['tax_term']),
            'thumbnail' => sanitize_text_field($atts['thumbnail']),
            'className' => esc_attr($atts['className']),
        );
        extract($atts);
        // $date can only be a string passed to strtotime, eg: Y-n, - 1 month, +2 weeks, etc...
        // So we only allow digits, letters, spaces, and the characters - and +
        if (preg_match('/[^a-zA-Z0-9\-\+ ]/', $date)) {
            $date = date('Y-n');
        }
        return '<div
                    class="eventpost_calendar ' . esc_attr($className) . '"
                    data-tax_name="' . esc_attr($tax_name) . '"
                    data-tax_term="' . esc_attr($tax_term) . '"
                    data-cat="' . esc_attr($cat) . '"
                    data-date="' . esc_attr($date) . '"
                    data-mf="' . esc_attr($mondayfirst) . '"
                    data-dp="' . esc_attr($datepicker) . '"
                    data-title="'. esc_attr($display_title) .'"
                >'
            . wp_kses_post(EventPost()->calendar($atts))
        . '</div>';
    }

    /**
     * Shortcode for search
     *
     * @param type $_atts
     * 
     * @return type
     */
    public function shortcode_search($_atts){
        return EventPost()->search($_atts);
    }

}

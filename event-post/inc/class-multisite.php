<?php
/**
 * Multisite management
 * 
 * @package event-post
 * @version 5.10.3
 * @since   4.3
 */

 namespace EventPost;

if(is_multisite()){
    global $Multisite;
    $Multisite=new Multisite();
}

/**
 * Event Post multisite support
 * Extends Event post to multisite networks
 */
class Multisite{
    function __construct(){
        add_filter('eventpost_params',array(&$this,'params'),1,2);
        add_filter('eventpost_get',array(&$this,'get'),1,3);
    }

    public function Multisite(){
        $this->__construct();
    }

    private function no_use(){
        __('Extends Event post to multisite networks','event-post');
        __('Event Post multisite support','event-post');
    }

    /**
     * Adds "blogs" to params via `eventpost_params` filter
     *
     * @param array $param
     * @param string $context
     * 
     * @see eventpost_params
     * 
     * @return array
     */
    public function params($param, $context){
        $param['blogs']='';
        return $param;
    }

    /**
     * Fetches events from all needed blogs
     * 
     * @see eventpost_multisite_get
     * @see eventpost_multisite_blogids
     * 
     * @return array of events
     */
    public function get($empty,$arg,$requete){
    	$is_result=apply_filters('eventpost_multisite_get',$empty,$arg,$requete);
		if($is_result!=$empty)
			return $is_result;

        if(!is_array($arg) || !isset($arg['blogs']) || ''==$arg['blogs'])
            return $empty;
        //print_r($arg);
        $blog_ids=array();
        if($arg['blogs']=='all'){
            $blogs=get_sites(array('limit'=>0));
            foreach ($blogs as $blog) {
               $blog_ids[]=$blog->blog_id;
            }
        }
        elseif(!empty($arg['blogs'])){
            $blog_ids=apply_filters('eventpost_multisite_blogids',explode(',',$arg['blogs']));
        }
        else{
            return $empty;
        }


        global $EventPost,$wpdb;

        $all_events=array();
        foreach ($blog_ids as $blog_id) {
            switch_to_blog($blog_id);
            $query = new WP_Query($requete);
            $events =  $wpdb->get_col($query->request);
            foreach($events as $k=>$post){
                $event = $EventPost->retreive($post);
                $aEvent = (array) $event;
                $all_events[($arg['orderby']!='meta_value' && isset($aEvent[$arg['orderby']])?$aEvent[$arg['orderby']]:$event->time_start).'-'.$blog_id.'-'.$event->ID]=$event;
            }
            restore_current_blog();
        }
	if($arg['order']!=''){
	    $sort = $arg['order']=='DESC'?'krsort':'ksort';
	    $sort($all_events);
	}
        return $all_events;

    }
}

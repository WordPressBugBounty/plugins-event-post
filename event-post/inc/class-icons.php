<?php
/**
 * Icons
 *
 * @package event-post
 * @version 5.10.1
 * @since   5.4
 */

namespace EventPost;

class Icons{
  public $icons;
  public $icons_html_entities;
  public $transient_name;
  public $transient_name_html_entities;

  function __construct(){
    $this->icons = [];
    $this->icons_html_entities = [];
    $this->transient_name = "dashicons_list";
    $this->transient_name_html_entities = "dashicons_list_html_entities";
    add_action('init', array(&$this,'init_icons'));
  }
  function init_icons() {
    if(get_transient($this->transient_name) && get_transient($this->transient_name_html_entities)){
      $this->icons = get_transient($this->transient_name);
      $this->icons_html_entities = get_transient($this->transient_name_html_entities);
    }else{
      $css_file = file_get_contents(ABSPATH. 'wp-includes/css/dashicons.css');
      $css_array = explode('}',$css_file);
      foreach ($css_array as $rule) {
        $matches = [];
        if(preg_match(  "/\.dashicons-((?:\w+-?)+\w+):before {\s*content:\s*[\"\']\\\(\w+)[\"\'];/m",
                        $rule,
                        $matches))
        {
          if(isset($matches[1]) &&  isset($matches[2])){
            $this->icons[$matches[1]] = $matches[2];
            $this->icons_html_entities[$matches[1]] = '&#x'.$matches[2].'; '.$matches[1];
          }
        }
      }
      set_transient($this->transient_name , $this->icons, MONTH_IN_SECONDS );
      set_transient($this->transient_name_html_entities , $this->icons_html_entities, MONTH_IN_SECONDS );
    }
  }

}

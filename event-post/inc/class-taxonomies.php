<?php
/**
 * Taxonomies
 * 
 * @package event-post
 * @version 5.11.0
 * @since   5.6
 */

namespace EventPost;

class Taxonomies{
  private $META_COLOR = 'taxonomy_color';
	private $META_ICON = 'taxonomy_icon';
	private $dashicons = 'taxonomy_icon';


  function __construct($dashicons){
    $this->dashicons = $dashicons;
  }


  function add_fields_to_taxonomies($posttypes){
    $taxonomies_done = [];
		foreach($posttypes as $posttype){
			$taxonomies = get_object_taxonomies( $posttype, 'names' );
      foreach ($taxonomies as $taxonomy) {
        if(!isset($taxonomies_done[$taxonomy])){
          add_action( 'created_'.$taxonomy, array($this, 'save_taxonomy_fields') );
          add_action( 'edited_'.$taxonomy, array($this, 'save_taxonomy_fields') );
          add_action( $taxonomy.'_add_form_fields', array($this, 'taxonomy_fields_new'), 10 );
          add_action( $taxonomy.'_edit_form_fields', array($this, 'taxonomy_fields_edit'), 10, 2 );
          add_filter( "manage_edit-".$taxonomy."_columns", array($this, 'taxonomy_custom_column_header'), 10);
          add_action( "manage_".$taxonomy."_custom_column", array($this, 'taxonomy_custom_column_content'), 10, 3);
          $taxonomies_done[$taxonomy] = $taxonomy;
        }
      }
		}
  }
  function taxonomy_fields_new( $taxonomy ) { // Function has one field to pass – Taxonomy
    $fields = $this->get_fields(false);
    foreach($fields as $field => $components){
      ?>
      <div class="form-field event-color-section <?php echo esc_attr($field) ?>-wrap">
        <?php echo wp_kses($components['label'], EventPost()->kses_tags)?>
        <?php echo wp_kses($components['field'], EventPost()->kses_tags)?>
      	<p><?php echo esc_html($components['desc']) ?></p>
      </div>
      <?php
    }
  }
  function get_fields($term){
    $color = "";
    $icon = "";
    if($term){
      $color = get_term_meta( $term->term_id, $this->META_COLOR, true );
      $icon = get_term_meta( $term->term_id, $this->META_ICON, true );
    }
    $options = '<option value="">'.__('None', 'event-post').'</option>';
    foreach($this->dashicons->icons as $class => $unicode){
      $options .= '<option value="'.$class.'" '.selected($icon, $class, false).'>&#x'.$unicode.'; '.$class.'</option>';
    }
    $fields = [
      $this->META_COLOR => [
        "label" => '<label for="taxonomy_'.$this->META_COLOR.'">' .__('Event Color:', 'event-post').'</label>',
        "field" => '<input class="color-field-taxo eventpost-colorpicker" type="text" name="'.$this->META_COLOR.'" value="'.$color.'" id="taxonomy_'.$this->META_COLOR.'"/>',
        "desc"  => __('This color will be applied to all events of this category on the map, if they do not have their own color, and it will add the color to the term where it is shown on events.', 'event-post')
      ],
      $this->META_ICON => [
        "label" => '<label for="taxonomy_'.$this->META_ICON.'">' .__('Map Icon:', 'event-post').'</label>',
        "field" => '<select style="font-family : dashicons;" class="eventpost-iconpicker" name="'.$this->META_ICON.'">'.$options.'</select>
        <div class="custom-marker-container">
  				<p>'.__('An image was found in your custom folder for this color', 'event-post').'<span class="color-hex"></span> </p>
  				<img src="" class="image-marker">
  			</div>',
        "desc"  => __('This color will be applied to all events of this category on the map, if they do not have their own color, and it will add the color to the term where it is shown on events.', 'event-post')
      ],
    ];
    return $fields;
  }
  function taxonomy_fields_edit( $term, $taxonomy ) {
    $fields = $this->get_fields($term);
    foreach($fields as $field => $components){
      ?>
      <tr class='form-field <?php echo esc_attr($field) ?>-wrap'>
        <th scope='row' valign='top'>
          <?php echo wp_kses($components['label'], EventPost()->kses_tags)?>
        </th>
        <td>
          <?php echo wp_kses($components['field'], EventPost()->kses_tags); ?>
          <p class='description'><?php echo esc_html($components['desc']) ?></p>
        </td>
      </tr>
      <?php
    }
  }

  function save_taxonomy_fields( $term_id ) {
    if( ! empty( $_POST[$this->META_COLOR] ) ) {
      update_term_meta( $term_id, $this->META_COLOR, sanitize_hex_color( $_POST[$this->META_COLOR] ) );
    }
    if( ! empty( $_POST[$this->META_ICON] ) ) {
      update_term_meta( $term_id, $this->META_ICON, sanitize_html_class( $_POST[$this->META_ICON] ) );
    }

  }

  function taxonomy_custom_column_header( $columns ){
  	$columns['event_post_style'] = __('Event Post Style',  'event-post');
  	return $columns;
  }
  function taxonomy_custom_column_content( $content,$column_name,$term_id ){
  	if ($column_name === 'event_post_style') {
      $icon = $this->get_taxonomy_icon($term_id, "location");
      $color = $this->get_taxonomy_color($term_id, "#000000");
  		echo '<span class="dashicons dashicons-'.esc_attr($icon).'" style="color : #'.esc_attr($color).'"></span>';
  	}
  	return $content;
  }

  function get_taxonomy_icon($term_id, $default = false){
    $icon = get_term_meta( $term_id, $this->META_ICON, true );
    if($icon && !empty($icon)){
      return $icon;
    }else{
      $ancestors = get_ancestors($term_id, '', 'taxonomy' );
      if($ancestors){
        foreach($ancestors as $ancestor){
          $icon = get_term_meta( $ancestor, $this->META_ICON, true );
          if($icon && !empty($icon)){
            return $icon;
          }
        }
      }
    }
    return $default;
  }
  function get_taxonomy_color($term_id, $default = false){
    $color = get_term_meta( $term_id, $this->META_COLOR, true );
    if($color && !empty($color)){
      return event_post_format_color($color);
    }else{
      $ancestors = get_ancestors($term_id, '', 'taxonomy' );
      if($ancestors){
        foreach($ancestors as $ancestor){
          $color = get_term_meta( $ancestor, $this->META_COLOR, true );
          if($color && !empty($color)){
            return  event_post_format_color($color);
          }
        }
      }
    }
    return $default;
  }
}

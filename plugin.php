<?php
/*
Plugin Name: CPT on Front Page
Description: Allow for any post type to be displayed on the front page instead of just Pages.
Author: Mark Fabrizio
Version: 1.0.0
Author URI: https://owlwatch.com
Plugin URI: http://wordpress.org/extend/plugins/cpt-on-frontpage

  This plugin is released under version 3 of the GPL:
  http://www.opensource.org/licenses/gpl-3.0.html
*/

class cpt_on_frontpage {
  
  protected $ns = 'cpt_on_frontpage';
  
  public function __construct()
  {
    add_filter('pre_get_posts',       array($this, 'pre_get_posts'), 10, 1 );
    add_filter('wp_dropdown_pages',   array($this, 'wp_dropdown_pages'), 10, 1);
    add_filter('post_type_link',      array($this, 'post_type_link'), 10, 4);
    add_action('admin_init',          array($this, 'settings_init'));
  }
  
  /**
   * @wp.filter
   */
  public function pre_get_posts( $q )
  {
    if( is_admin() || !$q->is_main_query() || !$q->is_singular() ) return;
    
    if( get_option('page_on_front') == $q->get('page_id') ){
      if( 'page' != get_post_type( $q->get('page_id') ) ){
        $q->set('post_type', 'any');
        $q->is_page = false;
        $q->is_single = true;
      }
    }
  }
  
  /**
   * @wp.filter
   */
  public function wp_dropdown_pages( $select )
  {
    if( false === strpos($select, "page_on_front") ) return $select;
    
    $types = get_option($this->ns.'_types');
    if( !$types || !is_array($types) || !count($types) ) return $select;
    
    // otherwise, lets get the all the options...
    $start = strpos($select, '/option>')+9;
    $end = strrpos($select, '<');
    
    $open = substr( $select, 0, $start );
    $opts = substr( $select, $start, $end-$start);
    $close = substr( $select, $end );
    
    $select = $open.'<optgroup label="Pages">'.$opts.'</optgroup>';
    
    $page_on_front = get_option( 'page_on_front' );
    
    foreach( $types as $type ){
      
      $object = get_post_type_object( $type );
      if( !$object ) continue;
      
      // add our own options
      $posts = get_posts(array(
          'post_type'       => $type
        , 'posts_per_page'  => -1
        , 'orderby'         => 'title'
        , 'order'           => 'asc'
      ));
      
      if( !count( $posts) ) continue;
      
      $select.='<optgroup label="'.esc_attr($object->labels->name).'">';
      
      foreach( $posts as $p ){
        $selected = $p->ID == $page_on_front ? 'selected="selected"' : '';
        $select.='<option value="'.$p->ID.'" '.$selected.'>'.$p->post_title.'</option>';
      }
      
      $select.='</optgroup>';
    }
    $select.=$close;
    
    return $select;
  }
  
  /**
   * @wp.filter
   */
  public function post_type_link($post_link, $post, $leavename, $sample)
  {
    if( $post->ID == get_option('page_on_front') ){
      return home_url('/');
    }
    return $post_link;
  }
  
  /**
   * @wp.action
   */
  public function settings_init()
  {
    add_settings_field(
      $this->ns.'_types',
      'Post Types allowed on Front Page',
      array(&$this, 'settings_field'),
      'reading'
    );
    
    register_setting('reading', $this->ns.'_types');
  }
  
  public function settings_field()
  {
    $types = get_option( $this->ns.'_types' );
    if( !$types || !is_array($types) ) $types = array();
    
    foreach( get_post_types() as $type ){
      $object = get_post_type_object( $type );
      if( in_array($type, array('page','attachment')) || !$object->publicly_queryable ) continue;
      
      $checked = in_array( $type, $types ) ? 'checked="checked"' : '';
      ?>
      <label>
        <input type="checkbox" name="<?= $this->ns ?>_types[]" value="<?= $type ?>" <?= $checked ?> />
        <?= $object->labels->name ?>
      </label><br />
      <?php
    }
  }
  
}

$GLOBALS['cpt_on_frontpage'] = new cpt_on_frontpage;
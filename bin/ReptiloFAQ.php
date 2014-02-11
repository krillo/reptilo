<?php
/**
 * This is the ReptiloCarousel.
 * To initialize it you just have to include this php-file
 */
$rc = new ReptiloFAQ;



/**
 * The ReptiloCarousel class is based on Bootstrap 3.0 
 * 1. It adds a custom posttype called Slideshow
 * 2. Add featured image to them  
 * 3. Call the slideshow like this
 * <?php global $rc; if (method_exists($rc,'rep_carousel')) $rc->rep_carousel('rep-carousel', false); ?>
 * 
 * Author: Kristian Erendi 
 * URI: http://reptilo.se 
 * Date: 2013-02-10 
 */
class ReptiloFAQ{

  function __construct() {
    add_action('init', array($this, 'create_post_type'));
  }

  /**
   * Crate posttype
   */
  function create_post_type() {
  $labels = array(
      'name' => 'FAQ',
      'singular_name' => 'FAQ',
      'add_new' => 'Lägg till ny FAQ',
      'add_new_item' => 'Lägg till ny FAQ',
      'edit_item' => 'Redigera FAQ',
      'new_item' => 'Ny FAQ',
      'all_items' => 'Alla FAQn',
      'view_item' => 'Visa FAQ',
      'search_items' => 'Sök FAQ',
      'not_found' => 'Inga FAQn hittade',
      'not_found_in_trash' => 'Inga FAQn hittade i soptunnan',
      'parent_item_colon' => '',
      'menu_name' => 'FAQ'
  );

  $args = array(
      'labels' => $labels,
      'public' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'query_var' => true,
      'rewrite' => array('slug' => 'faq'),
      'capability_type' => 'post',
      'has_archive' => true,
      'hierarchical' => false,
      'menu_position' => null,
      'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt') //, 'comments' )
  );
  register_post_type('faq', $args);
}


}

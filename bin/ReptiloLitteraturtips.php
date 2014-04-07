<?php

/**
 * This is Litteraturtips.
 * To use it you just have to include this php-file in the functions.php
 */
$littTips = new Litteraturtips;

/**
 * 1. It adds a custom posttype
 * 2. Add featured image to them
 * 3. Use the Call it like this
 * <?php global $littTips; if (method_exists($littTips,'printLitteraturtips')) $littTips->printLitteraturtips(); ?>
 * 
 * Author: Kristian Erendi 
 * URI: http://reptilo.se 
 * Date: 2014-04-07 
 */
class Litteraturtips {

  function __construct() {
    add_action('init', array($this, 'create_post_type'));
    $this->init_acf_fields();
  }

  /**
   * Crate posttype
   */
  function create_post_type() {
    $labels = array(
        'name' => 'Litteraturtips',
        'singular_name' => 'Litteraturtips',
        'add_new' => 'Lägg till nytt litteraturtips',
        'add_new_item' => 'Lägg till nytt litteraturtips',
        'edit_item' => 'Redigera litteraturtips',
        'new_item' => 'Nytt litteraturtips',
        'all_items' => 'Alla litteraturtips',
        'view_item' => 'Visa litteraturtips',
        'search_items' => 'Sök litteraturtips',
        'not_found' => 'Inga litteraturtips hittade',
        'not_found_in_trash' => 'Inga litteraturtips hittade i soptunnan',
        'parent_item_colon' => '',
        'menu_name' => 'Litteraturtips'
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'litteraturtips'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt') //, 'comments' )
    );
    register_post_type('litteraturtips', $args);
  }

  function init_acf_fields() {
    if (function_exists("register_field_group")) {
      register_field_group(array(
          'id' => 'acf_litteraturtips',
          'title' => 'Litteraturtips',
          'fields' => array(
              array(
                  'key' => 'field_52fbc2b25b38e',
                  'label' => 'bild',
                  'name' => 'bild',
                  'type' => 'image',
                  'save_format' => 'id',
                  'preview_size' => 'thumbnail',
                  'library' => 'all',
              ),
              array(
                  'key' => 'field_52fbc2c65b38f',
                  'label' => 'Författare',
                  'name' => 'forfattare',
                  'type' => 'text',
                  'default_value' => '',
                  'placeholder' => '',
                  'prepend' => '',
                  'append' => '',
                  'formatting' => 'html',
                  'maxlength' => '',
              ),
              array(
                  'key' => 'field_52fbc2d55b390',
                  'label' => 'Text',
                  'name' => 'text',
                  'type' => 'text',
                  'default_value' => '',
                  'placeholder' => '',
                  'prepend' => '',
                  'append' => '',
                  'formatting' => 'html',
                  'maxlength' => '',
              ),
              array(
                  'key' => 'field_52fbc3015b391',
                  'label' => 'Länk',
                  'name' => 'isbn',
                  'type' => 'text',
                  'instructions' => 'Om du vill länka till en webbshop så använd en av länkarna nedan och lägg bara på ISBN nummret sist. <br/><br/>
	<b>Adlibris:</b>	 http://clk.tradedoubler.com/click?p=21&a=2214064&g=17284614&url=http://www.adlibris.com/se/product.aspx?isbn= <br>
	<b>CDON:</b>	 http://clk.tradedoubler.com/click?p=46&a=2214064&url=http://cdon.se/search?q= <br>
	<b>Bokus:</b>	 http://clk.tradedoubler.com/click?p=362&a=2214064&g=17415644&url=http://www.bokus.com/bok/',
                  'default_value' => '',
                  'placeholder' => '',
                  'prepend' => '',
                  'append' => '',
                  'formatting' => 'html',
                  'maxlength' => '',
              ),
          ),
          'location' => array(
              array(
                  array(
                      'param' => 'post_type',
                      'operator' => '==',
                      'value' => 'litteraturtips',
                      'order_no' => 0,
                      'group_no' => 0,
                  ),
              ),
          ),
          'options' => array(
              'position' => 'normal',
              'layout' => 'no_box',
              'hide_on_screen' => array(
                  0 => 'the_content',
                  1 => 'excerpt',
                  2 => 'custom_fields',
                  3 => 'discussion',
                  4 => 'comments',
                  5 => 'revisions',
                  6 => 'slug',
                  7 => 'author',
                  8 => 'format',
                  9 => 'featured_image',
                  10 => 'categories',
                  11 => 'tags',
                  12 => 'send-trackbacks',
              ),
          ),
          'menu_order' => 0,
      ));
    }
  }

  function printLitteraturtips($posttype = 'litteraturtips', $nbr = 5, $random = false, $nbrDigits = 40, $echo = true) {
    global $post;
    $args = array('post_type' => $posttype, 'posts_per_page' => $nbr);
    if ($random) {
      $args['orderby'] = 'rand';
    }
    $loop = new WP_Query($args);
    if ($loop->have_posts()):
      $i = 0;
      while ($loop->have_posts()) : $loop->the_post();
        if ($i % 2 == 0) {
          $zebra_class = 'zebra';
        } else {
          $zebra_class = '';
        }
        $i++;
        $img = wp_get_attachment_image(get_field('bild'), 'bokomslag');
        $title = mb_substr(get_the_title(), 0, 32) . '..';
        $author = mb_substr(get_field('forfattare'), 0, 32) . '..';
        $text = mb_substr(get_field('text'), 0, 32);
        $text = $text == '' ? $text : $text . '..';
        $url = get_field('isbn');  //notis its is now a link!!
        $out .= <<<OUT
        <div class="posttype-container $zebra_class">
          <div class="posttype-img">
            $img
          </div>        
          <div class="posttype-content">
            <b>$title</b><br/>
            $author<br/>
            $text<br/>
            <a href="$url" target="_blank" class="">Läs mer om boken</a>
          </div>
        </div>              
OUT;
      endwhile;
    endif;
    wp_reset_query();
    if ($echo) {
      echo $out;
    }
  }


}
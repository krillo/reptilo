<?php

/**
 * This is the ReptiloCarousel.
 * To initialize it you just have to include this php-file
 */
$rfaq = new ReptiloFAQ;

/**
 * The ReptiloFAQ class is based on Bootstrap 3.0 
 * 1. It adds a custom posttype called 'FAQ' and a taxonomy called 'faqkategori'
 * 2. Add FAQs to them  
 * 3. List all FAQs like this
 *   <?php global $rfaq; if (method_exists($rfaq, 'displayFAQ')){ $rfaq->displayFAQ();} ?>
 * 4. List all FAQ categorys like this
 *   <?php global $rfaq; if (method_exists($rfaq, 'displayFAQCategories')) {$rfaq->displayFAQCategories($mySlug);} ?>
 *   where $mySlug is the current page slug
 * 5. OR use the theme pages "archive-faq.php" and "single-faq.php" for the listings
 * 
 * Author: Kristian Erendi 
 * URI: http://reptilo.se 
 * Date: 2013-02-26 
 * updated: 2014-06-06
 * revision 1.2
 */
class ReptiloFAQ {

  function __construct() {
    add_action('init', array($this, 'create_post_type'));
    add_action('init', array($this, 'create_faq_taxonomy'));
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
        'rewrite' => array('slug' => 'alla-faq'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt') //, 'comments' )
    );
    register_post_type('faq', $args);
  }

  /**
   *  Add a new taxonomy for post type  "FAQ" make it hierarchical (like categories)
   */
  function create_faq_taxonomy() {
    $labels = array(
        'name' => _x('FAQ-kategori', 'taxonomy general name'),
        'singular_name' => _x('FAQ-kategori', 'taxonomy singular name'),
        'search_items' => __('Sök FAQ-kategorier'),
        'all_items' => __('Alla FAQ-kategorier'),
        'parent_item' => __('FAQ-kategorier förälder'),
        'parent_item_colon' => __('FAQ-kategorier förälder:'),
        'edit_item' => __('Redigera FAQ-kategorier'),
        'update_item' => __('Uppdatera FAQ-kategori'),
        'add_new_item' => __('Lägg till ny FAQ-kategori'),
        'new_item_name' => __('Ny FAQ-kategoriname'),
        'menu_name' => __('FAQ-kategorier')
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_tagcloud' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'faq-kategorier'),
        'capabilities' => array(
            'manage_terms' => 'manage_options', //by default only admin
            'edit_terms' => 'manage_options',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'edit_posts'  // means administrator', 'editor', 'author', 'contributor'
        )
    );
    register_taxonomy('faqkategori', array('faq'), $args);
  }

  /**
   * Display all FAQs with taxonomy
   * To display all FAQs - submit 'showall'
   * 
   * @global type $post
   */
  function displayFAQ($cat) {
    global $post;
    if($cat != 'showall'){
    $args = array('post_type' => 'faq', 'orderby' => 'modified', 'order' => 'DESC', 'tax_query' => array(array('taxonomy' => 'faqkategori', 'field' => 'slug','terms' => $cat)));
    } else {
      $args = array('post_type' => 'faq', 'orderby' => 'modified', 'order' => 'DESC');
    }
    $loop = new WP_Query($args);
    if ($loop->have_posts()):
      $out = '<div class="panel-group" id="accordion">';
      while ($loop->have_posts()) : $loop->the_post();
        //print_r($post);
        $title = $post->post_title;
        $permalink = get_permalink($post->ID);
        $id = $post->ID;
        $content = get_the_content();
        //$edit_post_link = get_edit_post_link();
        $post_class = ' class="' . implode(' ', get_post_class("panel panel-default")) . '" ';
        $out .= <<<POST
          <article id="post-$id" $post_class>
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse-post-$id">
                  <i class="fa fa-question fa-lg"></i></span>$title
                </a>
              </h4>
            </div>
            <div id="collapse-post-$id" class="panel-collapse collapse">
              <div class="panel-body">
                $content 
              </div>
            </div>
          </article>
POST;
      endwhile;
      $out .= '</div>';
    endif;
    wp_reset_query();
    echo $out;
  }

  /**
   * Returns an array of all categories for fynd
   * "Köp" and "Sälj" are also categoriesthese are excluded
   *  
   */
  function displayFAQCategories($myslug) {
    
    $cats = get_terms('faqkategori', array('orderby' => 'name', 'hide_empty' => 0));
    $out = '<ul class="rep-faq-tax">';
    foreach ($cats as $cat) {
      $out .= '<li data-cat="' . $cat->slug . '" class=""><a href="/'.$myslug.'/?cat=' . $cat->slug . '" ><i class="fa fa-question-circle"></i>  ' . $cat->name . '</a></li>';
    }
    $out .= '</ul>';
    echo $out;
  }


}
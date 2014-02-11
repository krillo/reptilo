<?php
/**
 * Reptilo
 * Author: Kristian Erendi
 * Author URI: http://reptilo.se/
 * Date: 2013-12-20
 * @package WordPress
 * @subpackage Reptilo
 * @since Reptilo 1.0
 */


add_theme_support( 'post-thumbnails' ); 

/**
 * Enqueues scripts and styles for frontend.
 */
function reptilo_enqueue_scripts() {
  wp_enqueue_script('bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), '2013-12-20', true);
  wp_enqueue_script('reptilo.js', get_template_directory_uri() . '/js/reptilo.js', array('jquery'), '2013-12-20', true);
  wp_enqueue_script('jquery.placeholder', get_template_directory_uri() . '/js/jquery.placeholder.js', array('jquery'), '2013-12-20', true);  //IE fix
  wp_enqueue_style('font_awesome', get_template_directory_uri() . '/fonts/font-awesome/css/font-awesome.min.css', array(), '2013-12-20');
  wp_enqueue_style('style', get_stylesheet_uri(), array(), '2013-12-20');
  // Adds JavaScript to pages with the comment form to support sites with threaded comments (when in use).
  if (is_singular() && comments_open() && get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }
}

add_action('wp_enqueue_scripts', 'reptilo_enqueue_scripts');


/**
 * register the meny
 */
register_nav_menus(array(
    'primary' => __('Primary Menu', 'huvudmeny'),
));

/**
 * Remove the [...] from the excerpt printout 
 * @param type $more
 * @return string
 */
function new_excerpt_more($more) {
  return '...';
}

add_filter('excerpt_more', 'new_excerpt_more');

function rep_widgets_init() {
  register_sidebar(array(
      'name' => __('Sidebar info'),
      'id' => 'sidebar_info',
      'description' => __('Widgets kommer att visas till höger'),
      'before_title' => '<h3>',
      'after_title' => '</h3>'
  ));
}

add_action('widgets_init', 'rep_widgets_init');

/**
 * Include the special header file "header-home.php"
 * It tries to find it in gthe child thme else it gets it from this parent theme
 * 
 */
function rep_get_header_home() {
  $file = 'header-home.php';
  if (is_child_theme()) {
    $testfile = get_stylesheet_directory() . '/' . $file;
    if (file_exists($testfile)) {
      $file = $testfile;
    }
    include $file;
  }
}














include_once "bin/reptilo_utils.php";
include_once "bin/reptilo_carousel.php";
include_once "bin/dimox_bootstrap_breadcrumbs.php";
require_once "bin/wp-bootstrap-navwalker.php";


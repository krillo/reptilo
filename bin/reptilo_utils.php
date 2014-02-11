<?php
/*
 * Description: The file contains functions commonly used by Reptilo 
 * Author: Kristian Erendi
 * Author URI: http://reptilo.se
 * Date: 2012-12-06
 * License: GNU General Public License version 3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Version: 1.0
 */




add_action('wp_enqueue_scripts', 'reptilo_scripts');

/**
 * Enqueue some java scripts, only on front page
 */
function reptilo_scripts() {
}

/**
 * Shortcode for a tooltip
 * Use like this: 
 * [tooltip text="apa" tip="Vardagligt utryck för primater"]
 * 
 * This is not a standalone function, it has dependencies to:
 * 1. bootstrap.min.js
 * 2. reptilo.js for initialization 
 */
function rep_tooltip($atts) {
  extract(shortcode_atts(array(
      'text' => '',
      'tip' => '',
                  ), $atts));
  return '<a href="#" data-toggle="tooltip" class="rep-tooltip" title="' . $tip . '" data-placement="top">' . $text . '</a>';
}

add_shortcode('tooltip', 'rep_tooltip');

/**
 * Display posts from a category.
 * Bootstrap 3 style
 * 
 * @global type $post
 * @param type $category  - the slug
 * @param type $nbr - nbr of posts to show
 */
function printPostsPerCat($category = 'aktuellt', $nbr = 1){//, $nbrDigits = 100) {
  global $post;
  $args = array('category_name' => $category, 'posts_per_page' => $nbr);
  $loop = new WP_Query($args);
  if ($loop->have_posts()):
    while ($loop->have_posts()) : $loop->the_post();
      //$content = mb_substr(get_the_content(), 0, $nbrDigits) . '...';
      $content = get_the_excerpt();
      $title = get_the_title();
      $guid = get_the_guid();
      $img = '';
      if (has_post_thumbnail()){
        $img = get_the_post_thumbnail(null, 'thumbnail');
      } 
      $readingbox .= <<<RB
<div class="cat-container">
  <section>
    <h2>$title</h2>
    $img          
    <p>$content</p>
    <a href="$guid" target="" class="btn btn-default btn-xs">Läs mer</a>
  </section>
</div>
RB;
    endwhile;
  endif;
  wp_reset_query();
  echo $readingbox;
}

/* * ** Reptilo feedback callback function *** */
add_action('wp_ajax_rep_feedback', 'rep_feedback_callback');
add_action('wp_ajax_nopriv_rep_feedback', 'rep_feedback_callback');

function rep_feedback_callback() {
  !empty($_REQUEST['pagename']) ? $pagename = addslashes($_REQUEST['pagename']) : $pagename = '';
  !empty($_REQUEST['guid']) ? $guid = addslashes($_REQUEST['guid']) : $guid = '';
  !empty($_REQUEST['type']) ? $type = addslashes($_REQUEST['type']) : $type = '';
  !empty($_REQUEST['msg']) ? $msg = addslashes($_REQUEST['msg']) : $msg = '';
  !empty($_REQUEST['email']) ? $email = addslashes($_REQUEST['email']) : $email = '';
//!empty($_REQUEST['to_email']) ? $to_email = addslashes($_REQUEST['to_email']) : $to_email = 'hravdelningen@helsingborg.se';
  !empty($_REQUEST['to_email']) ? $to_email = addslashes($_REQUEST['to_email']) : $to_email = 'krillo@gmail.com';

  $subject = "Feedback på intranätet";
  $message = <<<MSG
Feedback

Sidanamn: $pagename
Länk: $guid
Typ: $type
Email: $email
          
  
$msg

MSG;

  $success = wp_mail($to_email, $subject, $message);
  $response = json_encode(array('success' => $success, 'guid' => $guid));
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json');
  echo $response;
  die(); // this is required to return a proper result
}



/**
 * Pagination Bootstrap 3 style
 * 
 * @global type $wp_query
 * @param type $query
 */
function bootstrap3_pagination($query = null) {
  global $wp_query;
  $query = $query ? $query : $wp_query;
  $big = 999999999;

  $paginate = paginate_links(array(
      'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
      'type' => 'array',
      'total' => $query->max_num_pages,
      'format' => '?paged=%#%',
      'current' => max(1, get_query_var('paged')),
      'prev_text' => __('&laquo;'),
      'next_text' => __('&raquo;'),
          )
  );

  if ($query->max_num_pages > 1) :
    ?>
    <ul class="pagination pagination-sm">
      <?php
      foreach ($paginate as $page) {
        echo '<li>' . $page . '</li>';
      }
      ?>
    </ul>
    <?php
  endif;
}

/**
 * This function echoes a hiearchical tree menu suited for intranets.
 * A chunk of javascript is in the reptilo.js
 * 
 * Use like this:
 * <?php if (function_exists('rep_page_hierarchy')) rep_page_hierarchy(); ?>
 * 
 * @global type $post
 */
function rep_page_hierarchy() {
  global $post;
  $currentPostId = $post->ID;
  $forefatherId = rep_get_forefather($post->ID);
  $faqstyle = '';
  if (is_post_type_archive('faq')) {
    $faqstyle = 'current_page_item ';
  }
  $args = array(
      'sort_order' => 'ASC',
      'sort_column' => 'post_title',
      'hierarchical' => 1,
      'exclude' => '',
      'include' => '',
      'meta_key' => '',
      'meta_value' => '',
      'authors' => '',
      'child_of' => 0,
      'parent' => 0,
      'exclude_tree' => '1663',
      'number' => '',
      'offset' => 0,
      'post_type' => 'page',
      'post_status' => 'publish'
  );
  $pages = get_pages($args);

  $out = '<div class="rep-page-hierarchy">';
  foreach ($pages as $page) {
    $showTree = false;
    if ($page->ID == $forefatherId) {
      $showTree = true;
    }
    $ul = rep_list_pages($page->ID, $showTree);
    $div_class = '';
    $a_class = '';
    $caret = false;
    if ($ul != '') {
      $div_class = ' rep-has-children ';
      $caret = true;
    }
    if ($currentPostId == $page->ID) {
      $a_class = ' current_page_item ';
    }
    $out .= '<div class="rep-forefather page-id-' . $page->ID . $div_class . '">';
    $out .= '<a href="' . get_permalink($page->ID) . '" class="' . $a_class . '">' . $page->post_title . '</a>';
    $out .= $caret ? '<i class="fa fa-caret-down rep-caret" style="float:right;" data="' . $page->ID . '"></i>' : '';
    $out .= $ul;
    $out .= '</div>';
  }
  $out .= '<div class="rep-forefather"><a href="/faq/" class="'.$faqstyle.'">FAQ</a></div>';
  $out .= '</div>';
  echo $out;
}

/**
 * This is a helper function to rep_page_hierarchy()
 * 
 * @param type $postId
 * @return type
 */
function rep_get_forefather($postId) {
  $ancestors = get_ancestors($postId, 'page');
  if (count($ancestors) > 0) {
    return $ancestors[count($ancestors) - 1];
  } else {
    return -1;
  }
}

/**
 * This is a helper function to rep_page_hierarchy()
 * 
 * @param type $id
 * @param type $showTree
 * @return string
 */
function rep_list_pages($id, $showTree) {
  $ul = '';
  $li = '';
  $args = array(
      'authors' => '',
      'child_of' => $id,
      'date_format' => get_option('date_format'),
      'depth' => 0,
      'echo' => 0,
      'exclude' => '',
      'exclude_tree' => '1663',
      'include' => '',
      'link_after' => '',
      'link_before' => '',
      'post_type' => 'page',
      'post_status' => 'publish',
      'show_date' => '',
      'sort_column' => 'menu_order, post_title',
      'title_li' => __(''),
      'walker' => ''
  );
  $li = wp_list_pages($args);
  if ($li != '') {
    if (!$showTree) {
      $class = ' rep-collapse ';
    }
    $ul = '<ul id="rep-children-to-' . $id . '" class="rep-top-children' . $class . ' ">' . $li . '</ul>';
  }
  return $ul;
}


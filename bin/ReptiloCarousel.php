<?php
/**
 * This is the ReptiloCarousel.
 * To initialize it you just have to include this php-file
 */
$rc = new ReptiloCarousel;



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
class ReptiloCarousel {

  function __construct() {
    add_action('init', array($this, 'rep_create_slideshow_post_type'));
  }

  /**
   * Crate posttype
   */
  function rep_create_slideshow_post_type() {
    register_post_type('Slideshow', array(
        'labels' => array(
            'name' => __('Slideshow'),
            'singular_name' => __('Slide')
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'thumbnail')
            )
    );
  }

  /**
   * This method prints the carousel
   * 
   * @global Post $post
   * @param string $carousel_id - this string is added att the id och the containing div
   * @param boolean $indicators - set this to true to show indicators  
   */
  function rep_carousel($carousel_id, $indicators = true) {
    global $post;
    $args = array('post_type' => 'Slideshow', 'posts_per_page' => 16);
    $loop = new WP_Query($args);
    $li = '';
    $img = '';
    $i = 0;
    $active = 'active';
    if ($loop->have_posts()):
      while ($loop->have_posts()) : $loop->the_post();
        if ($i != 0) {
          $active = '';
        }
        if ($indicators) {
          $li .= '<li data-target="#' . $carousel_id . '" data-slide-to="' . $i . '" class="' . $active . '"></li>';
        }
        $img .= '<div class="item ' . $active . '">' . get_the_post_thumbnail() . '</div>';
        $i++;
      endwhile;
      $carousel = <<<CAROUSEL
  <div id="$carousel_id" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      $li
    </ol>
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      $img
    </div> 
  </div>
CAROUSEL;
    endif;
    wp_reset_query();
    echo $carousel;
  }

}
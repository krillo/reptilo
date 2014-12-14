<?php

/**
 * This is the ReptiloFeaturedSlides.
 * To initialize it you just have to include this php-file 
 */
$rfs = new ReptiloFeaturedSlides;

/**
 * The ReptiloCarousel class is based on Bootstrap 3.0 
 * 1. It adds a custom posttype called FeaturedSlides
 * 2. Create a new FeaturedSlides item   
 * 3. Add posts/items in the repeater
 * 4. Then it shows images, and links to the posts in a slideshow
 * 5. Paste this in your theme - select a featuredslide by its slug
 * <?php global $rfs; if (method_exists($rfs,'rep_featuredslides')) $rfs->rep_featuredslides('featuredslides_slug', false); ?>
 * 
 * Author: Kristian Erendi 
 * URI: http://reptilo.se 
 * Date: 2014-11-10 
 */
class ReptiloFeaturedSlides {

  function __construct() {
    add_action('init', array($this, 'rep_create_post_type'));
    $this->init_acf_fields();
    add_shortcode('rep_featuredslides', array($this, 'rep_featuredslides_shortcode'));
  }

  /**
   * Crate posttype
   */
  function rep_create_post_type() {
    register_post_type('FeaturedSlides', array(
        'labels' => array(
            'name' => __('FeaturedSlides'),
            'singular_name' => __('FeaturedSlide')
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'thumbnail')
            )
    );
  }

  
  /**
   * get this from ACF or keep it in there...
   */
  function init_acf_fields() {
    /*
     * this is old stuff
     * 
      if (function_exists("register_field_group")) {
      register_field_group(array(
      'id' => 'acf_slideshow',
      'title' => 'Slideshow',
      'fields' => array(
      array(
      'key' => 'field_539f52dde6cdf_rep',
      'label' => 'Url',
      'name' => 'url',
      'type' => 'text',
      'instructions' => 'Länka bilden. Glöm inte http://',
      'default_value' => '',
      'placeholder' => 'http://',
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
      'value' => 'slideshow',
      'order_no' => 0,
      'group_no' => 0,
      ),
      ),
      ),
      'options' => array(
      'position' => 'normal',
      'layout' => 'default',
      'hide_on_screen' => array(
      ),
      ),
      'menu_order' => 0,
      ));
      }
     */
  }

  /**
   * Shortcode for a tooltip
   * Use like this: 
   * [rep_carousel id="reklam"]
   * 
   */
  function rep_featuredslides_shortcode($atts) {
    extract(shortcode_atts(array(
        'id' => '',
                    ), $atts));
    $carousel = $this->rep_featuredslides($id, false, false, false);
    return $carousel;
  }

  /**
   * This method prints the carousel
   * To add a url to the images then add a field (ACF) named url
   * 
   * @global Post $post
   * @param string $slide_slug - this string is added att the id och the containing div
   * @param boolean $indicators - set this to true to show indicators  
   */
  function rep_featuredslides($slide_slug, $indicators = true, $arrows = true, $echo = true) {
    global $post;
    $args = array('post_type' => 'FeaturedSlides', 'posts_per_page' => 30);
    $loop = new WP_Query($args);
    $lidesArray = $loop->posts;  //all FeaturedSlides available
    $currentSlideObj = null;
    $slidesObjId = null;
    foreach ($lidesArray as $slideObj) {
      if ($slideObj->post_name == $slide_slug) {
        $slidesObj = $slideObj;
        $slidesObjId = $slideObj->ID;
      }
    }
    if ($slidesObjId != null) {
      $indicator = '';
      $arrow = '';
      $img = '';
      $i = 0;
      $active = 'active';
      if (have_rows('slideobjects', $slidesObjId)):
        while (have_rows('slideobjects', $slidesObjId)) : the_row();
          $slideobj = get_sub_field('slideobj');
          $url = get_permalink($slideobj->ID);
          if ($i != 0) {
            $active = '';
          }
          if ($indicators) {
            $indicator .= '<li data-target="#' . $slideobj->post_name . '" data-slide-to="' . $i . '" class="' . $active . '"></li>';
          }
          $img .= '<div class="item ' . $active . '"><a href="' . $url . '">' . get_the_post_thumbnail($slideobj->ID, 'thumbnail') . '</a><div class="featuredslide-title">' . $slideobj->post_title . '</div></div>';
          if ($arrows) {
            $arrow .= '<a class="left carousel-control" href="#' . $slide_slug . '" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a><a class="right carousel-control" href="#' . $slide_slug . '" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>';
          }
          $i++;
        endwhile;
        $carousel = <<<CAROUSEL
  <div id="$slide_slug" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      $indicator
    </li>
    </ol>
    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      $img
    </div>
    $arrow
  </div>
CAROUSEL;
      endif;
      wp_reset_query();
      if ($echo) {
        echo $carousel;
      } else {
        return $carousel;
      }
    }
  }

}

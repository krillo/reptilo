<?php
/**
 * Template Name: FAQ-sida
 *
 * @author Kristian Erendi
 * @subpackage Template
 * uses the ReptiloFAQ class
 * @date 2014-06-06
 */
get_header();
!empty($_REQUEST['cat']) ? $cat = $_REQUEST['cat'] : $cat = '';
//$cat = 'showall';   //show all FAQs
$term = get_term_by('slug', $cat, 'faqkategori');
$taxname = $term->name;
$mySlug = '';
?>
<div class="container">
  <div class="row" id="">
    <div class="col-sm-3" id="nav-sidebar">
      <?php include("snippets/sidebar.php"); ?>
    </div>    
    <?php
    if (have_posts()) : while (have_posts()) : the_post();
    $mySlug = $post->post_name;
        ?>
        <div class="col-sm-7 rep-content">
          <?php include "snippets/notice.php"; ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class("rep-article, rep-no-bottom"); ?>>
            <h1>
              <?php
              the_title();
              if ($cat) {
                echo ' - ' . $taxname;
              }
              ?>
            </h1>
            <?php the_content(); ?>
          </article>
          <div class="top-buffer ">

            <?php
            global $rfaq;
            if ($cat) {
              //show all FAQs ina category
              if (method_exists($rfaq, 'displayFAQ')) {
                $rfaq->displayFAQ($cat);
              }
            } else {
              //show all FAQs
              if (method_exists($rfaq, 'displayFAQCategories')) {
                $rfaq->displayFAQCategories($mySlug);
              }
            }
            ?>

          </div>
        </div>
        <div class="col-sm-2" id="second-sidebar">
          <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar("sidebar_info")) : endif; ?>    
        </div>     
      </div>          
      <?php
    endwhile;
  else:
    ?>
    <div class="span12">  
      <p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
    </div>
  <?php endif; ?>  
</div>  <!-- end container -->
<?php get_footer(); ?>
<?php
/**
 * Template Name: Blogg
 * @author Kristain Erendi
 * @subpackage Template
 * 
 * Notice! don't forget to copy the header-home 
 */
get_header();
?>
<div class="container">
  <div class="row post-item">
    <div class="col-md-12">
      Blogg
    </div>
  </div>
  <?php
  $myposts = get_posts('');
  foreach ($myposts as $post) :
    setup_postdata($post);
    ?>
    <div class="row post-item">
      <div class="col-md-12">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <h2><?php the_title(); ?></h2>
          <div class="pub-info"><i class="fa fa-calendar-o"></i><time pubdate="pubdate"><?php the_modified_date(); ?></time> <i class="fa fa-pencil"></i>Skriven av <?php the_author(); ?> <i class="fa fa-tags"></i><?php the_tags(' '); ?></div>
          <?php the_excerpt(); ?>
          <a href="<?php the_permalink(); ?>"  title="<?php the_title_attribute(); ?>">Läs mer</a>
        </article>
      </div>
    </div>
  <?php endforeach;
  wp_reset_postdata();
  ?>
</div>
<?php get_footer(); ?>
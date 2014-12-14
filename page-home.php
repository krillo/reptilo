<?php
/**
 * Template Name: Förstasidan
 * @author Kristain Erendi
 * @subpackage Template
 * 
 * Notice! don't forget to copy the header-home if you want to use this
 */
rep_get_header_home();
?>
<div class="container" id="main-container">
  <?php
  if (have_posts()) : while (have_posts()) : the_post();
      ?>
      <div class="row">
        <div class="col-md-12">
          <div id="hbg-logo-area">
            <img class="" alt="Logo" src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo.png" >
          </div>
        </div>
      </div>
      <h1 id="home-h1"><?php the_field('rubrik'); ?></h1>
      <h2 id="home-h2"><?php the_field('underrubrik'); ?></h2>
      <div class="row search-area-large">
        <div class="col-md-3">&nbsp;</div>
        <div class="col-md-6">
          <form action="/" method="get" role="search" id="hbg-search">
            <div class="input-group input-group-lg">
              <input type="text" class="form-control" placeholder="Vad söker du?" autocomplete="off" type="text" name="s">
              <span class="input-group-btn">
                <input class="btn btn-default hbg-btn" type="submit" value="Sök!">
              </span>
            </div>
          </form>
        </div>
        <div class="col-md-3">&nbsp;</div>
      </div> 


      <div class="row">
        <div class="col-md-12">
          <!--?php include "snippets/fontawsome_dropdown.php"; ?-->
          <?php printPostsPerCat('aktuellt', 1, 250); ?>
        </div>  
      </div> 
      <div class="row" id="puff-container">
        <div class="col-md-3 ">
          <div class="puff" id="puff1">
            <img class="" alt="" src="<?php the_field('bild_1'); ?>" >
            <h3 class="less-top-margin"><?php the_field('rubrik1'); ?></h3>
            <p><?php echo mb_substr(get_field('text_1'), 0, 120) . '...'; ?></p><a href="<?php
            $post_obj = get_field('lank_1');
            echo $post_obj->guid;
            ?>">Läs mer...</a>
          </div>
        </div>  
        <div class="col-md-3">
          <div class="puff" id="puff2">
            <img class="" alt="" src="<?php the_field('bild_2'); ?>" >
            <h3 class="less-top-margin"><?php the_field('rubrik2'); ?></h3>
            <p><?php echo mb_substr(get_field('text_2'), 0, 120) . '...'; ?></p><a href="<?php
            $post_obj = get_field('lank_2');
            echo $post_obj->guid;
            ?>">Läs mer...</a>
          </div>
        </div>  
        <div class="col-md-3">
          <div class="puff" id="puff3">
            <img class="" alt="" src="<?php the_field('bild_3'); ?>" >
            <h3 class="less-top-margin"><?php the_field('rubrik3'); ?></h3>
            <p><?php echo mb_substr(get_field('text_3'), 0, 120) . '...'; ?></p><a href="<?php
            $post_obj = get_field('lank_3');
            echo $post_obj->guid;
            ?>">Läs mer...</a>
          </div>
        </div>  
        <div class="col-md-3">
          <div class="puff" id="puff4">
            <img class="" alt="" src="<?php the_field('bild_4'); ?>" >
            <h3 class="less-top-margin"><?php the_field('rubrik4'); ?></h3>
            <p><?php echo mb_substr(get_field('text_4'), 0, 120) . '...'; ?></p><a href="<?php
            $post_obj = get_field('lank_4');
            echo $post_obj->guid;
            ?>">Läs mer...</a>
          </div>
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


<?php
/*
Template Name: Contact
*/
?>

<?php get_header(); ?>

  <main role="main" <?php body_class() ?>>
    <!-- section -->
    <article class='contact' >
      <!-- section-inner.break-container -->
      <div class='section-inner break-container'>
        <h1><?php the_title(); ?></h1>
        <div class='page-content'>
          <div class='content-column'>
            <?php echo apply_filters( 'the_content', $post->post_content ); ?>
          </div>
          <div class='form-column'>
          <?php 
            $page = create_dunnbuilding_page( $post );
            echo do_shortcode( $page->acf->cf7_shortcode ); 
            ?>
          </div>
        </div>  
      </div> 
      <!-- /div.section-inner.break-container -->
    </article>
    <!-- /section -->
  </main>

<?php get_footer(); ?>


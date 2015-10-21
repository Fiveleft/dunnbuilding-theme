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
          <?php echo do_shortcode( $post->post_content ); ?>
        </div>  
      </div> 
      <!-- /div.section-inner.break-container -->
    </article>
    <!-- /section -->
  </main>

<?php get_footer(); ?>


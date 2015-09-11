<?php
/*
Template Name: Neighborhood
*/
?>

<?php if(!is_home()) : ?>

<?php get_header(); ?>

  <main role="main">
    <!-- section -->
    <article class='neighborhood' >

      <h1><?php the_title(); ?></h1>

    </article>
    <!-- /section -->
  </main>

<?php get_footer(); ?>

<?php else : ?>
  <?php 
    // Load page content as a section of the index page
    $post = get_page_by_path( '/neighborhood' ); 
    if( $post ) :
    ?>

  <section class='neighborhood'>

    <h1><?php the_title(); ?></h1>
    <a href="/neighborhood">go to page</a>

  </section>

  <?php unset($post); ?>
  <?php endif; ?>

<?php endif; ?>
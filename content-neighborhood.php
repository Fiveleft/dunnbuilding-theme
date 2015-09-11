<?php
/*
Template Name: Apartents
*/
?>

<?php if(!is_home()) : ?>

<?php get_header(); ?>

  <main class='neighborhood' role="main">
    <!-- section -->
    <section>

      <h1><?php _e( 'Latest Posts', 'html5blank' ); ?></h1>

    </section>
    <!-- /section -->
  </main>

<?php get_footer(); ?>

<?php else : ?>

  <section class='neighborhood'>

    

  </section>

<?php endif; ?>



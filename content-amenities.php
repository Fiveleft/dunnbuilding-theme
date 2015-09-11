<?php
/*
Template Name: Apartents
*/
?>

<?php if(!is_home()) : ?>

<?php get_header(); ?>

  <main class='amenities' role="main">
    <!-- section -->
    <section>
      <h1>Amenities</h1>

      <?php get_template_part('loop'); ?>

    </section>
    <!-- /section -->
  </main>

<?php get_footer(); ?>

<?php else : ?>

  <section class='amenities'>
    <h1>Amenities</h1>


  </section>

<?php endif; ?>



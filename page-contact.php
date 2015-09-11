<?php
/*
Template Name: Contact
*/
?>

<?php if(!is_home()) : ?>

<?php get_header(); ?>

  <main role="main">
    <!-- section -->
    <article class='contact' >

      <h1><?php the_title(); ?></h1>

    </article>
    <!-- /section -->
  </main>

<?php get_footer(); ?>

<?php else : ?>

  <section class='contact'>

    <h1><?php the_title(); ?></h1>
    

  </section>

<?php endif; ?>



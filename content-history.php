<?php
/*
Template Name: Apartents
*/
?>

<?php if(!is_home()) : ?>

<?php get_header(); ?>

  <main class='history' role="main">
    <!-- section -->
    <section>

      <h1><span class='blockA'>Classic Heritage.</span>&nbsp;<span class='blockB'>Modern History.</span></h1>


    </section>
    <!-- /section -->
  </main>

<?php get_footer(); ?>

<?php else : ?>

  <section class='history'>
    <h1><span class='blockA'>Classic Heritage.</span>&nbsp;<span class='blockB'>Modern History.</span></h1>
      
    

  </section>

<?php endif; ?>



<?php
/*
Template Name: History
*/
?>

<?php if(!is_home()) : ?>

<?php get_header(); ?>
  <?php if (is_page()): the_post() ?>

  <main class='history' role="main">
    <!-- section -->
    <section>

<?php ep( $post ); ?>
      <h1><span class='blockA'>Classic Heritage.</span>&nbsp;<span class='blockB'>Modern History.</span></h1>


    </section>
    <!-- /section -->
  </main>

  <?php endif; ?>
<?php get_footer(); ?>



<?php else : ?>
  <?php 
    // Load page content as a section of the index page
    $post = get_page_by_path( '/building-history' ); 
    if( $post ) :
    ?>

  <section class='history'>
    <h1><span class='blockA'>Classic Heritage.</span>&nbsp;<span class='blockB'>Modern History.</span></h1>
      
    <a href="/building-history">go to page</a>
    

  </section>

  <?php unset($post); ?>
  <?php endif; ?>

<?php endif; ?>



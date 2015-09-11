<?php
/*
Template Name: History
*/
?>

<?php 
  $home_section = is_front_page();
  // If loading as an independent page
  if(!$home_section) { 
    get_header();
  }else{
    $page = get_page_by_path( '/building-history' ); 
  }
  ?>

<?php if(!$home_section) : ?>
  <!-- main.building-history -->
  <main role="main">
    <!-- article.building-history --> 
    <article class='building-history'>
      <h1><span class='blockA'>Classic Heritage.</span>&nbsp;<span class='blockB'>Modern History.</span></h1>
      <!-- <h1><?php //the_title(); ?></h1> -->


<?php else : ?>

    <!-- section.building-history -->
    <section class='building-history'>
      <h1><span class='blockA'>Classic Heritage.</span>&nbsp;<span class='blockB'>Modern History.</span></h1>
      <!-- <h1><?php //echo $page->post_title; ?></h1> -->
      <a href="/building-history">go to page</a>

<?php endif; ?>


    <!-- /section.building-history -->


<?php if(!$home_section) : ?>

    </article>
    <!-- /article.building-history -->
  </main>
  <!-- /main -->
  <?php get_footer(); ?>

<?php else : ?>

    </section>
  <?php 
    wp_reset_postdata();
    unset($post); 
    ?>

<?php endif; ?>
    


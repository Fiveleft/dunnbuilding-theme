<?php
/*
Template Name: Neighborhood
*/
?>

<?php 
  $home_section = is_front_page();
  // If loading as an independent page
  if(!$home_section) { 
    get_header();
  }else{
    $page_section = get_page_by_path( '/neighborhood' ); 
  }
  $page = create_dunnbuilding_page( isset( $page_section ) ? $page_section : $post );
  ep( $page );
  
  ?>

<?php if(!$home_section) : ?>
  <!-- main -->
  <main role="main">
    <!-- article.neighborhood --> 
    <article class='neighborhood'>
      <h1><?php the_title(); ?></h1>


<?php else : ?>

    <!-- section.neighborhood -->
    <section class='neighborhood'>
      <h1><?php echo $page->post_title; ?></h1>
      <a href="/neighborhood">go to page</a>

<?php endif; ?>

      <div class='map-wrapper'>
        <?php echo $page->acf->google_map->address; ?>
      </div>


<?php if(!$home_section) : ?>

    </article>
    <!-- /article.neighborhood -->
  </main>
  <!-- /main -->
  <?php get_footer(); ?>

<?php else : ?>

    </section>
    <!-- /section.neighborhood -->

  <?php 
    wp_reset_postdata();
    unset($post); 
    ?>

<?php endif; ?>
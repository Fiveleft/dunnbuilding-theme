<?php
/*
Template Name: Amenities
*/
?>

<?php 
  $is_own_page = is_page_template( 'page-amenities.php' ) && !is_front_page();
  $apartments_subsection = is_page_template( 'page-apartments.php' );
  $home_section = is_front_page();
  // If loading as an independent page
  if(!$is_own_page) { 
    get_header();
  }

  // Load Amenities
  $amenities = new WP_Query( array( 
      'post_type'   => 'amenity', 
      'orderby'     => 'post_title',
      'post_status' => 'publish'
    ));
?>

<?php if($is_own_page) : ?>
  <!-- main.amenities -->
  <main class='amenities' role="main">
    <!-- article -->
    <article>
      <h1><?php the_title(); ?></h1>

<?php else : ?>

  <?php 
    $page = get_page_by_path( '/amenities' );
    ?>
    <!-- section.amenities -->
    <section class='amenities'>
      <h1>Building Amenities</h1>
      <a href="/amenities">go to page</a>

<?php endif; ?>


    <div class='amenity-gallery'>
      (amenity gallery)
    </div>

    <div class='amenity-list-wrapper'>
      
      <ul class='amenity-list'>
        
        <?php while ( $amenities->have_posts() ) : $amenities->the_post(); ?>
        
        <li class='amenity-item'>
          <div class='amenity-item-inner'>
            <h3><?php the_title(); ?></h3>
            <div class='amenity-content'>
              <?php the_content(); ?>
            </div>
          </div>
        </li>
        
        <?php endwhile; 
          wp_reset_postdata();
          ?>

      </ul>

    </div>

<?php if($is_own_page) : ?>

    </article>
    <!-- /article -->
  </main>
  <!-- /main -->
  <?php get_footer(); ?>

<?php else : ?>

    </section>
    <!-- /section.amenities -->
  <?php 
    unset($page); 
    ?>

<?php endif; ?>


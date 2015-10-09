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
<?php $page = create_dunnbuilding_page( $post ); ?>
  <!-- main.amenities -->
  <main role="main" <?php body_class() ?>>
    <!-- article.amenities -->
    <article class='amenities'>
      <!-- section-inner.break-container -->
      <div class='section-inner break-container'>
        <h1><?php the_title(); ?></h1>
      </div>

<?php else : ?>

  <?php 
    $page = create_dunnbuilding_page( get_page_by_path( '/amenities' ) );
    ?>
    <!-- section.amenities -->
    <section class='amenities'>
      <!-- section-inner.break-container -->
      <div class='section-inner break-container'>
        <h1>Building Amenities</h1>
      </div>

<?php endif; ?>

      <div class='amenity-gallery break-container'>
        <?php echo $page->gallery; ?>
      </div>

      <div class='amenity-list-wrapper break-container'>
        
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
    <!-- /article.amenities -->
  </main>
  <!-- /main -->
  <?php get_footer(); ?>

<?php else : ?>

    </section>
    <!-- /section.amenities -->
  <?php unset($page); ?>

<?php endif; ?>



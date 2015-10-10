<?php
/*
Template Name: Amenities
*/
?>

<?php 
  // Load Amenities
  $amenities = new WP_Query( array( 
      'post_type'   => 'amenity', 
      'orderby'     => 'post_title',
      'post_status' => 'publish'
    ));
  $page = create_dunnbuilding_page( get_page_by_path( '/amenities' ) );
?>
    <!-- section.amenities -->
    <section class='amenities'>
      <!-- section-inner.break-container -->
      <div class='section-inner break-container'>
        <h1>Building Amenities</h1>
      </div>

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
    </section>
    <!-- /section.amenities -->

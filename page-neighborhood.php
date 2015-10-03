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
  ?>

<?php if(!$home_section) : ?>
  <!-- main -->
  <main role="main" <?php body_class() ?>>
    <!-- article.neighborhood --> 
    <article class='neighborhood'>
      <!-- section-inner.break-container -->
      <div class='section-inner'>
        <h1 class='break-container'><?php the_title(); ?></h1>


<?php else : ?>

    <!-- section.neighborhood -->
    <section class='neighborhood'>
      <!-- section-inner.break-container -->
      <div class='section-inner'>
        <h1 class='break-container'><?php echo $page->post_title; ?></h1>

<?php endif; ?>

        <div class='map-wrapper'>
          <div class='google-map' data-lat='<?php echo $page->acf->google_map->lat; ?>' data-lng='<?php echo $page->acf->google_map->lng; ?>'></div>
        </div>
        <div class='break-container'>
          <div class='content-wrapper'>

      <?php if($home_section) : ?>
        
            <p class='cta'>Check out more of the neighborhood <a href='<?php echo site_url(); ?>/neighborhood/' class='link cta'>here.</a></p>

            <div class='image-column xs-hide'>
              <div class='image-wrapper'>
                <?php echo create_image_html( $page->acf->landing_page_image, true, false ); ?>
              </div>
            </div>

            <div class='content-column'>
              <?php echo apply_filters( 'the_content', $page->post_content ); ?>
            </div>

      <?php else: ?>

          <?php 
              // Load Amenities
              $attractions = new WP_Query( array( 
                  'post_type'   => 'attraction', 
                  'orderby'     => 'menu_order',
                  'post_status' => 'publish'
                ));
              wp_reset_postdata();
            ?>
            
            <div class='attractions-list-wrapper'>

              <div class='title-row'>
                <h2>Neighborhood Attractions</h2>
                <a href="<?php echo site_url(); ?>/apartments/" class='btn btn-block xs-hide'>View Apartments</a>
              </div>

              <ul class='attractions-list'>
                
          <?php 
              while ( $attractions->have_posts() ) : $attractions->the_post(); 
                $attraction = create_dunnbuilding_post_item( $post );
                ep( $attraction );
              ?>
                
                <li class='attractions-item'>
                  <div class='attractions-item-inner'>
                    <div class='image-wrapper'>
                      <?php echo create_image_html( $attraction->image, false, false ); ?>
                    </div>
                    <h3><?php the_title(); ?></h3>
                    <div class='attractions-content'>
                      <?php the_content(); ?>
                    </div>
                  </div>
                </li>
                
                <?php endwhile; ?>

              </ul>

            </div>


      <?php endif; ?>

          </div>
        </div>
        

<?php if(!$home_section) : ?>
      </div> 
      <!-- /div.section-inner.break-container -->
    </article>
    <!-- /article.neighborhood -->
  </main>
  <!-- /main -->
  <?php get_footer(); ?>

<?php else : ?>
      </div> 
      <!-- /div.section-inner.break-container -->
    </section>
    <!-- /section.neighborhood -->

  <?php 
    wp_reset_postdata();
    unset($post); 
    ?>

<?php endif; ?>
<?php
/*
Template Name: Apartments
*/
?>

<?php 

  $uri =  parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
  $paths = preg_split( '/\//', preg_replace( '/^\/|\/$/', '', $uri ) );

  // Settings for complex apartments page
  $settings = new stdClass();

  // Settings Conditions
  $settings->is_home_page = is_front_page();
  $settings->is_apartments_page = is_page_template( 'page-apartments.php' );
  $settings->is_type_page = is_single() && $post->post_type === 'unit_type';
  $settings->is_floorplans = $paths[2] === 'floorplans';

  // Apartments Page
  $apartments_post = ($settings->is_apartments_page) ? $post : get_page_by_path( '/apartments' );
  $apartments_page = create_dunnbuilding_page( $apartments_post ); 

  // Apartment Types
  $apartments_all = new WP_Query( array( 
    'post_type'   => 'unit_type', 
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
  ));

  // Apartment Type Page
  if( $settings->is_type_page ) :
    $apt = create_apartment_type( $post );
  elseif( $apartments_all->have_posts() ) :
    $apt = create_apartment_type( $apartments_all->posts[0] );
  else :
    $apt = false;
  endif;

  $apartment_type = ($apt) ? $apt->ID : "";
  wp_reset_postdata();

  $body_classes = "";
  if( $settings->is_apartments_page ) {
    // $body_classes .= "name-apartments";
  }
  if( $settings->is_type_page || $settings->is_floorplans ) {

    $units = new WP_Query( array( 
      'post_type'   => 'unit', 
      'post_status' => 'publish',
      'posts_per_page' => 100,
      'meta_query' => array(
        array( 
          'key' => 'apartment_type',
          'value' => $apartment_type,
          'compare' => '=',
        ),
        array( 
          'key' => 'availability_available',
          'value' => "true",
          'compare' => '=',
        ),
      )
    ));

    $floorplan_uniques = array();
    while( $units->have_posts() ) : $units->the_post();
      $unit_floorplan = get_post_meta( $post->ID, 'floor_plan_type', true );
      if( isset( $unit_floorplan[0] ) ){
        $floorplan_uniques[ $unit_floorplan[0] ] = true;
      }
    endwhile;
    $floorplan_ids = array_keys( $floorplan_uniques );

    $floorplans = get_posts( array(
        'post_type' => 'floorplan',
        'post__in' => $floorplan_ids,
      ));
    wp_reset_postdata();
  }

  // If loading as an independent page
  if(!$settings->is_home_page) get_header();
 ?>

<?php if(!$settings->is_home_page) : ?>
  <!-- main.apartments -->
  <main role="main" <?php body_class( $body_classes ) ?>>
    <!-- article.apartments -->
    <article class='apartments'>

<?php else : ?>

    <div class='home-apartments-wrapper'>

<?php endif; ?>

    
      <section class='apartments-header'>

        <div class='gallery-wrapper'>
          <div class='section-inner break-container'>
            <h1><?php echo $apartments_page->post_title; ?></h1>
          </div>
          <?php echo $apartments_page->gallery; ?>
        </div><!-- /div.gallery-wrapper -->
        
        <div class='section-inner break-container'>

          <div class='nav-wrapper'>
            <nav class='unit-type-nav'> 
              <ul class='unit-type-list'>
          
          <?php 
            // Create the Apartment Type Navigation
            while ( $apartments_all->have_posts() ) : 
              $apartments_all->the_post();
              $apt_link = create_apartment_type( $post );
              //unset( $apt );
            ?> 
                <li>
                  <a class='btn unit-type-link' href="/apartments/<?php echo $apt_link->post_name; ?>/" data-name="<?php echo $apt_link->post_name; ?>">
                    <span class='label'><?php echo $apt_link->post_title; ?></span>
                  </a>
                </li>

          <?php endwhile; ?>

              </ul>
            </nav>
          </div><!-- /div.nav-wrapper -->
        </div>
      </section><!-- /section.apartments.header-->

      <section class='apartment-types type-<?php echo $apt->post_name; ?>'>

        <article class='apartment-type <?php echo $apt->post_name; ?>' data-type='<?php echo $apt->post_name; ?>'>
          
          <section class='apartment-type-content break-container'> 

            <div class='apartment-type-content-inner'>

              <div class='apartment-type-title xs-show'>
                <h1><?php echo $apt->post_title; ?></h1>
              </div><!-- /div.apartment-type-title -->
              
              <div class="apartment-type-gallery">
                <?php if( $apt->gallery ) echo $apt->gallery; ?>
              </div><!-- /div.apartment-type-gallery -->

              <div class='apartment-type-info'>
                <?php echo apply_filters( 'the_content', $apt->post_content ); ?>

              <?php if(!$settings->is_home_page) : ?>

                  <a class='site-link' href="<?php echo site_url(); ?>/amenities/">
                    <span class='label'>Building Amenities</span>
                  </a>
                  <a class='site-link' href="<?php echo site_url(); ?>/neighborhood/">
                    <span class='label'>Neighborhood Attractions</span>
                  </a>

              <?php endif; ?>

              </div><!-- /div.apartment-type-info -->

              <div class='apartment-type-cta'> 
                <nav class='unit-type-sections'>
                  <ul>

            <?php if($settings->is_home_page) : // include details link on home page ?>

                    <li>
                      <a href="/apartments/<?php echo $apt->post_name; ?>/" class='unit-type-sections-link'>
                        <span class='label'>Details</span>
                      </a>
                    </li>

            <?php endif; ?>

                    <li>
                      <a href="/apartments/<?php echo $apt->post_name; ?>/floorplans" class='unit-type-sections-link'>
                        <span class='label'>Floor Plans</span>
                      </a>
                    </li>
                    <li>
                      <a href="#rent-now" class='unit-type-sections-link'>
                        <span class='label'>Rent Now</span>
                      </a>
                    </li>
                  </ul>
                </nav>
              </div><!-- /div.apartment-type-cta -->

            </div><!-- /div.apartment-type-content-inner -->

          </section><!-- /section.apartment-type-content.break-container -->


        <?php if($floorplans)  : ?>

          <section class="apartment-type-floorplans break-container">
            <h1 class='xs-show'><?php echo $apt->post_title; ?> Floor Plans</h1>
            <ul class='floorplans-list'>
    
        <?php foreach( $floorplans as $fp ) : $fp = create_dunnbuilding_floorplan( $fp ); ?>

              <li class='floorplan-item <?php echo $fp->layout_class; ?>'>
                <div class='floorplan-inner <?php echo $fp->post_name; ?>'>
                  
                  <div class='floorplan-title'>
                    <h2>Floor Plan: <?php echo $fp->post_title; ?></h2>
                  </div>
                  
                  <div class='floorplan-image'>
                    <?php echo create_image_html( $fp->image, false, false ); ?>
                  </div>
                  
                  <div class='floorplan-info'>
                    <?php echo apply_filters( 'the_content', $fp->post_content ); ?>
                  </div>
                  
                  <div class='floorplan-gallery'>
                    <h3><?php echo $fp->post_title; ?> Gallery</h3>
                    <?php echo $fp->gallery; ?> 
                  </div>
                  
                  <div class='floorplan-cta'>
                    <a href="<?php echo $fp->pdf->url; ?>" target="_blank" class='btn btn-shadow'>
                      <span class='shadow'></span>
                      <span class='label'>View PDF</span>
                    </a>
                    <a href="#rent-now" class='btn btn-shadow'>
                      <span class='shadow'></span>
                      <span class='label'>Rent Now</span>
                    </a>
                  </div>
                </div>
              </li>

        <?php endforeach; // foreach( $floorplans as $fp ): ?>

            </ul>
          </section><!-- /section.apartment-type-floorplans -->

        <?php endif; // if($floorplans): ?>

          <!-- /article.apartment-type.<?php echo $apt->post_name; ?> -->
        </article>

      </section><!-- /section.apartment-types -->

<?php if($settings->is_home_page) : ?>
    
    </div><!-- /div.landingpage-apartments-wrapper -->

<?php else : ?>

      <?php //get_template_part( 'page-amenities' ); ?>
      
    </article>
    <!-- /article -->
  </main>
  <!-- /main -->

  <?php get_footer(); ?>

<?php endif; ?>
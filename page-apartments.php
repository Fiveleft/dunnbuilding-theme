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
  $settings->is_amenities = $paths[2] === 'building-amenities';

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

  // ep( $apartments_all->posts );

  // Apartment Type Page
  if( $settings->is_type_page ) :
    $apt = create_apartment_type( $post );
  elseif( $apartments_all->have_posts() ) :
    $apt = create_apartment_type( $apartments_all->posts[0] );
  else :
    $apt = false;
  endif;

  $apartment_type_name = ($apt) ? $apartments_page->post_name : "";
  wp_reset_postdata();

  $body_classes = "";
  if( $settings->is_apartments_page ) {
    // $body_classes .= "name-apartments";
  }
  if( $settings->is_type_page ) {
    // $body_classes .= "name-apartment-type";
  }
  if( $settings->is_floorplans ) {

  }
  if( $settings->is_amenities ) {
    
  }


  // If loading as an independent page
  if(!$settings->is_home_page) get_header();
 ?>

<?php if(!$settings->is_home_page) : ?>
  <!-- main.apartments -->
  <main role="main" <?php body_class( $body_classes ) ?>>
    <!-- article -->
    <article class='apartments'>

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
                  <a class='btn unit-type-link' href="/apartments/<?php echo $apt_link->post_name; ?>/">
                    <span class='label'><?php echo $apt_link->post_title; ?></span>
                  </a>
                </li>

          <?php endwhile; ?>

              </ul>
            </nav>
          </div><!-- /div.nav-wrapper -->
        </div>
      </section><!-- /section.apartments.header-->

      <section class='apartment-type-info type-<?php echo $apartment_type_name; ?>'>

        <article class='apartment unit-type <?php echo $apt->post_name; ?>' data-type='<?php echo $apt->post_name; ?>'>
          <div class='article-inner break-container'> 
            
            <section class="section-gallery">
              <div class='section-inner'>
                <?php if( $apt->gallery ) echo $apt->gallery; ?>
                <nav class='unit-type-sections'>
                  <ul>

            <?php if($settings->is_home_page) : ?>
                    <li>
                      <a href="/apartments/<?php echo $apt->post_name; ?>" class='unit-type-sections-link'>
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
              </div>
            </section>

            <section class="section-info">
              <div class='section-inner'>
                <h1 class='xs-show'><?php echo $apt->post_title; ?></h1>
                <?php echo apply_filters( 'the_content', $apt->post_content ); ?>

            <?php if(!$settings->is_home_page) : ?>

                <a class='site-link' href="/apartments/<?php echo $apt->post_name; ?>/building-amenities">
                  <span class='label'>Building Amenities</span>
                </a>
                <a class='site-link' href="/neighborhood">
                  <span class='label'>Neighborhood Attractions</span>
                </a>

            <?php endif; ?>

              </div>
            </section>


            <section class="section-available-units">
              <div class='section-inner'>
                <ul class='available-units-list'>
                  <li>
                    <div class='available-unit'>

                    </div>
                  </li>
                </ul>
              </div>
            </section>
          </div>
        </article>

      </section><!-- /section.apartment-type-info -->

<?php if(!$settings->is_home_page) : ?>

      <?php get_template_part( 'page-amenities' ); ?>
      
    </article>
    <!-- /article -->
  </main>
  <!-- /main -->

  <?php get_footer(); ?>

<?php else : ?>

  <?php unset($page); ?>

<?php endif; ?>
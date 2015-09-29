
<?php 
  $apt = create_apartment_type( $post );
  $units = new WP_Query( array(
    'post_type'   => 'unit',
    'post_status' => 'publish',
  ));
  ?>

  <article class='apartment unit-type <?php echo $apt->post_name; ?>' data-type='<?php echo $apt->post_name; ?>'>

    <div class='article-inner <?php if( $is_own_page ) echo "break-container"; ?>'> 
      
      <section class="section-gallery">
        <div class='section-inner'>
          <?php if( $apt->gallery ) echo $apt->gallery; ?>
          <nav class='unit-type-sections'>
            <ul>

      <?php if(!$is_own_page) : ?>
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

      <?php if($is_own_page) : ?>
      
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

          </ul>
        </div>
      </section>

    </div>
  </article>
  <!-- /article.<?php echo $apt->post_name; ?> -->


  <?php 
  // unset( $apt, $units );
  wp_reset_postdata();
  ?>

<?php if( $is_own_page ) : ?>
    <?php get_template_part( 'page-amenities' ); ?>
  </main>
  <?php get_footer(); ?>

<?php endif; ?>
<?php 
  $is_own_page = !is_page_template( 'page-apartments.php' ) && !is_front_page();
  ?>


<?php if( $is_own_page ) : ?>
<?php get_header(); ?>

  <!-- Add Main for Single Unit page type -->
  <main role="main"   <?php body_class() ?>>
    <!-- Add Nav for Single Unit page type -->
  <?php get_template_part( 'nav-apartment-types' ); ?>

<?php endif; ?>

  <?php 
    $apt = create_apartment_type( $post );
    $units = new WP_Query( array(
      'post_type'   => 'unit',
      'post_status' => 'publish',
    ));
    ?>

  <article style="border-top:1px solid #333;" class='apartment unit-type <?php echo $apt->post_name; ?>' data-type='<?php echo $apt->post_name; ?>'>
    <div class='article-inner'>
      <h1><?php echo $apt->post_title; ?></h1>
      
      <section class="apartment-info">
        <div class='section-inner'>
          <?php echo $apt->post_content; ?>
        </div>
      </section>

    <?php if( $apt->gallery ) : ?>
      <section class="apartment-gallery">
        <div class='section-inner'>
          <?php echo $apt->gallery; ?>
        </div>
      </section>
    <?php endif; ?>

      <section class="available-units">
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
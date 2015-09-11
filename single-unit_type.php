<?php 

  $home_section = is_front_page();
  //$is_singular();
  //get_header(); 
  ?>

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

      <section class="apartment-gallery">
        <div class='section-inner'>
        (apartment gallery)
      </section>

      <section class="available-units">
        <div class='section-inner'>
          <ul class='available-units-list'>

          </ul>
        </div>
      </section>

    </div>
  </article>
  <!-- /article.<?php echo $apt->post_name; ?> -->

<?php //get_footer(); ?>
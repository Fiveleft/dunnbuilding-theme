
  <nav class='unit-type-nav'>  
    <?php 
      // Load Apartments
      $apartments = new WP_Query( array( 
        'post_type'   => 'unit_type', 
        'post_status' => 'publish',
        'orderby' => 'menu_order',
        'order' => 'ASC',
      ));
      while ( $apartments->have_posts() ) : 
        $apartments->the_post(); 
        $apt = create_apartment_type( $post );
      ?>
        <a href="/apartments/<?php echo $apt->post_name; ?>" class='btn unit-type-link'>
          <span class='label'><?php echo $apt->post_title; ?></span>
        </a>
    <?php 
      endwhile; 
      wp_reset_postdata();
      ?>
  </nav>


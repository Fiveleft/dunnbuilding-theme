
  <nav class='unit-type-nav'> 
    <ul class='unit-type-list'>
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
        <li>
          <a class='btn unit-type-link' href="/apartments/<?php echo $apt->post_name; ?>/">
            <span class='label'><?php echo $apt->post_title; ?></span>
          </a>
        </li>
    <?php 
      endwhile; 
      wp_reset_postdata();
      ?>
    </ul>
  </nav>


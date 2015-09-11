<?php
/*
Template Name: Apartments
*/
?>

<?php 
  $home_section = is_front_page();
  // If loading as an independent page
  if(!$home_section) { 
    get_header();
  }else{
    $page = get_page_by_path( '/apartments' ); 
  }

  // Load Amenities
  $apartments = new WP_Query( array( 
      'post_type'   => 'unit_type', 
      'post_status' => 'publish'
    ));

 ?>

<?php if(!$home_section) : ?>
  <!-- main.apartments -->
  <main role="main">
    <!-- article -->
    <article>
      <section class='apartments'>
        <h1><?php the_title(); ?></h1>

<?php else : ?>

    <!-- section.apartments -->
    <section class='apartments'>
      <h1><?php echo $page->post_title; ?></h1>
      <a href="/apartments">go to page</a>

<?php endif; ?>

      <div class='apartments-gallery'>
        (main gallery)
      </div>


      <nav class='unit-type-nav'>
    
    <?php while ( $apartments->have_posts() ) : 
      $apartments->the_post(); 
      $apt = create_apartment_type( $post );
      ?>
        <a href="/apartments/<?php echo $apt->post_name; ?>" class='btn unit-type-link'>
          <span class='label'><?php echo $apt->post_title; ?></span>
        </a>
    <?php endwhile; ?>

      </nav>

      <!-- div.unit-types -->
      <div class='unit-types'>
    <?php while ( $apartments->have_posts() ) : 
        $apartments->the_post(); 
        $apt = create_apartment_type( $post );
      ?>
        <article class='apartment unit-type <?php echo $apt->post_name; ?>' data-type='<?php echo $apt->post_name; ?>'>
          <div class='article-inner'>
            <h1><?php echo $apt->post_title; ?></h1>
            <div class='content-wrapper'>
              <?php echo $apt->post_content; ?>
            </div>
          </div>
        </article>
        <!-- /article.<?php echo $apt->post_name; ?> -->
    <?php endwhile; ?>
      </div>
      <!-- /div.unit-types -->





<?php if(!$home_section) : ?>

      <?php get_template_part( 'page-amenities' ); ?>
    </article>
    <!-- /article -->
  </main>
  <!-- /main -->
  <?php get_footer(); ?>

<?php else : ?>

    </section>
    <!-- /section.amenities -->
  <?php 
    wp_reset_postdata();
    unset($page); 
    ?>

<?php endif; ?>
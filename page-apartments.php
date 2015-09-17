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
    $page = create_dunnbuilding_page( $post );
  }else{
    $page = create_dunnbuilding_page( get_page_by_path( '/apartments' ) ); 
  }
 ?>

<?php if(!$home_section) : ?>
  <!-- main.apartments -->
  <main role="main" <?php body_class() ?>>
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

    <?php if( $page->gallery ) : ?>
      <?php echo $page->gallery; ?>
    <?php endif; ?>

    <?php get_template_part( 'nav', 'apartment-types' ); ?>

      <!-- div.unit-types -->
      <div class='unit-types'>

    <?php 

      // Load Apartments
      $apartments = new WP_Query( array( 
        'post_type'   => 'unit_type', 
        'post_status' => 'publish',
        'posts_per_page' => '1',
        'orderby' => 'menu_order',
        'order' => 'ASC',
      ));
      if( $apartments->have_posts() ):
        // $post_tmp = $post;
        while ( $apartments->have_posts() ) : 
          $apartments->the_post();
          include(locate_template('single-unit_type.php'));
        endwhile;
        $post = $post_tmp;
      endif;
      wp_reset_postdata();

      ?>

      </div>
      <!-- /div.unit-types -->





<?php if(!$home_section) : ?>

      <?php 
        get_template_part( 'page-amenities' ); 
        ?>
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
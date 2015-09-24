<?php get_header(); ?>

<main role="main" <?php body_class() ?>>
	<!-- section -->
	<section class='welcome'>
    <!-- section-inner.break-container -->
    <div class='section-inner break-container'>
		  <h1><?php the_title(); ?></h1>
    </div> 
    <!-- /div.section-inner.break-container -->
  </section>
	<!-- /section -->

  <!-- apartments -->
  <?php get_template_part( 'page', 'apartments' ); ?>
  <!-- /apartments -->

  <!-- amenities -->
  <?php get_template_part( 'page', 'amenities' ); ?>
  <!-- /amenities -->

  <!-- history -->
  <?php get_template_part( 'page', 'building-history' ); ?>
  <!-- /history -->

  <!-- neighborhood -->
  <?php get_template_part( 'page', 'neighborhood' ); ?>
  <!-- /neighborhood -->

</main>

<?php get_footer(); ?>

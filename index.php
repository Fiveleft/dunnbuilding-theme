<?php get_header(); ?>

<main role="main">
	<!-- section -->
	<section>
		<h1><?php _e( 'Latest Posts', 'html5blank' ); ?></h1>
	</section>
	<!-- /section -->

  <!-- apartments -->
  <?php get_template_part( 'content', 'apartments' ); ?>
  <!-- /apartments -->

  <!-- amenities -->
  <?php get_template_part( 'content', 'amenities' ); ?>
  <!-- /amenities -->

  <!-- history -->
  <?php get_template_part( 'content', 'history' ); ?>
  <!-- /history -->

  <!-- neighborhood -->
  <?php get_template_part( 'content', 'neighborhood' ); ?>
  <!-- /neighborhood -->

</main>

<?php get_footer(); ?>

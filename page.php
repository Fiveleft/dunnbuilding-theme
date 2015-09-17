<?php get_header(); ?>

	<main role="main"  <?php body_class() ?>>

<?php if(is_front_page()) : ?>

	<!-- welcome -->
	<section class='welcome'>
		<h1><?php the_title(); ?></h1>
	</section>
	<!-- /welcome -->
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


<?php else : ?>

		<!-- section -->
		<section>
			<h1><?php the_title(); ?></h1>

		<?php if (have_posts()): while (have_posts()) : the_post(); ?>
			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php the_content(); ?>
				<?php // edit_post_link(); ?>
			</article>
			<!-- /article -->
		<?php endwhile; ?>

		<?php else: ?>
			<!-- article -->
			<article>
				<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
			</article>
			<!-- /article -->
		<?php endif; ?>

		</section>
		<!-- /section -->

	<?php endif; ?>
	</main>

<?php get_footer(); ?>

<?php get_header(); ?>

	<main role="main"  <?php body_class() ?>>

<?php 
  if(is_front_page() && !is_404()) : 

    $page = create_dunnbuilding_page( $post );

    ?>
    <article class='landing-page'>
    	<!-- welcome -->
    	<section class='welcome'>
        <!-- section-inner.break-container -->
        <div class='section-inner break-container'>
          <h1><?php the_title(); ?></h1>
          <div class='gallery-wrapper'>
            <?php echo $page->gallery; ?>
          </div>
          <nav class='welcome-nav'> 
            <ul class='unit-type-list'>
              <li>
                <a class='btn btn-shadow' href="/apartments/">
                  <span class='shadow'></span>
                  <span class='label'>Apartments</span>
                  <span class='sub-label'>See Floor Plans</span>
                </a>
              </li>
              <li>
                <a class='btn btn-shadow' href="#amenities">
                  <span class='shadow'></span>
                  <span class='label'>Amenities</span>
                  <span class='sub-label'>Roof Deck &amp; More</span>
                </a>
              </li>
              <li>
                <a class='btn btn-shadow' href="/neighborhood/">
                  <span class='shadow'></span>
                  <span class='label'>Location</span>
                  <span class='sub-label'>About Capitol Hill</span>
                </a>
              </li>
            </ul>
          </nav>
        </div> 
        <!-- /div.section-inner.break-container -->
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
    </article>

<?php elseif (have_posts()): ?>

  <?php while (have_posts()) : the_post(); ?>
		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <div class='content-wrapper break-container'>
        <h1><?php the_title(); ?></h1>
			  <?php the_content(); ?>
      </div>
		</article>
		<!-- /article -->
	<?php endwhile; ?>

<?php else: ?>

		<!-- article -->
		<article>
      <div class='content-wrapper break-container'>
        <h1>Sorry, we couldn&rsquo;t find that page.</h1>
      </div>
		</article>
		<!-- /article -->

<?php endif; ?>
	
  </main>

<?php get_footer(); ?>

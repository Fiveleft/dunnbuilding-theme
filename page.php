<?php get_header(); ?>

	<main role="main"  <?php body_class() ?>>

<?php 
  if(!is_404()) : 
    if( !is_front_page() ) :
      $page = create_dunnbuilding_page( get_post( get_option('page_on_front') ) );
    else :
      $page = create_dunnbuilding_page( $post );
    endif; 

    ?>
    <article class='landing-page'>
    	<section class='welcome'>
        <!-- section-inner.break-container -->
        <div class='section-inner'>
          <div class='break-container'>
            <h1><?php the_title(); ?></h1>
          </div>
          <div class='gallery-wrapper'>
            <?php echo $page->gallery; ?>
          </div>
          <div class='break-container'>
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
                  <a class='btn btn-shadow' data-scrollto='true' href="#amenities">
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
        </div> 
        <!-- /div.section-inner.break-container -->
    	</section>
      <?php get_template_part( 'page', 'apartments' ); ?>
      <?php get_template_part( 'page', 'amenities' ); ?>
      <?php get_template_part( 'page', 'building-history' ); ?>
      <?php get_template_part( 'page', 'neighborhood' ); ?>
    </article>

<?php elseif (have_posts()): ?>

  <?php while (have_posts()) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <div class='content-wrapper break-container'>
        <h1><?php the_title(); ?></h1>
			  <?php the_content(); ?>
      </div>
		</article>
	<?php endwhile; ?>

<?php else: ?>

		<article>
      <div class='content-wrapper break-container'>
        <h1>Sorry, we couldn&rsquo;t find that page.</h1>
      </div>
		</article>

<?php endif; ?>
	
  </main>

<?php get_footer(); ?>

<?php
/*
Template Name: History
*/
?>

<?php 
  $home_section = is_front_page();
  // If loading as an independent page
  if(!$home_section) { 
    get_header();
  }else{
    $page_section = get_page_by_path( '/building-history' ); 
  }
  $page = create_dunnbuilding_page( isset( $page_section ) ? $page_section : $post );
  ep( $page );
  ?>

<?php if(!$home_section) : ?>
  
  <!-- main.building-history -->
  <main role="main" class='name-<?php echo $page->post_name; ?> color-dark-on-brown'>
    <!-- article.building-history --> 
    <article class='building-history'>

<?php else : ?>
  
    <!-- section.building-history -->
    <section class='building-history'>

<?php endif; ?>
      
      <div class='building-history-inner'>
      
        <div class='hero-wrapper hero-wrapper-image'>
          <div class='hero-image'>
            <?php echo create_image_html( $page->acf->landing_page_image, true, false ); ?>
          </div>
        </div>
        <div class='hero-wrapper hero-wrapper-headline'>
          <div class='hero-headline'>
            <div class='hero-headline-inner break-container'>
        
        <?php if(!$home_section) : ?>
          
              <div class='headline-block'>
                <h1><?php echo $page->post_title; ?></h1>
                <?php echo $page->acf->history_page_excerpt; ?>
              </div>
    
        <?php else : ?>
            
              <div class='headline-wrapper'>
                <h1 class='headline-landing-page'><?php echo $page->acf->landing_page_title; ?></h1>
              </div>
              <div class='headline-info'>
                <p><?php echo apply_filters( 'the_content', $page->acf->landing_page_description ); ?></p>
                <a href="/building-history"><?php echo $page->acf->landing_page_cta; ?></a>
              </div>

        <?php endif; ?>

            </div><!-- /div.hero-headline-inner -->
          </div><!-- /div.hero-headline -->
        </div><!-- /div.hero-wrapper -->
      </div><!-- /div.building-history-inner -->      

<?php if(!$home_section) : ?>
        <!-- section-inner.break-container -->
        <div class='section-inner break-container'>
          <?php echo apply_filters( 'the_content', $page->post_content ); ?>
        </div>
        <!-- /div.section-inner.break-container -->
<?php endif; ?>

      </div>
      <!-- /div.building-history-inner -->


<?php if(!$home_section) : ?>
    </article>
    <!-- /article.building-history -->
  </main>
  <!-- /main -->
  <?php get_footer(); ?>

<?php else : ?>
    </section>
    <!-- /section.building-history -->
  <?php 
    wp_reset_postdata();
    // unset($page); 
    ?>

<?php endif; ?>
    


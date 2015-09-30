<!doctype html>
<html lang="en" xml:lang="en" class="no-js">
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

    <link href="//www.google-analytics.com" rel="dns-prefetch">
    <link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
    <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Content-Language" content="en">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">

    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>

    <!-- header -->
    <header class="site-header" role="banner">
      <div class="header-inner break-container">
        <div class="logo">
          <a href="<?php echo home_url(); ?>">
            <!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
            <?php get_template_part( "img/inline", "logo.svg" ); ?>
          </a>
        </div>
        <div class='extras'>
          <button class="rent-now">
            <span class="feedback"></span>
            <span class="label">Rent Now</span>
          </button>
          <a class='btn tel' href="tel:555-555-5555" aria-hidden="true" hidden>555-555-5555</a>
          <br/>
          <a class='btn live-chat-link' href="#live-chat" aria-hidden="true" hidden>Live Chat</a>
          <button class="menu-toggle">
            <span class="label">Menu</span>
          </button> 
        </div>
      </div>
    </header>
    <!-- /header -->

    <!-- nav -->
    <nav class="main-nav" role="navigation">
      <div class="nav-inner">
         <div class='main-nav-header'>
        <div class="logo">
          <a href="<?php echo home_url(); ?>">
            <?php get_template_part( "img/inline", "logo.svg" ); ?>
          </a>
        </div>
        <button class='close'>
          <span class='label'>Close Menu</span>
        </button>
      </div>
        <?php
          wp_nav_menu(array(
            'theme_location'    => 'header-menu',
            'container'         => false,
            'menu_id'           => null,
            'menu_class'        => 'header-nav-menu',
            'link_before'       => "<span class='label'>",
            'link_after'        => "</span>",
            // 'items_wrap'        => '<ul class="%2$s" aria-hidden="true" hidden>%3$s</ul>'
          )); ?>
      </div>
    </nav>
    <!-- /nav -->

    <!-- wrapper -->
    <div class="wrapper">


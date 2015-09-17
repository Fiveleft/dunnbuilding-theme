<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
  <head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

    <link href="//www.google-analytics.com" rel="dns-prefetch">
    <link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
    <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">

    <script>
      // Font Loading
      (function(d) {
        var config = { kitId: 'qei2xts', scriptTimeout: 3000, async: true },
        h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
      })(document);
    </script>


    <?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>

    <!-- header -->
    <header class="header" role="banner">
      <div class="header-inner">
        <!-- logo -->
        <div class="logo">
          <a href="<?php echo home_url(); ?>">
            <?php get_template_part( "img/inline", "logo.svg" ); ?>
            <!-- svg logo - toddmotto.com/mastering-svg-use-for-a-retina-web-fallbacks-with-png-script -->
            <!-- <img src="<?php //echo get_template_directory_uri(); ?>/img/logo.svg" alt="Logo" class="logo-img"> -->
          </a>
        </div>
        <!-- /logo -->

        <button class="chat-now" aria-hidden="true" hidden>
          <span class="feedback"></span>
          <span class="label">Chat Now</span>
        </button>

        <button class="rent-now">
          <span class="feedback"></span>
          <span class="label">Rent Now</span>
        </button>

        <button class="menu-toggle">
          <span class="label">Menu</span>
        </button> 
      </div>
    </header>
    <!-- /header -->

    <!-- rent application module -->
    <div class='rent-application-module-wrapper'>
      <?php get_template_part( 'module-rent-application' ); ?>
    </div>

    <!-- nav -->
    <nav class="main-nav" role="navigation" aria-hidden="true" hidden>
      <div class="nav-inner">
        <?php
          wp_nav_menu(array(
            'theme_location'    => 'header-menu',
            'container'         => false,
            'menu_id'           => null,
            'menu_class'        => 'header-nav-menu',
            // 'items_wrap'        => '<ul class="%2$s" aria-hidden="true" hidden>%3$s</ul>'
          )); ?>
      </div>
    </nav>
    <!-- /nav -->

    <!-- wrapper -->
    <div class="wrapper">


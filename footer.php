    
    </div>
    <!-- /wrapper -->

    <footer class="site-footer" role="contentinfo">
      <nav class='footer-nav break-container'>
        <div class="nav-inner">
          <p class="copyright">
            &copy; <?php echo date('Y'); ?> Copyright <?php bloginfo('name'); ?>.
          </p>
          <?php
            wp_nav_menu(array(
              'theme_location'    => 'footer-menu',
              'container'         => false,
              'menu_id'           => null,
              'menu_class'        => 'footer-menu',
            )); 
            wp_nav_menu(array(
              'theme_location'    => 'social-menu',
              'container'         => false,
              'menu_id'           => null,
              'menu_class'        => 'social-menu',
            )); 
            ?>
        </div>
      </nav>
    </footer>
    
    <div class='main-loader' aria-hidden='true' hidden>
      <!-- load main content into this container -->
    </div>

    <div class='overlay-container' aria-hidden='true' hidden>
      <div class='rent-application modal-wrapper'>
        <?php get_template_part( 'module-rent-application' ); ?>
      </div>
      <progress value="100" max="100">
        <span class="loading-message">Loading</span>
      </progress>
      <button class='close'>
        <span class='label'>Close Menu</span>
        <?php get_template_part( "img/inline", "close-icon.svg" ); ?>
      </button>
    </div>

    <?php wp_footer(); ?>

    <!-- maps api --> 
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false&callback=initMap" async defer></script>

    <!-- analytics -->
    <script>
    // (function(f,i,r,e,s,h,l){i['GoogleAnalyticsObject']=s;f[s]=f[s]||function(){
    // (f[s].q=f[s].q||[]).push(arguments)},f[s].l=1*new Date();h=i.createElement(r),
    // l=i.getElementsByTagName(r)[0];h.async=1;h.src=e;l.parentNode.insertBefore(h,l)
    // })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    // ga('create', 'UA-XXXXXXXX-XX', 'yourdomain.com');
    // ga('send', 'pageview');
    </script>

  </body>
</html>

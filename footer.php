    
    </div>
    <!-- /wrapper -->

    <!-- footer -->
    <footer class="site-footer" role="contentinfo">
      <div class="footer-fixed">
      </div>
      <nav class='footer-nav break-container'>
        <div class="nav-inner">
          <!-- copyright -->
          <p class="copyright">
            &copy; <?php echo date('Y'); ?> Copyright <?php bloginfo('name'); ?>.
          </p>
          <!-- /copyright -->

        
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
    <!-- /footer -->

    <div class='main-loader' aria-hidden='true' hidden>
      <!-- load main content into this container -->
    </div>

    <div class='overlay-container' aria-hidden='true' hidden>
      <div class='rent-application module-wrapper'>
        <?php get_template_part( 'module-rent-application' ); ?>
      </div>
      <progress value="100" max="100">
        <span class="loading-message">Loading</span>
      </progress>
    </div>

    <?php wp_footer(); ?>

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

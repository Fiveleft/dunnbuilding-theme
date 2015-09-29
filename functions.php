<?php
/*
 *  Author: Todd Motto | @toddmotto
 *  URL: html5blank.com | @html5blank
 *  Custom functions, support, custom post types and more.
 */


function ep( $content ) {
  error_log( print_r( $content, true ) );
}



/*------------------------------------*\
  External Modules/Files
\*------------------------------------*/

// Load any external files you have here
require_once( 'custom-post-unit.php' );
require_once( 'custom-post-unit-type.php' );
require_once( 'custom-post-floor-plans.php' );
require_once( 'custom-post-amenities.php' );
require_once( 'custom-post-attractions.php' );
require_once( 'custom-taxonomies.php' );
require_once( 'custom-gallery-shortcodes.php' );
require_once( 'custom-settings-page.php' );

/*------------------------------------*\
  Theme Support
\*------------------------------------*/

function custom_menu_page_removing() {
  remove_menu_page( 'edit.php' );
  remove_menu_page( 'edit-comments.php' );
}
add_action( 'admin_menu', 'custom_menu_page_removing' );

if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 1500, '', true); // Large Thumbnail
    add_image_size('medium', 750, '', true); // Medium Thumbnail
    add_image_size('small', 375, '', true); // Small Thumbnail
    // add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('html5blank', get_template_directory() . '/languages');
}



/**
 * [create_apartment_type description]
 * @param  WP_Post $post [description]
 * @return [type]       [description]
 */
function create_apartment_type( $post ) 
{
  $apt = $post;
  $apt->meta = get_post_meta( $post->ID );

  $acf = (function_exists('get_fields')) ? get_fields($apt->ID) : false;
  $apt->acf = $acf ? json_decode(json_encode($acf), FALSE) : false;

  if( $acf['gallery_shortcode'] ) {

    $gallery = preg_replace( '/gallery/', 'apartment_gallery', $acf['gallery_shortcode'] );
    
    // // Odd hack of adding incorrect quotes around attribute value?
    $gallery = preg_replace( '/\&#8221;|\&#8243;/', '', $gallery );

    // Create the apartment_gallery shortcode
    $apt->gallery = apply_filters( 'the_content', $gallery );
  }




  return $apt;
}


/**
 * [create_dunnbuilding_page description]
 * @param  WP_Page $page [description]
 * @return [type]       [description]
 */
function create_dunnbuilding_page( $page ) 
{
  ep( $page );

  $db_page = $page;
  $db_page->meta = get_post_meta( $page->ID );

  $acf = (function_exists('get_fields')) ? get_fields($db_page->ID) : false;
  $db_page->acf = $acf ? json_decode(json_encode($acf), FALSE) : false;

  // Check for gallery shortcode
  $sc_pattern = '\[(\[?)(gallery)(?![\w-])([^\]\/]*(?:\/(?!\])[^\]\/]*)*?)(?:(\/)\]|\](?:([^\[]*+(?:\[(?!\/\2\])[^\[]*+)*+)\[\/\2\])?)(\]?)';
  preg_match_all( '/' . $sc_pattern . '/', $page->post_content, $matches );

  if( $matches[0] ) {

    $gallery = preg_replace( '/gallery/', 'page_gallery', $matches[0] );
    $gallery = preg_replace( '/\&#8221;|\&#8243;/', '', $gallery[0] );

    // Create the apartment_gallery shortcode
    $db_page->gallery = apply_filters( 'the_content', $gallery );

    // Remove the Gallery shortcode from the content
    $db_page->post_content = preg_replace( '/' . $sc_pattern . '/', '', $page->post_content );
  } else {
    $db_page->gallery = "<div class='gallery'>no gallery</div>";
  }
  return $db_page;
}


/**
 * [feed_dir_rewrite description]
 * @param  [type] $wp_rewrite [description]
 * @return [type]             [description]
 * @see   https://codex.wordpress.org/Class_Reference/WP_Rewrite#Examples
 */
function feed_dir_rewrite( $wp_rewrite ) {
  $apartment_rules = array(
    'apartments/([^/]+)/floorplans' => 'index.php?unit_type=$matches[1]&page=$matches[2]',
    'apartments/([^/]+)/building-amenities' => 'index.php?unit_type=$matches[1]&page=$matches[2]',
  );
  $wp_rewrite->rules = $apartment_rules + $wp_rewrite->rules;
  // ep( $wp_rewrite->rules );
  return $wp_rewrite->rules;
}

// Hook in.
add_filter( 'generate_rewrite_rules', 'feed_dir_rewrite' );






/*------------------------------------*\
  Functions
\*------------------------------------*/


// Load HTML5 Blank scripts (header.php)
function enqueue_header_scripts()
{
  if( is_admin() ) :


  else : 

    // Check for local dev environment
    $is_local_dev = defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV;

    // Find our relative JS directory
    $js_dir = get_stylesheet_directory_uri() . '/js';

    /**
     * @see https://github.com/jrburke/requirejs/wiki/Patterns-for-separating-config-from-the-main-module
     */
    wp_register_script('require-config', $js_dir . '/require-config.js', null, false, true);
    wp_enqueue_script('require-config');

    wp_register_script('require', $js_dir . '/require.js', array('require-config'), false, true);
    wp_enqueue_script('require');

    wp_register_script('main', $js_dir . '/main.js', array('require'), false, true);
    wp_enqueue_script('main');

    wp_register_script('modernizr', $js_dir . '/modernizr.js');
    wp_enqueue_script('modernizr');


    /**
     * Add a localized path to the js directory of the theme for require JS modules
     * @see http://codegeekz.com/using-require-js-with-wordpress/
     * @var array
     */
    $js_localized = array(
      'homeUrl' => home_url(),
      'baseUrl' => get_stylesheet_directory_uri() . '/js',
      'url' => get_stylesheet_directory_uri(),
     );
    if( get_permalink() ) {
      $js_localized['permalink'] = get_permalink();
    }
    wp_localize_script( 'require-config', 'localized', $js_localized );


    /**
     * Add Livereload if we're developing locally
     * @see http://robandlauren.com/2014/02/05/live-reload-grunt-wordpress/
     */
    if ( $is_local_dev ) {
      wp_register_script('livereload', 'http://localhost:35729/livereload.js?snipver=1', null, false, true);
      wp_enqueue_script('livereload');
    }

  endif;


  


}



// Load HTML5 Blank conditional scripts
function enqueue_conditional_scripts()
{
    if (is_page('pagenamehere')) {
        wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), '1.0.0'); // Conditional script(s)
        wp_enqueue_script('scriptname'); // Enqueue it!
    }
}

// Load HTML5 Blank styles
function enqueue_styles()
{
    // wp_register_style('normalize', get_template_directory_uri() . '/normalize.css', array(), '1.0', 'all');
    // wp_enqueue_style('normalize'); // Enqueue it!

    wp_register_style('main_styles', get_template_directory_uri() . '/css/main.css', array(), '1.0', 'all');
    wp_enqueue_style('main_styles'); // Enqueue it!
}

// Register HTML5 Blank Navigation
function register_menus()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'dunnbuilding'), // Main Navigation
        'social-menu' => __('Social Menu', 'dunnbuilding'), // Fotter Navigation if needed (duplicate as many as you need!)
        'footer-menu' => __('Footer Menu', 'dunnbuilding') // Fotter Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    switch( true ) {

      case is_home() :
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
        break;

      case is_page() : 
        $classes[] = "name-" . sanitize_html_class($post->post_name);
        break;

      case is_singular() :
        $classes[] = "name-" . sanitize_html_class($post->post_name);
        break;
    }
    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'html5blank') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function html5blankgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function html5blankcomments($comment, $args, $depth)
{
  $GLOBALS['comment'] = $comment;
  extract($args, EXTR_SKIP);

  if ( 'div' == $args['style'] ) {
    $tag = 'div';
    $add_below = 'comment';
  } else {
    $tag = 'li';
    $add_below = 'div-comment';
  }
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
  <?php if ( 'div' != $args['style'] ) : ?>
  <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
  <?php endif; ?>
  <div class="comment-author vcard">
  <?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
  <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
  </div>
<?php if ($comment->comment_approved == '0') : ?>
  <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
  <br />
<?php endif; ?>

  <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
    <?php
      printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
    ?>
  </div>

  <?php comment_text() ?>

  <div class="reply">
  <?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
  </div>
  <?php if ( 'div' != $args['style'] ) : ?>
  </div>
  <?php endif; ?>
<?php }

/*------------------------------------*\
  Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'enqueue_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'enqueue_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'enqueue_styles'); // Add Theme Stylesheet
add_action('init', 'register_menus'); // Add HTML5 Blank Menu 


add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
// add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'html5blankgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
// add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo'); // You can place [html5_shortcode_demo] in Pages, Posts now.
add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2'); // Place [html5_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]

/*------------------------------------*\
  Custom Post Types
\*------------------------------------*/

?>

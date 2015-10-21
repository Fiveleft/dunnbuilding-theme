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
 * [create_dunnbuilding_floorplan]
 * @param  WP_Post $post [description]
 * @return [type]       [description]
 */
function create_dunnbuilding_floorplan( $post ) 
{
  $fp = create_dunnbuilding_page( $post );
  $fp->image = $fp->acf->floor_plan_image;
  $fp->pdf = $fp->acf->floor_plan_pdf;

  $fp->layout_class = "portrait";

  if( $fp->image->width > $fp->image->height ) :
    $fp->layout_class = "landscape";
  endif;

  return $fp;
}


/**
 * [create_dunnbuilding_post_item description]
 * @param  WP_Post $post [description]
 * @return [type]       [description]
 */
function create_dunnbuilding_post_item( $post ) 
{
  $item = $post;
  $item->featured_image_id = get_post_thumbnail_id( $post->ID );

  if( $item->featured_image_id ) {

    $attachment = get_post( $item->featured_image_id );
  
    ep( "create_dunnbuilding_post_item\n\nattachment:::{$item->featured_image_id}::\n\n" );
    ep( $item->featured_image_id );

    /**
     * Pulled from plugins/advanced-custom-fields/core/fields/image :: function format_value_for_api( $value, $post_id, $field );
     */
    // create array to hold value data
    $src = wp_get_attachment_image_src( $attachment->ID, 'full' );
    
    $value = array(
      'id' => $attachment->ID,
      'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
      'title' => $attachment->post_title,
      'caption' => $attachment->post_excerpt,
      'description' => $attachment->post_content,
      'mime_type' => $attachment->post_mime_type,
      'url' => $src[0],
      'width' => $src[1],
      'height' => $src[2],
      'sizes' => array(),
    );
    
    // find all image sizes
    $image_sizes = get_intermediate_image_sizes();
    
    if( $image_sizes )
    {
      foreach( $image_sizes as $image_size )
      {
        // find src
        $src = wp_get_attachment_image_src( $attachment->ID, $image_size );
        
        // add src
        $value[ 'sizes' ][ $image_size ] = $src[0];
        $value[ 'sizes' ][ $image_size . '-width' ] = $src[1];
        $value[ 'sizes' ][ $image_size . '-height' ] = $src[2];
      }
      // foreach( $image_sizes as $image_size )
    }
    /**
     * END pulled code.
     */

    $item->image = json_decode(json_encode($value), FALSE);

  } 

  return $item;
}



function create_image_html( $image, $use_span=false, $lazy=true, $srcset=true) {

  if( !$image || is_null($image) ) :
    return "<span class='img no-img'></span>";
  endif;

  $html = "";

  $styles = "";

  $classes = "img";
  $classes .= $lazy ? " lazy" : "";
  $classes .= $srcset ? " breakpoints" : "";
  $classes .= isset($image->alt) && count($image->alt) ? " has-alt" : "";

  $aspect = ( intval($image->height) / intval($image->width) ) * 100;


  $attrs = " data-src='" . $image->url . "'";
  $attrs .= " data-width='{$image->width}'";
  $attrs .= " data-height='{$image->height}'";
  $attrs .= " data-aspect='{$aspect}'";

  if( !is_null($image->sizes) ){
    // If the image has additional sizes
    ep( $image );
    
    $size_array = get_object_vars( $image->sizes );
    $srcset = " " . $size_array["small"] . " " . $size_array["small-width"] . "w,";  
    $srcset .= " " . $size_array["medium"] . " " . $size_array["medium-width"] . "w,";  
    $srcset .= " " . $size_array["large"] . " " . $size_array["large-width"] . "w,";  
    $srcset .= " " . $image->url . " " . round($image->width/2) . "w";  
  
    foreach( $image->sizes as $key => $val ) :
      // $srcset .= $
      $attrs .= ' data-' . $key . '="' . $val . '"';
    endforeach;
  }

  if( $use_span || $lazy ) {
    $styles .= " padding-bottom:{$aspect}%;";
  }

  if( $use_span ) {
    
    if( !$lazy ) {
      $styles .= " background-image: url(" . $image->url . ");";
    }

    $html .= "<span class='{$classes}' {$attrs} style='{$styles}'>";
    $html .= isset($image->alt) && count($image->alt) ? $image->alt : "";
    $html .= "</span>";

  }else{

    if( $srcset ) {
      $html .= "<img src='{$image->sizes->small}' srcset='{$srcset}' class='{$classes}' style='{$styles}'/>";
    }else{
      $html .= "<img src='{$image->url}' srcset='{$srcset}' class='{$classes}' {$attrs} style='{$styles}'/>";
    }

  }
  return $html;
}




/**
 * [feed_dir_rewrite description]
 * @param  [type] $wp_rewrite [description]
 * @return [type]             [description]
 * @see   https://codex.wordpress.org/Class_Reference/WP_Rewrite#Examples
 */
function feed_dir_rewrite( $wp_rewrite ) {
  $apartment_rules = array(
    'amenities/' => 'index.php?page_id=2',
    'apartments/([^/]+)/floorplans' => 'index.php?unit_type=$matches[1]&page=$matches[2]',
    // 'apartments/([^/]+)/building-amenities' => 'index.php?unit_type=$matches[1]&page=$matches[2]',
  );
  $wp_rewrite->rules = $apartment_rules + $wp_rewrite->rules;
  return $wp_rewrite->rules;
}

// Hook in.
add_filter( 'generate_rewrite_rules', 'feed_dir_rewrite' );
add_filter('init','flushRules');  

// Remember to flush_rules() when adding rules
function flushRules(){
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
}





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

    // This Script is the only way Contact Form 7 will work :(
    // wp_deregister_script( 'jquery-form' );
    // wp_register_script( 'jquery-form', $js_dir . '/jquery.form.js', array( 'require' ), false, true );
    // wp_enqueue_script( 'jquery-form' );

    /**
     * @see https://github.com/jrburke/requirejs/wiki/Patterns-for-separating-config-from-the-main-module
     */
    wp_register_script('require-config', $js_dir . '/require-config.js', null, false, true);
    wp_enqueue_script('require-config');

    wp_register_script('require', $js_dir . '/require.js', array('require-config', 'ajaxform'), false, true);
    wp_enqueue_script('require');

    wp_register_script('main', $js_dir . '/main.js', array('require', 'require-config'), false, true);
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
      'url' => get_stylesheet_directory_uri()
     );
    if( get_permalink() ) {
      $js_localized['permalink'] = get_permalink();
    }
    wp_localize_script( 'require-config', 'localized', $js_localized );


    /**
     * Add Livereload if we're developing locally
     * @see http://robandlauren.com/2014/02/05/live-reload-grunt-wordpress/
     */
    // if ( $is_local_dev && !is_admin() ) {
    //   wp_register_script('livereload', 'http://localhost:35729/livereload.js?snipver=1', null, false, true);
    //   wp_enqueue_script('livereload');
    // }

  endif;

}

// Load HTML5 Blank styles
function enqueue_styles()
{
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

// Reove Emojis from <head>
function disable_wp_emojicons() {

  // all actions related to emojis
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
  remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
  remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

  // filter to remove TinyMCE emojis
  add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
function disable_emojicons_tinymce( $plugins ) {
  if ( is_array( $plugins ) ) {
    return array_diff( $plugins, array( 'wpemoji' ) );
  } else {
    return array();
  }
}
add_action( 'init', 'disable_wp_emojicons' );


// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
  return is_array($var) ? array() : '';
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
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'enqueue_styles'); // Add Theme Stylesheet
add_action('init', 'register_menus'); // Add HTML5 Blank Menu 


// add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
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
// add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
// add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
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

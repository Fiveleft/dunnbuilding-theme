<?php 


function create_unit_type_custom_post()
{
  register_post_type('unit_type', // Register Custom Post Type
    array(
      'labels' => array(
        'name' => __('Unit Types', 'unit_type'), // Rename these to suit
        'singular_name' => __('Unit Type', 'unit_type'),
        'add_new' => __('Add New', 'unit_type'),
        'add_new_item' => __('Add New Unit Type', 'unit_type'),
        'edit' => __('Edit', 'unit_type'),
        'edit_item' => __('Edit Unit Type', 'unit_type'),
        'new_item' => __('New Unit Type', 'unit_type'),
        'view' => __('View Unit Type', 'unit_type'),
        'view_item' => __('View Unit Type', 'unit_type'),
        'search_items' => __('Search Unit Type', 'unit_type'),
        'not_found' => __('No Units found', 'unit_type'),
        'not_found_in_trash' => __('No Units found in Trash', 'unit_type')
      ),
      'public' => true,
      'hierarchical' => false, // Allows your posts to behave like Hierarchy Pages
      'has_archive' => false,
      'menu_position' => 4,
      'rewrite' => array(
        'slug' => 'apartments'
      ),
      'supports' => array(
        'title',
        'editor',
        'excerpt',
        'thumbnail',
        'page-attributes',
      ), // Go to Dashboard Custom HTML5 Blank post for supports
      'can_export' => true, // Allows export in Tools > Export
      'taxonomies' => array(
        'post_tag',
      ) // Add Category and Post Tags support
    ));

  add_unit_type_rewrite_rule();
}
add_action( 'init', 'create_unit_type_custom_post' );



/**
 * Adding 'apartments' to 'unit_type' paths so our naming convention is less confusing.
 * urls for unit types will now be 'apartments/studio'
 * @see http://wordpress.stackexchange.com/questions/41988/redeclare-change-slug-of-a-plugins-custom-post-type
 */
function add_unit_type_rewrite_rule() {

  // First, try to load up the rewrite rules. We do this just in case
  // the default permalink structure is being used.
  if( ($current_rules = get_option('rewrite_rules')) ) {

    // Next, iterate through each custom rule adding a new rule
    // that replaces 'movies' with 'films' and give it a higher
    // priority than the existing rule.
    foreach($current_rules as $key => $val) {
      if(strpos($key, 'movies') !== false) {
        add_rewrite_rule(
          str_ireplace('unit_type', 'apartments', $key), 
          $val, 
          'top'
        );   
      } // end if
    } // end foreach

  } // end if/else

  // ...and we flush the rules
  flush_rewrite_rules();

} // end add_custom_rewrite_rule
// add_action('init', 'add_custom_rewrite_rule');
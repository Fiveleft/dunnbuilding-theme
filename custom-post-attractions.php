<?php 


function create_attraction_custom_post()
{
    register_post_type('attraction', // Register Custom Post Type
      array(
        'labels' => array(
          'name' => __('Attractions', 'attraction'), // Rename these to suit
          'singular_name' => __('Attraction', 'attraction'),
          'add_new' => __('Add New', 'attraction'),
          'add_new_item' => __('Add New Attraction', 'attraction'),
          'edit' => __('Edit', 'attraction'),
          'edit_item' => __('Edit Attraction', 'attraction'),
          'new_item' => __('New Attraction', 'attraction'),
          'view' => __('View Attractions', 'attraction'),
          'view_item' => __('View Attraction', 'attraction'),
          'search_items' => __('Search Attractions', 'attraction'),
          'not_found' => __('No Attractions found', 'attraction'),
          'not_found_in_trash' => __('No Attractions found in Trash', 'attraction')
        ),
        'public' => true,
        'hierarchical' => false, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => false,
        'menu_position' => 4,
        'supports' => array(
          'title',
          'editor',
          'thumbnail',
          'page-attributes',
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true // Allows export in Tools > Export
      ));
}
add_action( 'init', 'create_attraction_custom_post' );
<?php 


function create_amenity_custom_post()
{
    register_post_type('amenity', // Register Custom Post Type
      array(
        'labels' => array(
          'name' => __('Amenities', 'amenity'), // Rename these to suit
          'singular_name' => __('Amenity', 'amenity'),
          'add_new' => __('Add New', 'amenity'),
          'add_new_item' => __('Add New Amenity', 'amenity'),
          'edit' => __('Edit', 'amenity'),
          'edit_item' => __('Edit Amenity', 'amenity'),
          'new_item' => __('New Amenity', 'amenity'),
          'view' => __('View Amenities', 'amenity'),
          'view_item' => __('View Amenity', 'amenity'),
          'search_items' => __('Search Amenities', 'amenity'),
          'not_found' => __('No Amenities found', 'amenity'),
          'not_found_in_trash' => __('No Amenities found in Trash', 'amenity')
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
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
          'post_tag',
        ) // Add Category and Post Tags support
      ));
}
add_action( 'init', 'create_amenity_custom_post' );
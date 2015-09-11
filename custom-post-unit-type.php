<?php 


function create_unit_type_custom_post()
{
    register_post_type('unit_type', // Register Custom Post Type
      array(
        'labels' => array(
          'name' => __('Unit Type', 'unit_type'), // Rename these to suit
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
}
add_action( 'init', 'create_unit_type_custom_post' );
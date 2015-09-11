<?php 




function create_custom_taxonomies() 
{
  register_taxonomy( 'unit_floor', 'unit', array( 
    'labels' => array(
      'name' => __('Unit Floor', 'unit_floor'),
      'singular_name' => __('Unit Floor', 'unit_floor'),
    ),
    'hierarchical' => false,
    'public' => false,
    'show_ui' => true,
    'show_admin_column' => true,
    'show_in_nav_menus' => true,
  ));
}
add_action( 'init', 'create_custom_taxonomies' );


?>
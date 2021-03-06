<?php 


// Create 1 Custom Post type for a Demo, called HTML5-Blank
function create_unit_custom_post()
{
    register_taxonomy_for_object_type('category', 'unit'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'unit');
    register_taxonomy_for_object_type('unit_floor', 'unit');
    //register_taxonomy_for_object_type('unit_type', 'unit');
    register_post_type('unit', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('Units', 'unit'), // Rename these to suit
            'singular_name' => __('Unit', 'unit'),
            'add_new' => __('Add New', 'unit'),
            'add_new_item' => __('Add New Unit', 'unit'),
            'edit' => __('Edit', 'unit'),
            'edit_item' => __('Edit Unit', 'unit'),
            'new_item' => __('New Unit', 'unit'),
            'view' => __('View Unit', 'unit'),
            'view_item' => __('View Unit', 'unit'),
            'search_items' => __('Search Unit', 'unit'),
            'not_found' => __('No Units found', 'unit'),
            'not_found_in_trash' => __('No Units found in Trash', 'unit')
        ),
        'public' => true,
        'hierarchical' => false, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => false,
        'menu_position' => 4,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
        ), // Go to Dashboard Custom HTML5 Blank post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
          'post_tag',
          'unit_floor',
        ) // Add Category and Post Tags support
    ));
}
add_action( 'init', 'create_unit_custom_post' );





/**
 * Unit Taxoonomy Meta Box overrides
 * @see http://wordpress.stackexchange.com/questions/28290/custom-taxonomy-as-dropdown-in-admin
 */
function create_unit_taxonomy_meta_boxes() {
  // Remove Unit Floor metabox
  remove_meta_box( 'tagsdiv-unit_floor', 'unit', 'low' );
  add_meta_box( 'tagsdiv-unit_floor', 'Unit Floor', 'unit_floor_meta_box', 'unit', 'low' );
}
add_action('add_meta_boxes', 'create_unit_taxonomy_meta_boxes');


/* Prints the taxonomy box content */
function unit_floor_meta_box($post) {

    $tax_name = 'unit_floor';
    $taxonomy = get_taxonomy( 'unit_floor' );
?>
<div class="tagsdiv" id="<?php echo $tax_name; ?>">
    <div class="jaxtag">
    <?php 
    // Use nonce for verification
    wp_nonce_field( plugin_basename( __FILE__ ), 'unit_floor_noncename' );
    $unit_floor_IDs = wp_get_object_terms( $post->ID, 'unit_floor', array('fields' => 'ids') );
    wp_dropdown_categories('taxonomy=unit_floor&hide_empty=0&orderby=slug&name=unit_floor&show_option_none=Select Unit Floor&selected='.$unit_floor_IDs[0]); ?>
    <!-- <p class="howto">Select Unit Floor</p> -->
    </div>
</div>
<?php
}




/* When the post is saved, saves our custom taxonomy */
function unit_floors_save_post_data( $post_id ) {
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || wp_is_post_revision( $post_id ) ) 
    return;

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times
  if ( !wp_verify_nonce( $_POST['unit_floor_noncename'], plugin_basename( __FILE__ ) ) )
    return;

  // Check permissions
  if ( 'unit' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
      return;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
      return;
  }

  // OK, we're authenticated: we need to find and save the data

  $type_ID = $_POST['unit_floor'];

  $type = ( $type_ID > 0 ) ? get_term( $type_ID, 'unit_floor' )->slug : NULL;

  wp_set_object_terms(  $post_id , $type, 'unit_floor' );
}

/* Do something with the data entered */
add_action( 'save_post', 'unit_floors_save_post_data' );





/**
 * Unit Number Metabox
 * Generated by the WordPress Meta Box generator
 * at http://jeremyhixon.com/tool/wordpress-meta-box-generator/
 */
function unit_number_get_meta( $value ) {
  global $post;

  $field = get_post_meta( $post->ID, $value, true );
  if ( ! empty( $field ) ) {
    return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
  } else {
    return false;
  }
}

function unit_number_add_meta_box() {

  add_meta_box(
    'unit_number-unit-number',
    __( 'Unit Number', 'unit_number' ),
    'unit_number_html',
    'unit',
    'low',
    'default'
  );
}
add_action( 'add_meta_boxes', 'unit_number_add_meta_box' );

function unit_number_html( $post) {

  wp_nonce_field( '_unit_number_nonce', 'unit_number_nonce' ); 
  $disabled = !current_user_can( 'manage_options' );
  ?>
  <p>
    <label for="unit_number_unit_"><?php _e( 'Unit #', 'unit_number' ); ?></label>
    <input <?php if($disabled) echo 'disabled' ?> type="text" name="unit_number_unit_" id="unit_number_unit_" value="<?php echo unit_number_get_meta( 'unit_number_unit_' ); ?>">
  </p>
  <?php if($disabled) : ?>
    <p class='howto'>Only Administrator can edit unit number</p>
  <?php endif; ?>

  <?php
}

function unit_number_save( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
  if ( ! isset( $_POST['unit_number_nonce'] ) || ! wp_verify_nonce( $_POST['unit_number_nonce'], '_unit_number_nonce' ) ) return;
  if ( ! current_user_can( 'edit_post', $post_id ) ) return;

  if ( isset( $_POST['unit_number_unit_'] ) )
    update_post_meta( $post_id, 'unit_number_unit_', esc_attr( $_POST['unit_number_unit_'] ) );
}
add_action( 'save_post', 'unit_number_save' );

/*
  Usage: unit_number_get_meta( 'unit_number_unit_' )
*/









/**
 * Generated by the WordPress Meta Box generator
 * at http://jeremyhixon.com/tool/wordpress-meta-box-generator/
 */

function availability_get_meta( $value ) {
  global $post;

  $field = get_post_meta( $post->ID, $value, true );
  if ( ! empty( $field ) ) {
    return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
  } else {
    return false;
  }
}

function availability_add_meta_box() {
  add_meta_box(
    'availability-availability',
    __( 'Availability', 'availability' ),
    'availability_html',
    'post',
    'normal',
    'high'
  );
  add_meta_box(
    'availability-availability',
    __( 'Availability', 'availability' ),
    'availability_html',
    'unit',
    'normal',
    'high'
  );
}
add_action( 'add_meta_boxes', 'availability_add_meta_box' );

function availability_html( $post) {
  wp_nonce_field( '_availability_nonce', 'availability_nonce' ); ?>
  <p>
    <input type="checkbox" name="availability_available" id="availability_available" value="true" <?php echo ( availability_get_meta( 'availability_available' ) === 'true' ) ? 'checked' : ''; ?>>
    <label for="availability_available"><?php _e( 'Available', 'availability' ); ?></label> 
  </p><?php
}

function availability_save( $post_id ) {
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
  if ( ! isset( $_POST['availability_nonce'] ) || ! wp_verify_nonce( $_POST['availability_nonce'], '_availability_nonce' ) ) return;
  if ( ! current_user_can( 'edit_post', $post_id ) ) return;

  if ( isset( $_POST['availability_available'] ) )
    update_post_meta( $post_id, 'availability_available', esc_attr( $_POST['availability_available'] ) );
  else
    update_post_meta( $post_id, 'availability_available', null );
}
add_action( 'save_post', 'availability_save' );
/*
  Usage: availability_get_meta( 'availability_available' )
*/







/** 
 * Adding a table sort column for editing availability
 */
function unit_table_columns( $columns ) {
  ep( $columns );
  $new_columns = array( 
      "cb" => $columns["cb"],
      "available" => "Avail.",
      "title" => $columns["title"],
      "unit_type" => "Type",
      "unit_number" => "Number",
      "floorplan" => "Floor Plan",
      "tags" => $columns["tags"],
    );
  return $new_columns;
}
add_filter('manage_edit-unit_columns', 'unit_table_columns');
add_filter('manage_edit-unit_sortable_columns', 'unit_table_columns');
//the above line is the only difference from the previous example


function unit_table_column( $colname, $unit_pID ) {
  switch($colname) {
    case ( 'available' ) :
    $available = get_post_meta( $unit_pID, 'availability_available', true );
    ?>
      <span class='availability <?php if($available) echo "available"; ?>' ></span>
    <?php
    break;
    case ( 'floorplan' ) :
    $fp = get_post_meta( $unit_pID, 'floor_plan_type' );
    if( count($fp) ) {
      $fp_post = get_post( $fp[0][0] );
      ?>
      <span class='unit-type'><?php echo $fp_post->post_title; ?></span>
      <?php 
    }else{
      ?>
      <span class='unit-type'>Not Defined</span>
      <?php 
    }
    break;
    case ( 'unit_number' ) :
    $unit_number = get_post_meta( $unit_pID, 'unit_number_unit_', true );
    ?>
    <span class='unit-type'><?php echo $unit_number; ?></span>
    <?php
    break;
    case ( 'unit_type' ) :
    $unit_type_id = get_post_meta( $unit_pID, 'apartment_type', true );
    $unit_type_name = get_the_title( $unit_type_id );
    ?>
    <span class='unit-type'><?php echo $unit_type_name; ?></span>
    <?php
    break;
  }
}
add_action('manage_unit_posts_custom_column', 'unit_table_column', 10, 2);
//the actual column data is output

function my_admin_column_width() {
    echo '<style type="text/css">
      .wp-admin.post-type-unit .column-title { width:20%; }
      .wp-admin.post-type-unit .column-unit_type { width:15%; }
      .wp-admin.post-type-unit .column-unit_number { width:10%; }
      .wp-admin.post-type-unit .column-floorplan { width:10%; }
      .wp-admin.post-type-unit .column-tags { width:20%; }
      .wp-admin.post-type-unit .column-available { text-align: center; width:5%; }
      .wp-admin.post-type-unit .column-available span.availability {  
        display: inline-block;
        vertical-align: middle;
        width: 1em; height: 1em; margin: 0 auto;
        border: 2px solid #EEE;
        background: #F2F2F2;
        color: #999;
        text-align: center;
        line-height: 1em;
      }      
      .wp-admin.post-type-unit .column-available .availability.available {  
        border: 2px solid #00FF90;
        background: #00FF90;
        color: #FFF;
      }
    </style>';
}
add_action('admin_head', 'my_admin_column_width');



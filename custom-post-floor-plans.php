<?php 


function create_floorplan_custom_post()
{
  register_post_type('floorplan', // Register Custom Post Type
    array(
      'labels' => array(
        'name' => __('Floor Plan', 'floorplan'), // Rename these to suit
        'singular_name' => __('Floor Plan', 'floorplan'),
        'add_new' => __('Add New', 'floorplan'),
        'add_new_item' => __('Add New Floor Plan', 'floorplan'),
        'edit' => __('Edit', 'floorplan'),
        'edit_item' => __('Edit Floor Plan', 'floorplan'),
        'new_item' => __('New Floor Plan', 'floorplan'),
        'view' => __('View Floor Plan', 'floorplan'),
        'view_item' => __('View Floor Plan', 'floorplan'),
        'search_items' => __('Search Floor Plan', 'floorplan'),
        'not_found' => __('No Floor Plans found', 'floorplan'),
        'not_found_in_trash' => __('No Floor Plans found in Trash', 'floorplan')
      ),
      'public' => true,
      'hierarchical' => false, // Allows your posts to behave like Hierarchy Pages
      'has_archive' => false,
      'menu_position' => 4,
      'supports' => array(
        'title',
        'editor',
      ), // Go to Dashboard Custom HTML5 Blank post for supports
      'can_export' => true, // Allows export in Tools > Export
    ));

  // add_floorplan_rewrite_rule();
}
add_action( 'init', 'create_floorplan_custom_post' );





/**
 * If Adding a Custom Meta Box
 * @see http://www.deluxeblogtips.com/2010/04/how-to-create-meta-box-wordpress-post.html
 */

$prefix = 'dbt_';
$meta_box = array(
    'id' => 'my-meta-box',
    'title' => 'Custom meta box',
    'page' => 'floorplan',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Text box',
            'desc' => 'Enter something here',
            'id' => $prefix . 'text',
            'type' => 'text',
            'std' => 'Default value 1'
        ),
        array(
            'name' => 'Textarea',
            'desc' => 'Enter big text here',
            'id' => $prefix . 'textarea',
            'type' => 'textarea',
            'std' => 'Default value 2'
        ),
        array(
            'name' => 'Select box',
            'id' => $prefix . 'select',
            'type' => 'select',
            'options' => array('Option 1', 'Option 2', 'Option 3')
        ),
        array(
            'name' => 'Radio',
            'id' => $prefix . 'radio',
            'type' => 'radio',
            'options' => array(
                array('name' => 'Name 1', 'value' => 'Value 1'),
                array('name' => 'Name 2', 'value' => 'Value 2')
            )
        ),
        array(
            'name' => 'Checkbox',
            'id' => $prefix . 'checkbox',
            'type' => 'checkbox'
        )
    )
);
// Add meta box
function mytheme_add_box() {
    global $meta_box;
    add_meta_box($meta_box['id'], $meta_box['title'], 'mytheme_show_box', $meta_box['page'], $meta_box['context'], $meta_box['priority']);
}


// Callback function to show fields in meta box
function mytheme_show_box() {
    global $meta_box, $post;
    // Use nonce for verification
    echo '<input type="hidden" name="mytheme_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
    echo '<table class="form-table">';
    foreach ($meta_box['fields'] as $field) {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
        switch ($field['type']) {
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />', '<br />', $field['desc'];
                break;
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>', '<br />', $field['desc'];
                break;
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option ', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
                }
                echo '</select>';
                break;
            case 'radio':
                foreach ($field['options'] as $option) {
                    echo '<input type="radio" name="', $field['id'], '" value="', $option['value'], '"', $meta == $option['value'] ? ' checked="checked"' : '', ' />', $option['name'];
                }
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                break;
        }
        echo     '</td><td>',
            '</td></tr>';
    }
    echo '</table>';
}


add_action('admin_menu', 'mytheme_add_box');


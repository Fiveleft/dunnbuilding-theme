<?php
 //custom-gallery-shortcodes.php

$shortcode_regex = get_shortcode_regex();

remove_shortcode('gallery', 'gallery_shortcode');
add_shortcode('page_gallery', 'page_gallery_shortcode');
add_shortcode('apartment_gallery', 'apartment_gallery_shortcode');



/**
 * [apartment_gallery_shortcode description]
 * @param  [type] $attr [description]
 * @return [type]       [description]
 */
function apartment_gallery_shortcode($attr) 
{
  $gids = explode( ",", $attr['ids'] );
  $gallery_count = count( $gids );
  $slides_html = "<div class='gallery-slides-wrapper'>\n";
  $slides_html .= "  <div class='gallery-slides' data-count='$gallery_count' >\n";
  $grid_html = "<ul class='gallery-grid'>\n";

  if( $gallery_count ) :

    $gallery_index = 1;

    foreach( $gids as $imgID ) :

      $img_data = wp_get_attachment_metadata( $imgID );
      $img_caption = get_post_field( "post_excerpt", $imgID );
      $parsed = parse_url( wp_get_attachment_url( $imgID ) );
      $img_dir = dirname( $parsed [ 'path' ] ) . '/';


      if( $img_data ) :

        $img_attrs = "data-src='" . $img_dir . $img_data['file'] . "' ";
        $img_attrs .= "data-width'" . $img_data['width'] . "' ";
        $img_attrs .= "data-height'" . $img_data['height'] . "' ";

        foreach( $img_data['sizes'] as $size_name => $size_data ) :
          $img_attrs .= "data-$size_name-src='" . $img_dir . $size_data['file'] . "' ";
          $img_attrs .= "data-$size_name-width='" . $img_dir . $size_data['width'] . "' ";
        endforeach;

        $aspect_perc = 100 * ($img_data["height"] / $img_data["width"]) . "%";

        $slides_html .= "<dl class='gallery-item' data-id='" . $imgID . "' data-index='" . $gallery_index . "'>\n";
        $slides_html .= "  <dt class='gallery-image-wrapper' style='padding-bottom:$aspect_perc;' >\n";

        if( $gallery_index == 1 ) :
          $slides_html .= "    <img class='gallery-image image has-breakpoints' src='" . $img_dir . $img_data['file'] . "' $img_attrs/>\n";
        else :
          $slides_html .= "    <span class='gallery-image image has-breakpoints lazy' $img_attrs></span>\n";
        endif;
        $slides_html .= "  </dt>\n";
        $slides_html .= "  <dd class='gallery-image-caption'>\n";
        $slides_html .= "    <p class='caption'>$img_caption</p>\n";
        $slides_html .= "  </dd>\n";
        $slides_html .= "</dl>\n";

        $grid_html .= "<li class='gallery-grid-item'>\n";
        $grid_html .= "  <a class='gallery-grid-link' href='#img-$gallery_index' data-index='$gallery_index'>\n";
        $grid_html .= "    <img src='" . $img_dir . $img_data['sizes']['thumbnail']['file'] . "' alt='$img_caption' />\n";
        $grid_html .= "  </a>";
        $grid_html .= "</li>";

        $gallery_index ++;
      endif;
     // error_log( " reading gallery image id: " . $imgID );
    endforeach; 
  endif;

  $slides_html .= "  </div><!-- /.gallery-slides -->\n";
  $slides_html .= "</div><!-- /.gallery-slides-wrapper -->\n";
  $grid_html .= "</ul><!-- /.gallery-grid -->\n";

  $output = "<div class='gallery apartment-gallery'>\n";
  $output .= $slides_html . "\n";
  $output .= $grid_html . "\n";
  $output .= "</div>";
  return $output;
}


/**
 * [page_gallery_shortcode description]
 * @param  [type] $attr [description]
 * @return [type]       [description]
 */
function page_gallery_shortcode( $attr ) 
{
  $gids = explode( ",", $attr['ids'] );
  $gallery_count = count( $gids );

  $output = "<div class='gallery page-gallery'>\n";
  $output .= "  <div class='gallery-slides-wrapper'>\n";
  $output .= "    <div class='gallery-slides'  data-count='$gallery_count'>\n";

  if( $gallery_count ) :

    $gallery_index = 1;

    foreach( $gids as $imgID ) :

      $img_data = wp_get_attachment_metadata( $imgID );
      $img_caption = get_post_field( "post_excerpt", $imgID );
      $parsed = parse_url( wp_get_attachment_url( $imgID ) );
      $img_dir = dirname( $parsed [ 'path' ] ) . '/';

      if( $img_data ) :

        $img_attrs = "data-src='" . $img_dir . $img_data['file'] . "' ";
        foreach( $img_data['sizes'] as $size_name => $size_data ) :
          $img_attrs .= "data-$size_name-src='" . $img_dir . $size_data['file'] . "' ";
          $img_attrs .= "data-$size_name-width='" . $size_data['width'] . "' ";
        endforeach;


        $aspect_perc = 100 * ($img_data["height"] / $img_data["width"]) . "%";

        $output .= "<dl class='gallery-item' data-id='" . $imgID . "' data-index='" . $gallery_index . "'>\n";
        $output .= "  <dt class='gallery-image-wrapper' style='padding-bottom:$aspect_perc;' >\n";

        if( $gallery_index == 1 ) :
          $output .= "    <img class='gallery-image image has-breakpoints' src='" . $img_dir . $img_data['file'] . "' $img_attrs/>\n";
        else :
          $output .= "    <span class='gallery-image image has-breakpoints lazy' $img_attrs></span>\n";
        endif;
        $output .= "  </dt>\n";
        $output .= "  <dd class='gallery-image-caption'>\n";
        $output .= "    <p class='caption'>$img_caption</p>\n";
        $output .= "  </dd>\n";
        $output .= "</dl>\n";

        $gallery_index ++;
      endif;
     // error_log( " reading gallery image id: " . $imgID );
    endforeach; 
  endif;

  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>";
  return $output;
}

// function remove_gallery_shortcode($content) 
// {
//   $pattern = get_shortcode_regex(); 
//   preg_replace("/$pattern/s", "", $content);
// }


 ?>
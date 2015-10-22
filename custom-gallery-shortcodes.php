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
  $grid_html = "<ul class='gallery-grid' data-count='$gallery_count'>\n";

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

        $srcset = "";

        foreach( $img_data['sizes'] as $size_name => $size_data ) :
          $img_attrs .= "data-$size_name-src='" . $img_dir . $size_data['file'] . "' ";
          $img_attrs .= "data-$size_name-width='" . $img_dir . $size_data['width'] . "' ";

          if( $size_name !== 'thumbnail' ) :
            $srcset .= $img_dir . $size_data['file'] . " " . $size_data['width'] . "w, ";
          endif;
        endforeach;

        $aspect_perc = 100 * ($img_data["height"] / $img_data["width"]) . "%";

        $slides_html .= "<dl class='gallery-item' data-id='" . $imgID . "' data-index='" . $gallery_index . "'>\n";
        $slides_html .= "  <dt class='gallery-image-wrapper' style='padding-bottom:$aspect_perc;' >\n";

        // if( $gallery_index == 1 ) :
        //   $slides_html .= "    <img class='gallery-image image has-breakpoints' src='" . $img_dir . $img_data['file'] . "' $img_attrs/>\n";
        // else :
        //   $slides_html .= "    <span class='gallery-image image has-breakpoints lazy' $img_attrs></span>\n";
        // endif;
        $slides_html .= "    <img class='gallery-image image breakpoints' src='" . $img_dir . $img_data['sizes']['small']['file'] . "' srcset='$srcset'/>\n";

        $slides_html .= "  </dt>\n";
        $slides_html .= "  <dd class='gallery-image-caption'>\n";
        $slides_html .= "    <p class='caption'>$img_caption</p>\n";
        $slides_html .= "  </dd>\n";
        $slides_html .= "</dl>\n";

        $grid_html .= "<li class='gallery-grid-item'>\n";
        $grid_html .= "  <a class='gallery-grid-link' href='#img-$gallery_index' data-index='$gallery_index'>\n";
        $grid_html .= "    <span class='gallery-grid-img' style='padding-bottom:$aspect_perc; background-image: url(" . $img_dir . $img_data['sizes']['small']['file'] . ");'>$img_caption</span>\n";
        $grid_html .= "  </a>";
        $grid_html .= "</li>";

        $gallery_index ++;
      endif;
     // error_log( " reading gallery image id: " . $imgID );
    endforeach; 
  endif;

  $btn_html = file_get_contents( dirname( __FILE__ ) . "/img/arrow-icon.svg" );

  $slides_html .= "  </div><!-- /.gallery-slides -->\n";
  $slides_html .= "  <div class='ui-wrapper'>\n";
  $slides_html .= "    <button class='advance prev' title='Previous'>" . $btn_html . "</button>\n";
  $slides_html .= "    <button class='advance next' title='Next'>" . $btn_html . "</button>\n";
  $slides_html .= "  </div>\n";
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
  $output .= "    <div class='gallery-slides' data-count='$gallery_count'>\n";

  $nav_html = "<nav class='gallery-disc-nav'>";

  if( $gallery_count ) :

    $gallery_index = 1;

    foreach( $gids as $imgID ) :

      $img_data = wp_get_attachment_metadata( $imgID );
      $img_caption = get_post_field( "post_excerpt", $imgID );
      $parsed = parse_url( wp_get_attachment_url( $imgID ) );
      $img_dir = dirname( $parsed [ 'path' ] ) . '/';
      $img_alt = trim(strip_tags( get_post_meta( $imgID, '_wp_attachment_image_alt', true ) ));

      if( $img_data ) :

        $srcset = "";
        foreach( $img_data['sizes'] as $size_name => $size_data ) :
          if( $size_name !== 'thumbnail' ) :
            $srcset .= $img_dir . $size_data['file'] . " " . $size_data['width'] . "w, ";
          endif;
        endforeach;


        $aspect_perc = 100 * ($img_data["height"] / $img_data["width"]) . "%";

        $output .= "<dl class='gallery-item' data-id='" . $imgID . "' data-index='" . $gallery_index . "'>\n";
        $output .= "  <dt class='gallery-image-wrapper' style='padding-bottom:$aspect_perc;' >\n";
        $output .= "    <img class='gallery-image image breakpoints' src='" . $img_dir . $img_data['sizes']['small']['file'] . "' srcset='$srcset' alt='$img_alt' />\n";
        $output .= "  </dt>\n";
        $output .= "  <dd class='gallery-image-caption'>\n";
        $output .= "    <p class='caption'>$img_caption</p>\n";
        $output .= "  </dd>\n";
        $output .= "</dl>\n";

        $nav_html .= "<a href='#index-$gallery_index' data-index='$gallery_index'>View $img_alt</a>";

        $gallery_index ++;
      endif;
     // error_log( " reading gallery image id: " . $imgID );
    endforeach; 
  endif;

  $btn_html = file_get_contents( dirname( __FILE__ ) . "/img/arrow-icon.svg" );

  $nav_html .= "</nav><!-- .gallery-disc-nav -->";

  $output .= "    </div>\n";
  $output .= "    <div class='ui-wrapper'>\n";
  $output .= "      <button class='advance prev' title='Previous'>" . $btn_html . "</button>\n";
  $output .= "      <button class='advance next' title='Next'>" . $btn_html . "</button>\n";

  $output .= $nav_html;

  $output .= "    </div>\n";
  $output .= "  </div>\n";
  $output .= "</div>";
  return $output;
}

 ?>
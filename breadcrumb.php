<?php 

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Breadcrumbs
function breadcrumb($options = []) {

   // Define default values
   $defaults_options = array(
        'container'        => 'ul',
        'container_id'     => 'breadcrumb',
        'container_class'  => 'breadcrumb',
        'item'             => 'li',
        'item_class'       => '',
        'anchor_class'     => '',
        'separator'        => '&#47;',
        'active_class'     => 'active',
        'echo'             => false,
   );

   // Merge the provided arguments with the defaults
   $options = wp_parse_args($options, $defaults_options);

   // Extract the parameters
   extract($options);

   $text_domain =  wp_get_theme()->get( 'TextDomain' ); // Get text domain for translations
   $home_title  = 'Home'; //text for the 'Home' link

   // Get the query & post information
   global $post;

   // Open list
   $breadcrumb = '<' . $container . ' id="' . $container_id . '" class="' . $container_class . '">';

   // Front page
   if ( is_front_page() ) {
      $breadcrumb .= '<' . $item . ' class="' . ($item_class ? $item_class . ' ' : '') . $active_class . '"><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . '>' . $home_title . '</a></' . $item . '>';
   } else {
      $breadcrumb .= '<' . $item . ($item_class ? ' class="' . $item_class . '" '  : '') . '><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . ' href="' . home_url() . '">' . $home_title . '</a></' . $item . '>';
      
      if ( $separator ) $breadcrumb .= '<' . $item . ' class="separator">' . $separator . '</' . $item . '>';
   }
   
   // Category, Tag, Author, and Date Archives
   if ( is_archive() && !is_tax() && !is_post_type_archive() ) {
      $breadcrumb .= '<' . $item . ' class="' . ($item_class ? $item_class . ' ' : '') . $active_class . '"><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . '>';

      // Title of archive
      if ( is_category() ) {
         $breadcrumb .= single_cat_title( '', false );
      } else if ( is_tag() ) {
         $breadcrumb .= single_tag_title( '', false );
      } else if ( is_author() ) {
         $breadcrumb .= get_the_author();
      } else if ( is_date() ) {
         if ( is_day() ) {
            $breadcrumb .= get_the_time( 'F j, Y' );
         } else if ( is_month() ) {
            $breadcrumb .= get_the_time( 'F, Y' );
         } else if ( is_year() ) {
            $breadcrumb .= get_the_time( 'Y' );
         }
      }

      $breadcrumb .= '</a></' . $item . '>';
   }
   
   // Posts
   if ( is_singular( 'post' ) ) {
      // Get post category info
      $category = get_the_category();

      if ( !empty($category) ) {
         
         $first_category = current( array_values($category) ); // Get first category post is in
         $last_category = end( array_values($category) ); // Get last category post is in

         $breadcrumb .= '<' . $item . ($item_class ? ' class="' . $item_class . '" '  : '') . '><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . ' href="' . get_category_link( $last_category->term_id ) . '">' . $last_category->name . '</a></' . $item . '>';

         if ( $separator ) $breadcrumb .= '<' . $item . ' class="separator">' . $separator . '</' . $item . '>';

      }

      // Post title
      $breadcrumb .= '<' . $item . ' class="' . ($item_class ? $item_class . ' ' : '') . $active_class . '"><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . '>' . get_the_title() . '</a></' . $item . '>';
   }
   
   // Pages
   if ( is_page() && !is_front_page() ) {
      //  Parent pages
      if ( $post->post_parent ) {
         // If this is a child page, retrieve its parent pages
         $ancestors = get_post_ancestors($post->ID);

         // Get parents in the right order
         $ancestors = array_reverse($ancestors);

         // Parent page loop
         foreach ( $ancestors as $ancestor ) {
            $breadcrumb .= '<' . $item . ($item_class ? ' class="' . $item_class . '" '  : '') . '><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . ' href="' . get_permalink( $ancestor ) . '">' . get_the_title( $ancestor ) . '</a></' . $item . '>';
            
            if ( $separator ) $breadcrumb .= '<' . $item . ' class="separator">' . $separator . '</' . $item . '>';
         }
      }
      
      // Current page
      $breadcrumb .=  '<' . $item . ' class="' . ($item_class ? $item_class . ' ' : '') . $active_class . '"><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . '>' . get_the_title() . '</a></' . $item . '>';
   }

   // Attachments
   if ( is_attachment() ) {
      // Attachment parent
      $post = get_post( get_the_ID() );

      if ( $post->post_parent ) {
          $breadcrumb .= '<' . $item . ($item_class ? ' class="' . $item_class . '" '  : '') . '><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . ' href="' . get_permalink( $post->post_parent ) . '">' . get_the_title( $post->post_parent ) . '</a></' . $item . '>';
          
          if ( $separator ) $breadcrumb .= '<' . $item . ' class="separator">' . $separator . '</' . $item . '>';
      }

      // Attachment title
      $breadcrumb .= '<' . $item . ' class="' . ($item_class ? $item_class . ' ' : '') . $active_class . '"><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . '>' . get_the_title() . '</a></' . $item . '>';
   }
   
   // Search
   if ( is_search() ) {
      $breadcrumb .= '<' . $item . ' class="' . ($item_class ? $item_class . ' ' : '') . $active_class . '"><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . '>' . __( 'Search &#8211; ' . get_search_query(), $text_domain ) . '</a></' . $item . '>';
   }

   // 404 Page
   if ( is_404() ) {
      $breadcrumb .= '<' . $item . ' class="' . ($item_class ? $item_class . ' ' : '') . $active_class . '"><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . '>' . __( '404', $text_domain ) . '</a></' . $item . '>';
   }

   // Custom Post Type Archive
   if ( is_post_type_archive() ) {
      $breadcrumb .= '<' . $item . ' class="' . ($item_class ? $item_class . ' ' : '') . $active_class . '"><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . '>' . post_type_archive_title( '', false ) . '</a></' . $item . '>';
   }

   // Custom Taxonomies
   if ( is_tax() ) {
      // Term title
      $breadcrumb .= '<' . $item . ' class="' . ($item_class ? $item_class . ' ' : '') . $active_class . '"><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . '>' . single_term_title( '', false ) . '</a></' . $item . '>';
   }

   // Custom Post Types
   if ( is_single() && get_post_type() != 'post' && get_post_type() != 'attachment' ) {
      
      // Get post taxonomies info
      // $taxonomies = get_object_taxonomies( get_post_type() );
      $taxonomies = array_reverse( get_object_taxonomies( get_post_type() ) ); // array contains the elements in reverse order

      if ( !empty($taxonomies) ) {

         foreach ($taxonomies as $taxonomy) {

            $terms = get_the_terms( get_the_ID(), $taxonomy );
           
            if ( !empty($terms) && !is_wp_error($terms) && get_taxonomy($taxonomy)->hierarchical ) {
               
               $first_terms = current( array_values($terms) ); // Get first terms (categories or tags) post is in
               $last_terms = end( array_values($terms) ); // Get last terms (categories or tags) post is in

               $breadcrumb .= '<' . $item . ($item_class ? ' class="' . $item_class . '" '  : '') . '><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . ' href="' . get_term_link( $last_terms->name, $last_terms->taxonomy ) . '">' . $last_terms->name . '</a></' . $item . '>';
      
               if ( $separator ) $breadcrumb .= '<' . $item . ' class="separator">' . $separator . '</' . $item . '>';

               break;
            }
          
         }

      }

      // Cpt title
      $breadcrumb .= '<' . $item . ' class="' . ($item_class ? $item_class . ' ' : '') . $active_class . '"><a' . ($anchor_class ? ' class="' . $anchor_class . '" '  : '') . '>' . get_the_title() . '</a></' . $item . '>';
      
   }

   // Close list
   $breadcrumb .= '</' . $container . '>';

   // Output
   if ( $echo ) {
      echo $breadcrumb;
   } else {
      return $breadcrumb;
   }

}
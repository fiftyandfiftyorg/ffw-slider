<?php
/**
 * Functions
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function ffw_slider_disable_link()
{
    global $ffw_slider_settings;

   $ffw_slider_disable = isset( $ffw_slider_settings['slider_disable_link_to_single'] ) ? true : false;

   return $ffw_slider_disable;
    
}

/**
 * Allow the ability to set posts_per_page on the archive template from the admin area. BOOM!
 * 
 * @param  [type] $query [description]
 * @return [type]        [description]
 */
function ffw_slider_adjust_posts_per_page( $query ) {
    
    global $ffw_slider_settings;

    if ( is_admin() || ! $query->is_main_query() )
        return;

    if ( is_post_type_archive( 'ffw_slider' ) ||  $query->is_tax('slider_category')) {
        // Display 50 posts for a custom post type called 'movie'
        
        $ffw_slider_posts_per_page = isset( $ffw_slider_settings['slider_posts_per_page'] ) ? $ffw_slider_settings['slider_posts_per_page'] : 100;
        
        $query->set( 'posts_per_page', $ffw_slider_posts_per_page );
        
        return;
    }
}
add_action( 'pre_get_posts', 'ffw_slider_adjust_posts_per_page', 1 );





/**
 * Check if slider member is link to page is disabled
 * 
 * @return [type] [description]
 */
function ffw_slider_is_disabled( $post_id=null )
{
    global $post;

    $post_id = isset( $post_id ) ? $post_id : $post->ID;

    $disable_link_to = get_post_meta( $post_id, 'ffw_slider_disable_link_to', true);

    $link_status = isset( $disable_link_to ) ? $disable_link_to : 0;

    return $link_status;


}



/**
 * Returns URL from the settings page
 * 
 * @return [string] [the url of the media file]
 */
function get_slider_archive_image()
{
    global $ffw_slider_settings;

    $archive_image = isset( $ffw_slider_settings['archive_image_url'] ) ? $ffw_slider_settings['archive_image_url'] : '';
    
    return $archive_image;
}
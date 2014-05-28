<?php
/**
 * Scripts
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

function ffw_slider_load_admin_scripts( $hook ) 
{
    global $post,
    $ffw_slider_settings,
    $ffw_slider_settings_page,
    $wp_version;

    $js_dir  = FFW_SLIDER_PLUGIN_URL . 'assets/js/';
    $css_dir = FFW_SLIDER_PLUGIN_URL . 'assets/css/';

    wp_register_script( 'slider-admin-scripts', $js_dir . 'admin-scripts.js', array('jquery'), '1.0', true );

    wp_enqueue_script( 'slider-admin-scripts' );
    wp_localize_script( 'slider-admin-scripts', 'ffw_slider_vars', array(
        'new_media_ui'            => apply_filters( 'ffw_slider_use_35_media_ui', 1 ),
        ) 
    );

    if ( $hook == $ffw_slider_settings_page ) {
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_script( 'wp-color-picker' );
        wp_enqueue_style( 'colorbox', $css_dir . 'colorbox.css', array(), '1.3.20' );
        wp_enqueue_script( 'colorbox', $js_dir . 'jquery.colorbox-min.js', array( 'jquery' ), '1.3.20' );
        if( function_exists( 'wp_enqueue_media' ) && version_compare( $wp_version, '3.5', '>=' ) ) {
            //call for new media manager
            wp_enqueue_media();
        }
    }
    

}
add_action( 'admin_enqueue_scripts', 'ffw_slider_load_admin_scripts', 100 );
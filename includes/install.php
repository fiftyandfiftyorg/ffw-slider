<?php
/**
 * Install Function
 *
 * @package     NAMATI_NETWORK
 * @subpackage  Functions/Install
 * @copyright   Copyright (c) 2014, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Install
 *
 * Runs on plugin install by setting up the post types, custom taxonomies,
 * flushing rewrite rules to initiate the new 'downloads' slug and also
 * creates the plugin and populates the settings fields for those plugin
 * pages. After successful install, the user is redirected to the NAMATI_NETWORK Welcome
 * screen.
 *
 * @since 1.0
 * @global $wpdb
 * @global $ffw_slider_settings
 * @global $wp_version
 * @return void
 */
function ffw_slider_install() {
    global $wpdb, $ffw_slider_settings, $wp_version;

    // Setup the Downloads Custom Post Type
    setup_ffw_slider_post_types();

    // Setup the Download Taxonomies
    ffw_slider_setup_taxonomies();

    // Clear the permalinks
    flush_rewrite_rules();

    // Add Upgraded From Option
    $current_version = get_option( 'ffw_slider_version' );
    if ( $current_version ) {
        update_option( 'ffw_slider_version_upgraded_from', $current_version );
    }

    // Bail if activating from network, or bulk
    if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
        return;
    }

    // Add the transient to redirect
    set_transient( '_ffw_slider_activation_redirect', true, 30 );
}
register_activation_hook( FFW_SLIDER_PLUGIN_FILE, 'ffw_slider_install' );

/**
 * Post-installation
 *
 * Runs just after plugin installation and exposes the
 * ffw_slider_after_install hook.
 *
 * @since 1.7
 * @return void
 */
function ffw_slider_after_install() {

    if ( ! is_admin() ) {
        return;
    }

    $activation_pages = get_transient( '_ffw_slider_activation_pages' );

    // Exit if not in admin or the transient doesn't exist
    if ( false === $activation_pages ) {
        return;
    }

    // Delete the transient
    delete_transient( '_ffw_slider_activation_pages' );

    do_action( 'ffw_slider_after_install', $activation_pages );
}
add_action( 'admin_init', 'ffw_slider_after_install' );
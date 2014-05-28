<?php 
/**
 * Plugin Name: Fifty Framework Slider
 * Plugin URI: http://fiftyandfifty.org
 * Description: Build sliders for your site
 * Version: 1.0
 * Author: Fifty and Fifty
 * Author URI: http://labs.fiftyandfifty.org
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'FFW_SLIDER' ) ) :


/**
 * Main FFW_SLIDER Class
 *
 * @since 1.0 */
final class FFW_SLIDER {

  /**
   * @var FFW_SLIDER Instance
   * @since 1.0
   */
  private static $instance;


  /**
   * FFW_SLIDER Instance / Constructor
   *
   * Insures only one instance of FFW_SLIDER exists in memory at any one
   * time & prevents needing to define globals all over the place. 
   * Inspired by and credit to FFW_SLIDER.
   *
   * @since 1.0
   * @static
   * @uses FFW_SLIDER::setup_globals() Setup the globals needed
   * @uses FFW_SLIDER::includes() Include the required files
   * @uses FFW_SLIDER::setup_actions() Setup the hooks and actions
   * @see FFW_SLIDER()
   * @return void
   */
  public static function instance() {
    if ( ! isset( self::$instance ) && ! ( self::$instance instanceof FFW_SLIDER ) ) {
      self::$instance = new FFW_SLIDER;
      self::$instance->setup_constants();
      self::$instance->includes();
      // self::$instance->load_textdomain();
      // use @examples from public vars defined above upon implementation
    }
    return self::$instance;
  }



  /**
   * Setup plugin constants
   * @access private
   * @since 1.0 
   * @return void
   */
  private function setup_constants() {
    // Plugin version
    if ( ! defined( 'FFW_SLIDER_VERSION' ) )
      define( 'FFW_SLIDER_VERSION', '1.0' );

    // Plugin Folder Path
    if ( ! defined( 'FFW_SLIDER_PLUGIN_DIR' ) )
      define( 'FFW_SLIDER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

    // Plugin Folder URL
    if ( ! defined( 'FFW_SLIDER_PLUGIN_URL' ) )
      define( 'FFW_SLIDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

    // Plugin Root File
    if ( ! defined( 'FFW_SLIDER_PLUGIN_FILE' ) )
      define( 'FFW_SLIDER_PLUGIN_FILE', __FILE__ );

  }



  /**
   * Include required files
   * @access private
   * @since 1.0
   * @return void
   */
  private function includes() {
    global $ffw_slider_settings, $wp_version;

    require_once FFW_SLIDER_PLUGIN_DIR . '/includes/admin/settings/register-settings.php';
    $ffw_slider_settings = ffw_slider_get_settings();

    // Required Plugin Files
    require_once FFW_SLIDER_PLUGIN_DIR . '/includes/functions.php';
    require_once FFW_SLIDER_PLUGIN_DIR . '/includes/posttypes.php';
    require_once FFW_SLIDER_PLUGIN_DIR . '/includes/scripts.php';
    require_once FFW_SLIDER_PLUGIN_DIR . '/includes/shortcodes.php';

    if( is_admin() ){
        //Admin Required Plugin Files
        require_once FFW_SLIDER_PLUGIN_DIR . '/includes/admin/admin-pages.php';
        require_once FFW_SLIDER_PLUGIN_DIR . '/includes/admin/admin-notices.php';
        require_once FFW_SLIDER_PLUGIN_DIR . '/includes/admin/settings/display-settings.php';
        require_once FFW_SLIDER_PLUGIN_DIR . '/includes/admin/slides/metabox.php';

    }

    require_once FFW_SLIDER_PLUGIN_DIR . '/includes/install.php';


  }

} /* end FFW_SLIDER class */
endif; // End if class_exists check


/**
 * Main function for returning FFW_SLIDER Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $ffw_slider = FFW_SLIDER(); ?>
 *
 * @since 1.0
 * @return object The one true FFW_SLIDER Instance
 */
function FFW_SLIDER() {
  return FFW_SLIDER::instance();
}


/**
 * Initiate
 * Run the FFW_SLIDER() function, which runs the instance of the FFW_SLIDER class.
 */
FFW_SLIDER();



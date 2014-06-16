<?php
/**
 * Plugin Name.
 *
 * @package   Connecto
 * @author    Connecto <contact@thoughtfabrics.com>
 * @license   GPL-2.0+
 * @link      http://www.connecto.io
 * @copyright 2014 ThoughtFabrics Solutions Private Limited
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * @author    Connecto <contact@thoughtfabrics.com>
 */
class Connecto_Admin {

  /**
   * Instance of this class.
   *
   * @since    1.0.0
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Slug of the plugin screen.
   *
   * @since    1.0.0
   *
   * @var      string
   */
  protected $plugin_screen_hook_suffix = null;

  /**
   * Initialize the plugin by loading admin scripts & styles and adding a
   * settings page and menu.
   *
   * @since     1.0.0
   */
  private function __construct() {

    /*
     * @TODO :
     *
     * - Uncomment following lines if the admin class should only be available for super admins
     */
    /* if( ! is_super_admin() ) {
      return;
    } */

    /*
     * Call $plugin_slug from public plugin class.
     *
     * @TODO:
     *
     * - Rename "Connecto" to the name of your initial plugin class
     *
     */
    $plugin = Connecto::get_instance();
    $this->plugin_slug = $plugin->get_plugin_slug();

    // Load admin style sheet and JavaScript.
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

    // Add the options page and menu item.
    add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

    // Add an action link pointing to the options page.
    $plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
    add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

    add_action( 'admin_init', array( $this, 'connecto_redirect' ) );
    $redirect = $plugin->get_option( 'connecto_activation_redirect' );
    if (!redirect) {
      $plugin->save_option('connecto_activation_redirect', true);
    }
  }

  /**
   * Return an instance of this class.
   *
   * @since     1.0.0
   *
   * @return    object    A single instance of this class.
   */
  public static function get_instance() {
    // If the single instance hasn't been set, set it now.
    if ( null == self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;
  }

  /**
   * Redirect to connecto admin
   *
   * @since     1.0.0
   *
   */
  public function connecto_redirect() {
    $plugin = Connecto::get_instance();
    $redirect = $plugin->get_option( 'connecto_activation_redirect' );
    if ($redirect == true) {
        $plugin->save_option('connecto_activation_redirect', false);
        wp_redirect(admin_url( 'options-general.php?page=' . $this->plugin_slug ));
    }
  }

  /**
   * Register and enqueue admin-specific style sheet.
   *
   * @TODO:
   *
   * - Rename "Connecto" to the name your plugin
   *
   * @since     1.0.0
   *
   * @return    null    Return early if no settings page is registered.
   */
  public function enqueue_admin_styles() {

    if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
      return;
    }

    $screen = get_current_screen();
    if ( $this->plugin_screen_hook_suffix == $screen->id ) {
      wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), Connecto::VERSION );
    }

  }

  /**
   * Register and enqueue admin-specific JavaScript.
   *
   * @TODO:
   *
   * - Rename "Connecto" to the name your plugin
   *
   * @since     1.0.0
   *
   * @return    null    Return early if no settings page is registered.
   */
  public function enqueue_admin_scripts() {

    if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
      return;
    }

    $screen = get_current_screen();
    if ( $this->plugin_screen_hook_suffix == $screen->id ) {
      wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), Connecto::VERSION );
    }

  }

  /**
   * Register the administration menu for this plugin into the WordPress Dashboard menu.
   *
   * @since    1.0.0
   */
  public function add_plugin_admin_menu() {

    /*
     * Add a settings page for this plugin to the Settings menu.
     *
     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
     *
     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
     *
     * @TODO:
     *
     * - Change 'manage_options' to the capability you see fit
     *   For reference: http://codex.wordpress.org/Roles_and_Capabilities
     */
    $this->plugin_screen_hook_suffix = add_menu_page(
      __( 'Connecto', $this->plugin_slug ),
      __( 'Connecto', $this->plugin_slug ),
      'manage_options',
      $this->plugin_slug,
      array( $this, 'display_admin_page' ),
      plugins_url('assets/img/icon.png', __FILE__ )
    );

  }

  /**
   * Render the appropriate admin page.
   *
   * @since    1.0.0
   */
  public function display_admin_page() {
    if( !current_user_can( 'manage_options' ) ) {
      wp_die( 'You do not have sufficient permissions to access this page');
    }

    if( isset( $_POST ) && !empty( $_POST )) {
      if (isset( $_POST['change_api_key'])) {
        // Load admin page.
        $this->display_key_page();
        return;
      } else if (isset( $_POST['connecto_key'])) {
        // Save key.
        $connecto_key = (string) $_POST['connecto_key'];
        $this->save_key($connecto_key);
        $this->display_notification_page();
      }
    } else {
      $plugin = Connecto::get_instance();
      $connecto_key = $plugin->get_option( 'connecto_key' );
      if ($connecto_key && $connecto_key !== '') {
        $this->display_notification_page();
      } else {
        $this->display_key_page();
      }
    }
  }

  public function display_key_page() {
    $plugin = Connecto::get_instance();
    $connecto_key = $plugin->get_option( 'connecto_key' );
    $connecto_license_code = $plugin->get_option( 'connecto_license_key' );
    include_once( 'views/admin.php' );
  }

  public function display_notification_page() {
    $plugin = Connecto::get_instance();
    $connecto_key = $plugin->get_option( 'connecto_key' );
    $connecto_license_code = $plugin->get_option( 'connecto_license_key' );
    include_once( 'views/notifications.php' );
  }

  /**
   * Save API key.
   *
   * @since    1.0.0
   */
  public function save_key($key) {
    $plugin = Connecto::get_instance();
    $plugin->save_option('connecto_key', $key);
    $license_key = $this->get_license_key($key);
    $plugin->save_option('connecto_license_key', $license_key);
  }

  public function get_license_key($key) {
    $url = "http://www.connecto.io/accounts/get_license_key";
    $response = wp_remote_post( $url, array(
      'method' => 'POST',
      'timeout' => 45,
      'redirection' => 5,
      'httpversion' => '1.0',
      'blocking' => true,
      'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
      'body' => array( 'api_key' => $key),
      'cookies' => array()
    ));

    if ( is_wp_error( $response ) ) {
       $error_message = $response->get_error_message();
       echo "Error: $error_message";
    } else if (!empty( $response ) && isset( $response['body'] ) ) {
       $site = json_decode($response['body'], true);
       $license_key = $site['licenseKey'];
       return $license_key;
    } else {
       echo 'Response:<pre>';
       print_r( $response );
       echo '</pre>';
    }
  }

  /**
   * Add settings action link to the plugins page.
   *
   * @since    1.0.0
   */
  public function add_action_links( $links ) {

    return array_merge(
      array(
        'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
      ),
      $links
    );

  }

}

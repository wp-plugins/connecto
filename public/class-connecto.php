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
 * public-facing side of the WordPress site.
 *
 */
class Connecto {

  /**
   * Plugin version, used for cache-busting of style and script file references.
   *
   * @since   1.0.0
   *
   * @var     string
   */
  const VERSION = '1.0.0';

  /**
   * @TODO - Rename "connecto" to the name of your plugin
   *
   * Unique identifier for your plugin.
   *
   *
   * The variable name is used as the text domain when internationalizing strings
   * of text. Its value should match the Text Domain file header in the main
   * plugin file.
   *
   * @since    1.0.0
   *
   * @var      string
   */
  protected $plugin_slug = 'connecto';
  protected $option_name = '_connecto--options';

  /**
   * Instance of this class.
   *
   * @since    1.0.0
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Initialize the plugin by setting localization and loading public scripts
   * and styles.
   *
   * @since     1.0.0
   */
  private function __construct() {

    // Load plugin text domain
    add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

    // Activate plugin when new blog is added
    add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

    // Load public-facing style sheet and JavaScript.
    //add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
    //add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

    /* Define custom functionality.
     * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
     */
    add_action('wp_footer', array( $this, 'render_connecto_script') );

  }


  /**
   * Render our snippet.
   *
   * @since    1.0.0
   *
   */
  public function render_connecto_script() {
    $connecto_license_code = $this->get_option( 'connecto_license_key' );
    // render the widget if license code is present
    if ($connecto_license_code && $connecto_license_code !== '') {
    ?>
    <!-- Added via Connecto Wordpress Plugin 0.1.1 -->
    <script id="_connecto_script_tag" type="text/javascript">

    var _TConnecto = _TConnecto || {};
    _TConnecto.licenseKey = '<?php echo $connecto_license_code; ?>';

    (function() {
      var con = document.createElement('script'); con.type = 'text/javascript';
      var host = (document.location.protocol === 'http:') ? 'http://cdn' : 'https://server';
      con.src = host + '.connecto.io/javascripts/connect.prod.min.js';
      var s = document.getElementsByTagName('script')[0];
      s.parentNode.insertBefore(con, s);
    })();
    </script>
  <?php
    }
  }

  /**
   * Get a saved option.
   *
   * @since    1.0.0
   */
  public function get_option( $option_name ) {
    // Load option values if they haven't been loaded already
    if( !isset( $this->options ) || empty( $this->options ) ) {
        $this->options = get_option( $this->option_name, $this->defaults );
    }
    if( isset( $this->options[$option_name] ) ) {
        return $this->options[$option_name];    // Return user's specified option value
    } elseif( isset( $this->defaults[$option_name] ) ) {
        return $this->defaults[$option_name];   // Return default option value
    }
    return false;
  }

  /**
   * Get all options.
   *
   * @since    1.0.0
   */
  public function get_options() {
    if( !isset( $this->options ) || empty( $this->options ) ) {
      return get_option( $this->option_name, $this->defaults );
    }
    return $this->options;
  }

  /**
   * Save an option.
   *
   * @since    1.0.0
   */
  public function save_option( $key, $value ) {
    if( !isset( $this->options ) || empty( $this->options ) ) {
      $this->options = get_option( $this->option_name, $this->defaults );
    }
    $this->options[$key] = $value;
    $this->save_options($this->options);
  }

  /**
   * Save all options.
   *
   * @since    1.0.0
   */
  public function save_options( $options ) {
    update_option($this->option_name, $options );
  }

  /**
   * Return the plugin slug.
   *
   * @since    1.0.0
   *
   * @return    Plugin slug variable.
   */
  public function get_plugin_slug() {
    return $this->plugin_slug;
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
   * Fired when the plugin is activated.
   *
   * @since    1.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Activate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       activated on an individual blog.
   */
  public static function activate() {
  }

  /**
   * Fired when the plugin is deactivated.
   *
   * @since    1.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Deactivate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       deactivated on an individual blog.
   */
  public static function deactivate() {

  }

  /**
   * Fired when a new site is activated with a WPMU environment.
   *
   * @since    1.0.0
   *
   * @param    int    $blog_id    ID of the new blog.
   */
  public function activate_new_site( $blog_id ) {

  }

  /**
   * Get all blog ids of blogs in the current network that are:
   * - not archived
   * - not spam
   * - not deleted
   *
   * @since    1.0.0
   *
   * @return   array|false    The blog ids, false if no matches.
   */
  private static function get_blog_ids() {

    global $wpdb;

    // get an array of blog ids
    $sql = "SELECT blog_id FROM $wpdb->blogs
      WHERE archived = '0' AND spam = '0'
      AND deleted = '0'";

    return $wpdb->get_col( $sql );

  }

  /**
   * Fired for each blog when the plugin is activated.
   *
   * @since    1.0.0
   */
  private static function single_activate() {
  }

  /**
   * Fired for each blog when the plugin is deactivated.
   *
   * @since    1.0.0
   */
  private static function single_deactivate() {
  }

  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain() {

    $domain = $this->plugin_slug;
    $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

    load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
    load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

  }

  /**
   * Register and enqueue public-facing style sheet.
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {
    wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
  }

  /**
   * Register and enqueues public-facing JavaScript files.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts() {
    wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );
  }

}

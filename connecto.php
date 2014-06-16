<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   Connecto
 * @author    Connecto <contact@thoughtfabrics.com>
 * @license   GPL-2.0+
 * @link      http://www.connecto.io
 * @copyright 2014 ThoughtFabrics Solutions Private Limited
 *
 * @wordpress-plugin
 * Plugin Name:       Connecto
 * Plugin URI:        http://www.connecto.io
 * Description:       Connecto lets you add lead forms and notifications on your websites.
 * Version:           1.0.0
 * Author:            Connecto
 * Author URI:        http://www.connecto.io
 * Text Domain:       connecto-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
  die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 */
require_once( plugin_dir_path( __FILE__ ) . 'public/class-connecto.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'Connecto', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Connecto', 'deactivate' ) );

/*
 */
add_action( 'plugins_loaded', array( 'Connecto', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * @TODO:
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

  require_once( plugin_dir_path( __FILE__ ) . 'admin/class-connecto-admin.php' );
  add_action( 'plugins_loaded', array( 'Connecto_Admin', 'get_instance' ) );

}

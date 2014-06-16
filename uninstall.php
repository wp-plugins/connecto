<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Connecto
 * @author    Connecto <contact@thoughtfabrics.com>
 * @license   GPL-2.0+
 * @link      http://www.connecto.io
 * @copyright 2014 ThoughtFabrics Solutions Private Limited
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit;
}

global $wpdb;

/* @TODO: delete all transient, options and files you may have added
*/
$option_name = '_' . 'Connecto' . '--options';
delete_option($option_name);

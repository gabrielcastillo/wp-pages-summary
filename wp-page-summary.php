<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://gabrielcastillo.net/
 * @since             1.0.0
 * @package           Wp_Page_Summary
 *
 * @wordpress-plugin
 * Plugin Name:       WP Page Summary
 * Plugin URI:        https://gabrielcastillo.net/
 * Description:       WP Plugin to create summary text per page.
 * Version:           1.0.0
 * Author:            Gabriel Castillo
 * Author URI:        https://gabrielcastillo.net//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-page-summary
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_PAGE_SUMMARY_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-page-summary-activator.php
 */
function activate_wp_page_summary() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-page-summary-activator.php';
	Wp_Page_Summary_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-page-summary-deactivator.php
 */
function deactivate_wp_page_summary() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-page-summary-deactivator.php';
	Wp_Page_Summary_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_page_summary' );
register_deactivation_hook( __FILE__, 'deactivate_wp_page_summary' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-page-summary.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_page_summary() {

	$plugin = new Wp_Page_Summary();
	$plugin->run();

}
run_wp_page_summary();

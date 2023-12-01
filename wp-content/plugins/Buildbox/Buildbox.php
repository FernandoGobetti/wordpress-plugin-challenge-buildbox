<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Buildbox
 *
 * @wordpress-plugin
 * Plugin Name:       Buildbox
 * Description:       Plugin criado para teste de processo seletivo.
 * Version:           1.0.0
 * Author:            Fernando Gobetti
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       Buildbox
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('BUILDBOX_PATH', plugin_dir_path( __FILE__ ));
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-buildbox-activator.php
 */
function activate_buildbox() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-buildbox-activator.php';
	Buildbox_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-buildbox-deactivator.php
 */
function deactivate_buildbox() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-buildbox-deactivator.php';
	Buildbox_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_buildbox' );
register_deactivation_hook( __FILE__, 'deactivate_buildbox' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-buildbox.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_buildbox() {

	$plugin = new Buildbox();
	$plugin->run();

}
run_buildbox();

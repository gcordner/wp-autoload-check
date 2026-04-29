<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://geoffcordner.net
 * @since             1.0.0.0
 * @package           Plk_Autoload_Check
 *
 * @wordpress-plugin
 * Plugin Name:       plk autoload check
 * Plugin URI:        https://github.com/gcordner/plk-autoload-check
 * Description:       Sets specified options not to autoload, and emails an alert in autoloaded options exceed a set threshold.
 * Version:           1.0.0.1
 * Author:            Geoff Cordner
 * Author URI:        https://geoffcordner.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plk-autoload-check
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
define( 'PLK_AUTOLOAD_CHECK_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plk-autoload-check-activator.php
 */
function activate_plk_autoload_check() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plk-autoload-check-activator.php';
	Plk_Autoload_Check_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plk-autoload-check-deactivator.php
 */
function deactivate_plk_autoload_check() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-plk-autoload-check-deactivator.php';
	Plk_Autoload_Check_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_plk_autoload_check' );
register_deactivation_hook( __FILE__, 'deactivate_plk_autoload_check' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plk-autoload-check.php';

/**
 * The monitor class for the autoload check.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-plk-autoload-check-monitor.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0.1
 */
function run_plk_autoload_check() {

	$plugin = new Plk_Autoload_Check();
	$plugin->run();
}
run_plk_autoload_check();

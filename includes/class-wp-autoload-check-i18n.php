<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://geoffcordner.net
 * @since      1.0.0
 *
 * @package    Wp_Autoload_Check
 * @subpackage Wp_Autoload_Check/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Autoload_Check
 * @subpackage Wp_Autoload_Check/includes
 * @author     Geoff Cordner <geoffcordner@gmail.com>
 */
class Wp_Autoload_Check_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-autoload-check',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

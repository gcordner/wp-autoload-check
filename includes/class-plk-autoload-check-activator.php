<?php

/**
 * Fired during plugin activation
 *
 * @link       https://geoffcordner.net
 * @since      1.0.0
 *
 * @package    Plk_Autoload_Check
 * @subpackage Plk_Autoload_Check/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Plk_Autoload_Check
 * @subpackage Plk_Autoload_Check/includes
 * @author     Geoff Cordner <geoffcordner@gmail.com>
 */
class Plk_Autoload_Check_Activator {

	/**
	 * Code to run on plugin activation.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {
		// Schedule the cron event if it's not already scheduled.
		if ( ! wp_next_scheduled( 'plk_autoload_check_cron_hook' ) ) {
			wp_schedule_event( time(), 'hourly', 'plk_autoload_check_cron_hook' );
		}
	}
}

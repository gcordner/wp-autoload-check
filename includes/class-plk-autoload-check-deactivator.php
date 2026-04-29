<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://geoffcordner.net
 * @since      1.0.0
 *
 * @package    Plk_Autoload_Check
 * @subpackage Plk_Autoload_Check/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Plk_Autoload_Check
 * @subpackage Plk_Autoload_Check/includes
 * @author     Geoff Cordner <geoffcordner@gmail.com>
 */
class Plk_Autoload_Check_Deactivator {

	public static function deactivate() {
		wp_clear_scheduled_hook( 'plk_autoload_check_cron_hook' );
	}

}

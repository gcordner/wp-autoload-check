<?php
/**
 * WP Autoload Check Monitor
 *
 * This file contains the WP_Autoload_Check_Monitor class which is responsible for monitoring
 * the autoload functionality within the WordPress environment.
 *
 * @package    WP_Autoload_Check
 * @subpackage WP_Autoload_Check/includes
 * @author     Geoff Cordner
 * @license    GPL-2.0+
 * @link       https://example.com
 *
 * @since      1.0.0
 */

/**
 * Monitor the autoload size and send an email alert if it exceeds a threshold.
 */
class Plk_Autoload_Check_Monitor {

	/**
	 * Threshold for the autoload size in MB.
	 *
	 * @var float
	 */
	protected $threshold;

	/**
	 * Admin email address to send alerts.
	 *
	 * @var string
	 */
	protected $admin_email;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Get the threshold option with a fallback default of 0.3 MB.
		$this->threshold = get_option( 'plk_autoload_check_threshold', 0.3 );

		// Get the admin email option with a fallback default of the site's admin email.
		$this->admin_email = get_option( 'plk_autoload_check_email', get_option( 'admin_email' ) );
	}


	/**
	 * Execute the autoload check and send an email if the threshold is exceeded.
	 */
	public function run() {
		global $wpdb;

		// Calculate the total autoload size in MB.
		$autoload_size = $wpdb->get_var( // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			"SELECT ROUND(SUM(LENGTH(option_value)) / (1024 * 1024), 2) AS total_autoload_mb
             FROM $wpdb->options
             WHERE autoload = 'yes' OR autoload = 'on'"
		);

		if ( $autoload_size > $this->threshold ) {
			$site_name = get_bloginfo( 'name' );
			$site_url  = get_bloginfo( 'url' );
			$subject   = "Autoload Size Alert: {$site_name}";
			$message   = "Autoload size on <a href=\"{$site_url}\">{$site_name}</a> ({$site_url}) is currently {$autoload_size} MB.";
			$headers   = array( 'Content-Type: text/html; charset=UTF-8' );

			wp_mail( $this->admin_email, $subject, $message, $headers );
		}
	}
}

/**
 * Hook the monitor's run method to your custom cron event.
 *
 * This ensures that when your cron event fires, the autoload size is checked.
 */
add_action(
	'plk_autoload_check_cron_hook',
	function () {
		$monitor = new Plk_Autoload_Check_Monitor();
		$monitor->run();
	}
);

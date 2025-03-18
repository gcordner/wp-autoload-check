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
class Wp_Autoload_Check_Monitor {

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
		$this->threshold = get_option( 'wp_autoload_check_threshold', 0.3 );

		// Get the admin email option with a fallback default of the site's admin email.
		$this->admin_email = get_option( 'wp_autoload_check_email', get_option( 'admin_email' ) );
	}


	/**
	 * Execute the autoload check and send an email if needed.
	 */
	public function run() {
		global $wpdb;
		/* phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery */
		delete_transient( 'autoload_size_alert' ); // Delete the transient for testing purposes.

		// Calculate the total autoload size in MB.
		$autoload_size = $wpdb->get_var(
			"SELECT ROUND(SUM(LENGTH(option_value)) / (1024 * 1024), 2) AS total_autoload_mb 
             FROM $wpdb->options 
             WHERE autoload = 'yes' OR autoload = 'on'"
		);

		// If the autoload size exceeds the threshold, send an alert.
		if ( $autoload_size > $this->threshold ) {
			// Check if an alert has been sent recently (limit to 1 email per hour).
			if ( ! get_transient( 'autoload_size_alert' ) ) {
				$subject = 'WordPress Autoload Size Alert';
				$message = "The autoload size is currently {$autoload_size} MB.";
				$headers = array( 'Content-Type: text/html; charset=UTF-8' );

				wp_mail( $this->admin_email, $subject, $message, $headers );
				// Set a transient to avoid sending another email within an hour.
				set_transient( 'autoload_size_alert', true, HOUR_IN_SECONDS );
			}
		}
	}
}

/**
 * Hook the monitor's run method to your custom cron event.
 *
 * This ensures that when your cron event fires, the autoload size is checked.
 */
add_action(
	'wp_autoload_check_cron_hook',
	function () {
		$monitor = new Wp_Autoload_Check_Monitor();
		$monitor->run();
	}
);

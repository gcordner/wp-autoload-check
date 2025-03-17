<?php
/**
 * The file that defines the monitor class for the autoload check.
 *
 * This class monitors the total autoload size in the options table
 * and sends an email alert if it exceeds a specified threshold.
 *
 * @link       https://geoffcordner.net
 * @since      1.0.0
 *
 * @package    Wp_Autoload_Check
 * @since      1.0.0
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
		$this->threshold   = 0.3;
		$this->admin_email = 'geoffcordner@gmail.com';
	}

	/**
	 * Execute the autoload check and send an email if needed.
	 */
	public function run() {
		global $wpdb;

		delete_transient( 'autoload_size_alert' ); // Delete the transient for testing purposes.

		// Calculate the total autoload size in MB.
		$autoload_size = $wpdb->get_var(
			"SELECT ROUND(SUM(LENGTH(option_value)) / (1024 * 1024), 2) AS total_autoload_mb 
             FROM $wpdb->options 
             WHERE autoload = 'yes' OR autoload = 'on'"
		);

		// Log the calculated autoload size.
		error_log( 'Autoload size: ' . $autoload_size . ' MB' );

		// If the autoload size exceeds the threshold, send an alert.
		if ( $autoload_size > $this->threshold ) {
			// Check if an alert has been sent recently (limit to 1 email per hour).
			if ( ! get_transient( 'autoload_size_alert' ) ) {
				$subject = 'WordPress Autoload Size Alert';
				$message = "The autoload size is currently {$autoload_size} MB.";
				$headers = array( 'Content-Type: text/html; charset=UTF-8' );

				error_log( 'Threshold exceeded. Sending email alert.' );

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

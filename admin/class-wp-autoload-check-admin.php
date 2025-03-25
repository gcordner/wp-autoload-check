<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://geoffcordner.net
 * @since      1.0.0
 *
 * @package    Wp_Autoload_Check
 * @subpackage Wp_Autoload_Check/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two example hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Autoload_Check
 * @subpackage Wp_Autoload_Check/admin
 * @author     Geoff Cordner
 */
class Wp_Autoload_Check_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of this plugin.
	 * @param    string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name = 'wp-autoload-check', $version = '1.0.0' ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;

		// Register admin menu and settings hooks.
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-autoload-check-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-autoload-check-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Add settings page to the admin menu.
	 */
	public function add_settings_page() {
		add_options_page(
			'Autoload Monitor Settings',
			'Autoload Monitor',
			'manage_options',
			'wp-autoload-check',
			array( $this, 'settings_page_callback' )
		);
	}

	/**
	 * Register settings.
	 *
	 * This method now checks if the options already exist. If not, it creates
	 * them with the autoload parameter set to "no".
	 */
	public function register_settings() {
		// Ensure the email option is added with autoload "no" if it doesn't exist.
		if ( false === get_option( 'wp_autoload_check_email' ) ) {
			add_option( 'wp_autoload_check_email', get_option( 'admin_email' ), '', 'no' );
		}
		// Ensure the threshold option is added with autoload "no" if it doesn't exist.
		if ( false === get_option( 'wp_autoload_check_threshold' ) ) {
			add_option( 'wp_autoload_check_threshold', 0.3, '', 'no' );
		}

		// Register the settings (for use on the settings page).
		register_setting( 'wp_autoload_check_settings', 'wp_autoload_check_email' );
		register_setting( 'wp_autoload_check_settings', 'wp_autoload_check_threshold' );

		add_settings_section(
			'wp_autoload_check_main_section',
			'Autoload Check Settings',
			null,
			'wp-autoload-check'
		);

		add_settings_field(
			'wp_autoload_check_email',
			'Admin Email for Alerts',
			array( $this, 'email_field_callback' ),
			'wp-autoload-check',
			'wp_autoload_check_main_section'
		);

		add_settings_field(
			'wp_autoload_check_threshold',
			'Autoload Size Threshold (MB)',
			array( $this, 'threshold_field_callback' ),
			'wp-autoload-check',
			'wp_autoload_check_main_section'
		);
	}

	/**
	 * Email field callback.
	 */
	public function email_field_callback() {
		$email = get_option( 'wp_autoload_check_email', get_option( 'admin_email' ) );
		echo '<input type="email" name="wp_autoload_check_email" value="' . esc_attr( $email ) . '" />';
	}

	/**
	 * Threshold field callback.
	 */
	public function threshold_field_callback() {
		$threshold = get_option( 'wp_autoload_check_threshold', 0.3 );
		echo '<input type="number" step="0.01" name="wp_autoload_check_threshold" value="' . esc_attr( $threshold ) . '" />';
	}

	/**
	 * Settings page callback.
	 */
	public function settings_page_callback() {
		?>
		<div class="wrap">
			<h1>Autoload Monitor Settings</h1>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'wp_autoload_check_settings' );
				do_settings_sections( 'wp-autoload-check' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}

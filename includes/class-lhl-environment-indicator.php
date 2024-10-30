<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.lehelmatyus.com
 * @since      1.0.0
 *
 * @package    Lhl_Environment_Indicator
 * @subpackage Lhl_Environment_Indicator/includes
 * @author     Lehel Matyus <contact@lehelmatyus.com>
 */

use WpLHLAdminUi\Settings\SettingsLink;

class Lhl_Environment_Indicator {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Lhl_Environment_Indicator_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if (defined('LHL_ENVIRONMENT_INDICATOR_VERSION')) {
			$this->version = LHL_ENVIRONMENT_INDICATOR_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'lhl-environment-indicator';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Lhl_Environment_Indicator_Loader. Orchestrates the hooks of the plugin.
	 * - Lhl_Environment_Indicator_i18n. Defines internationalization functionality.
	 * - Lhl_Environment_Indicator_Admin. Defines all hooks for the admin area.
	 * - Lhl_Environment_Indicator_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for admin ui helper functions
		 */

		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'wp-lhl-admin-ui/wp-lhl-admin-ui.php';

		require_once __DIR__ . '/../vendor/autoload.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'models/LHLEnvironmentOptionsModel.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'services/LHLEnvironmentDetectorService.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-lhl-environment-indicator-loader.php';

		/**
		 * Email redirect Class
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-lhl-environment-indicator-emailredirect.php';

		/**
		 * REST API
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-lhl-environment-indicator-restapi.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-lhl-environment-indicator-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-lhl-environment-indicator-admin.php';

		/**
		 * Settings Page
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-lhl-environment-indicator-settings.php';

		/**
		 * Body Class indicator
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-lhl-environment-indicator-bodyclass.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-lhl-environment-indicator-public.php';

		$this->loader = new Lhl_Environment_Indicator_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Lhl_Environment_Indicator_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Lhl_Environment_Indicator_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		/**
		 * Styles
		 */
		$plugin_admin = new Lhl_Environment_Indicator_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		/**
		 * Rest APi stuff
		 */

		$plugin_restapi = new LHLNVND_Rest_API($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('rest_api_init', $plugin_restapi, 'register_routes');


		/**
		 * Settings Page
		 */
		$plugin_settings = new LHLNVND_Admin_Settings($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('admin_menu', $plugin_settings, 'setup_plugin_options_menu');
		$this->loader->add_action('admin_init', $plugin_settings, 'initialize_main_options');

		$this->loader->add_action('admin_init', $plugin_settings, 'initialize_environment_options');
		$this->loader->add_action('admin_init', $plugin_settings, 'initialize_email_options');
		$this->loader->add_action('admin_init', $plugin_settings, 'initialize_google_options');

		$plugin_body_class = new LHLNVND_body_class($this->get_plugin_name(), $this->get_version());
		$this->loader->add_filter('admin_body_class', $plugin_body_class, 'add_admin_body_class');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Lhl_Environment_Indicator_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		$plugin_body_class = new LHLNVND_body_class($this->get_plugin_name(), $this->get_version());
		$this->loader->add_filter('body_class', $plugin_body_class, 'add_body_class');

		/**
		 * Settings Link
		 */
		$settings_link_class = new SettingsLink($this->plugin_name, "lhl-environment-indicator", "/wp-admin/options-general.php?page=lhl_env_ind");
		$this->loader->add_filter('plugin_action_links', $settings_link_class, 'add_settings_link', 10, 2);
		// add_filter('plugin_action_links', array($this, 'add_settings_link'), 10, 2);



		/**
		 * Redirect Email
		 */
		$email_redirect_class = new LHLNVND_email_redirect();

		// Redirect Email
		$this->loader->add_filter('phpmailer_init', $email_redirect_class, 'redirect_email', 1000);
		// Modify Email
		$this->loader->add_filter('wp_mail', $email_redirect_class, 'modify_email', 1000);
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Lhl_Environment_Indicator_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}

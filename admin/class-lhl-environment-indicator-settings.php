<?php

/**
 * The settings of the plugin.
 *
 * @link       https://lehelmatyus.com
 * @since      1.0.0
 *
 * @package    lhlnvnd_Plugin
 * @subpackage lhlnvnd_Plugin/admin
 */

/**
 * Class WordPress_Plugin_Template_Settings
 *
 */

use WpLHLAdminUi\Forms\AdminForm as AdminForm;

class LHLNVND_Admin_Settings {

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
	private $env_options_model;
	private $env_options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$hello = new WpLHLAdminUi("lhl-env-ind");

		$this->env_options_model = new LHLEnvironmentOptionsModel();
		$this->env_options = $this->env_options_model->get_options();
	}

	/**
	 * This function introduces the theme options into the 'Appearance' menu and into a top-level
	 * 'Environment Indicator' menu.
	 */
	public function setup_plugin_options_menu() {

		//Add the menu to the Plugins set of menu items
		add_submenu_page(
			'options-general.php',
			'Environment indicator and Email redirect', 				  // The title to be displayed in the browser window for this page.
			'Environment and Email redirect',				  // The text to be displayed for this menu item
			'manage_options',					              // Which type of users can see this menu item
			'lhl_env_ind',			                      // The unique ID - that is, the slug - for this menu item
			array($this, 'render_settings_page_content')	  // The name of the function to call when rendering this menu's page
		);
	}

	/**
	 * Provides default values for the Settings.
	 *
	 * @return array
	 */
	public function default_main_options() {
	}
	public function default_envind_options() {
		$this->env_options_model->default_options();
	}
	public function default_email_options() {
		$defaults = array(
			'email_redirect_enable'		=>	'',
			'email_redirect_addr'		=>	'',
		);
		return $defaults;
	}

	/**
	 * Renders a simple page to display for the theme menu defined above.
	 */
	public function render_settings_page_content($active_tab = '') {

		$options = get_option('lhlnvnd_main_options');

?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php _e('LHL Environment indicator and Email redirect', 'lhl-environment-indicator'); ?></h2>
			<?php settings_errors(); ?>

			<?php if (isset($_GET['tab'])) {
				$active_tab = sanitize_text_field($_GET['tab']);
			} else if ($active_tab == 'environment_options') {
				$active_tab = 'environment_options';
			} else if ($active_tab == 'email_options') {
				$active_tab = 'email_options';
			} else if ($active_tab == 'google_options') {
				$active_tab = 'google_options';
			} else {
				$active_tab = 'main_options';
			} // end if/else 
			?>

			<h2 class="nav-tab-wrapper">
				<a href="?page=lhl_env_ind&tab=main_options" class="nav-tab <?php echo (esc_attr($active_tab) == 'main_options') ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-admin-generic"></span> <?php _e('Settings', 'lhl-environment-indicator'); ?></a>
				<a href="?page=lhl_env_ind&tab=environment_options" class="nav-tab <?php echo (esc_attr($active_tab) == 'environment_options') ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-admin-appearance"></span> <?php _e('Environment Indicator', 'lhl-environment-indicator'); ?></a>
				<a href="?page=lhl_env_ind&tab=email_options" class="nav-tab <?php echo (esc_attr($active_tab) == 'email_options') ? 'nav-tab-active' : ''; ?>"><span class="dashicons dashicons-email"></span> <?php _e('Email Redirect', 'lhl-environment-indicator'); ?></a>
				<!-- <a href="?page=lhl_env_ind&tab=google_options" class="nav-tab <?php echo (esc_attr($active_tab) == 'google_options') ? 'nav-tab-active' : ''; ?>"><?php _e('Google Analytics and Tags', 'lhl-environment-indicator'); ?></a> -->
			</h2>

			<form method="post" action="options.php" class="lhl__admin_form">
				<div class="lhl_container">
					<?php
					if ($active_tab == 'environment_options') {
						settings_fields('lhlnvnd_environment_options');
						do_settings_sections('lhlnvnd_environment_options');
						submit_button();
					} else if ($active_tab == 'email_options') {
						settings_fields('lhlnvnd_email_options');
						do_settings_sections('lhlnvnd_email_options');
						submit_button();
					?>
						<table class="form-table" role="presentation">
							<tbody>
								<tr>
									<th scope="row"></th>
									<td>
										<p class="description lhl-admin-description"><b><?php echo __('Save changes before sending Test Email.', 'lhl-environment-indicator'); ?></b> </p>
										<p class="description lhl-admin-description"><?php echo __('This button will attempt to send a test temail to the admin email:', 'lhl-environment-indicator');
																						echo " <u>" . get_bloginfo('admin_email') . "</u>"; ?> </p>
										<p class="description lhl-admin-description"><?php echo __('If redirection is working the recipients you set in "Redirect All Emails To" should recive the test email instead.', 'lhl-environment-indicator'); ?> </p>
										<div class="lhl__pt_15">
											<?php
											AdminForm::button(
												[],
												__("Send Test Email", "lhl-environment-indicator"),
												'lhl-env-ind-send-test-email',
												false,
												"lhlnvnd_send_test_email",
											);
											?>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					<?php

					} else if ($active_tab == 'google_options') {
						// settings_fields( 'lhlnvnd_main_options' );
						// do_settings_sections( 'lhlnvnd_main_options' );
						// submit_button();
					} else {
					?>
						<h2>

						</h2>

						<p>
							<b>Environment Indicator</b> - select what environment this is: Local, Development,Stage or Production. The Admin menu bar will be colored acording to it.
						</p>
						<p>
							<b>Email Redirect</b> - capability to redirect all outgoing emails to an email address of your choosing.
						</p>
					<?php
						// settings_fields( 'lhlnvnd_main_options' );
						// do_settings_sections( 'lhlnvnd_main_options' );
					}
					?>
				</div>
			</form>

		</div><!-- /.wrap -->
	<?php
	}

	/**
	 * This function provides a simple description for the generate_action options page.
	 */

	public function main_options_callback() {
		// echo '<p>' . __( 'Main options', 'lhl-environment-indicator' ) . '</p>';
	}
	public function envind_options_callback() {
		// echo '<p>' . __( 'Main options', 'lhl-environment-indicator' ) . '</p>';
	}
	public function email_options_callback() {
		// echo '<p>' . __( 'Main options', 'lhl-environment-indicator' ) . '</p>';
	}

	/**
	 * Initializes the theme's Settings page by registering the Sections,
	 * Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_main_options() {

		// delete_option('lhlnvnd_main_options');

		// If the theme options don't exist, create them.
		if (false == get_option('lhlnvnd_main_options')) {
			$default_array = $this->default_main_options();
			add_option('lhlnvnd_main_options', $default_array);
		}

		add_settings_section(
			'general_settings_section',			                       // ID used to identify this section and with which to register options
			'<span class="dashicons dashicons-admin-generic"></span> ' . __('Settings', 'lhl-environment-indicator'),		        // Title to be displayed on the administration page
			array($this, 'main_options_callback'),	    // Callback used to render the description of the section
			'lhlnvnd_main_options'		                     // Page on which to add this section of options
		);

		add_settings_field(
			'environment_id',
			__('This Environment is', 'lhl-environment-indicator'),
			array($this, 'render_environment_id'),
			'lhlnvnd_main_options',
			'general_settings_section'
		);

		add_settings_field(
			'email_redirect_enable',
			__('Enable All Email Redirect', 'lhl-environment-indicator'),
			array($this, 'email_redirect_enable_render'),
			'lhlnvnd_main_options',
			'general_settings_section'
		);

		add_settings_field(
			'email_redirect_addr',
			__('Redirect All Emails To', 'lhl-environment-indicator'),
			array($this, 'email_redirect_addr_render'),
			'lhlnvnd_main_options',
			'general_settings_section'
		);

		// Finally, we register the fields with WordPress
		register_setting(
			'lhlnvnd_main_options',
			'lhlnvnd_main_options',
			array($this, 'sanitize_main_options')
		);
	}


	public function initialize_environment_options() {

		// delete_option('lhlnvnd_environment_options');

		// If the theme options don't exist, create them.
		if (false == get_option('lhlnvnd_environment_options')) {
			$default_array = $this->default_envind_options();
			add_option('lhlnvnd_environment_options', $default_array);
		}

		add_settings_section(
			'env_ind_settings_section',			                       // ID used to identify this section and with which to register options
			'<span class="dashicons dashicons-admin-appearance"></span> ' . __('Environment Indicator Settings', 'lhl-environment-indicator'),		        // Title to be displayed on the administration page
			array($this, 'envind_options_callback'),	    // Callback used to render the description of the section
			'lhlnvnd_environment_options'		                     // Page on which to add this section of options
		);

		add_settings_field(
			'environment_id',
			__('This Environment is', 'lhl-environment-indicator'),
			array($this, 'render_environment_id'),
			'lhlnvnd_environment_options',
			'env_ind_settings_section'
		);

		// Finally, we register the fields with WordPress
		register_setting(
			'lhlnvnd_environment_options',
			'lhlnvnd_environment_options',
			array($this, 'sanitize_main_options')
		);
	}

	public function initialize_email_options() {

		$option_label = 'lhlnvnd_email_options';
		$section_id = "email_section";

		// delete_option($option_label);

		// If the theme options don't exist, create them.
		if (false == get_option($option_label)) {
			$default_array = $this->default_email_options();
			add_option($option_label, $default_array);
		}

		add_settings_section(
			$section_id,			                       	// ID used to identify this section and with which to register options
			'<span class="dashicons dashicons-email"></span> ' . __('Email Settings', 'lhl-environment-indicator'),	// Title to be displayed on the administration page
			array($this, 'email_options_callback'),	    	// Callback used to render the description of the section
			$option_label		                     			// Page on which to add this section of options
		);

		add_settings_field(
			'email_redirect_enable',
			__('Enable All Email Redirect', 'lhl-environment-indicator'),
			array($this, 'email_redirect_enable_render'),
			$option_label,
			$section_id
		);

		add_settings_field(
			'email_redirect_addr',
			__('Redirect All Emails To', 'lhl-environment-indicator'),
			array($this, 'email_redirect_addr_render'),
			$option_label,
			$section_id
		);

		// Finally, we register the fields with WordPress
		register_setting(
			$option_label,
			$option_label,
			array($this, 'sanitize_main_options')
		);
	}

	public function initialize_google_options() {
	}

	/***********************************************
	 * Form Elements
	 /**********************************************/

	public function render_environment_id($args) {
		// $options = get_option('lhlnvnd_environment_options');
		// v_dump($options);
		$options = $this->env_options;

		$select_options_array = [
			'def' => [
				'label' =>  __('- Hide Indicator -', 'lhl-environment-indicator'),
				'value' => "def",
			],
			'autodetect' => [
				'label' => __("Autodetect", "lhl-environment-indicator"),
				'value' => "auto",
			],
			"loc" => [
				'label' => __('Local', 'lhl-environment-indicator'),
				'value' => "loc",
			],
			"dev" => [
				'label' => __('Development', 'lhl-environment-indicator'),
				'value' => "dev",
			],
			"stg" => [
				'label' => __('Stage', 'lhl-environment-indicator'),
				'value' => "stg",
			],
			"prd" => [
				'label' => __('Production', 'lhl-environment-indicator'),
				'value' => "prd",
			],
			"cus" => [
				'label' => __('Custom', 'lhl-environment-indicator'),
				'value' => "cus",
			]
		];

		AdminForm::select(
			$options,
			$this->env_options_model->get_options_name(),
			'environment_id',
			$select_options_array,
			false
		);

	?>

		<p class="description lhl-admin-description"> <?php echo __('Select a label for this website. Admin bar will be colored accordingly.', 'lhl-environment-indicator'); ?> </p>
		<p class="description lhl-admin-description"> <?php echo __('Autodetect will try to do a best guess for the environment based on WP_ENV, URL patterns etc. It may not always grant desired result.', 'lhl-environment-indicator'); ?> </p>
	<?php
	}


	/**
	 * Form Elements
	 */
	public function email_redirect_enable_render($args) {
		$options_label = "lhlnvnd_email_options";
		$options = get_option($options_label);

		$select_options_array = [
			'disabled' => [
				'label' => " Do not redirect Emails ",
				'value' => "disabled",
			],
			"enabled" => [
				'label' => __('Redirect All Emails', 'lhl-environment-indicator'),
				'value' => "enabled",
			],
		];

		AdminForm::select(
			$options,
			$options_label,
			'email_redirect_enable',
			$select_options_array,
			false
		);

	?>
		<p class="description lhl-admin-description"> <?php echo __('Rerouting emails blocks users from getting various email notifications from this website. Useful for development and staging environments. redirects to WordPress Administration Email Address for this website unless specified otherwise below.', 'lhl-environment-indicator'); ?> </p>

	<?php
	}

	/**
	 * Form Elements
	 */
	public function email_redirect_addr_render($args) {
		$options_label = "lhlnvnd_email_options";
		$options = get_option($options_label);

		AdminForm::email_input_multi(
			$options,
			$options_label,
			'email_redirect_addr',
			false
		);

	?>
		<p class="description lhl-admin-description"> <?php echo __('Comma separated multiple emails, invalid email formats will be removed.', 'lhl-environment-indicator'); ?> </p>
<?php
	}


	public function sanitize_main_options($input) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach ($input as $key => $value) {

			// Check to see if the current option has a value. If so, process it.
			if (isset($input[$key])) {

				// Strip all HTML and PHP tags and properly handle quoted strings
				$output[$key] = strip_tags(stripslashes($input[$key]));
			} // end if

		} // end foreach

		// Return the array processing any additional functions filtered by this action
		return apply_filters('validate_generate_action', $output, $input);
	} // end validate_generate_action


}

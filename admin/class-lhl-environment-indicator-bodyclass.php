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
class LHLNVND_body_class {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	function add_body_class($classes) {

		if (current_user_can('manage_options')) {
			$options = new LHLEnvironmentOptionsModel();
			$env_id = $options->get_option('environment_id');
			if (!empty($env_id)) {
				// if not default, as in none selected
				if ($env_id !== 'def') {
					$classes[] = 'lhl_env_ind';
					$classes[] = 'lhl_env_pill';
					if ($env_id == 'auto') {
						$detector = new LHLEnvironmentDetectorService();
						$classes[] = 'lhl_' . $detector->detect_environment_id();
					} else {
						$classes[] = 'lhl_' . $env_id;
					}
				}
			}
		}
		return $classes;
	}

	function add_admin_body_class($classes) {

		if (current_user_can('manage_options')) {
			$options = new LHLEnvironmentOptionsModel();
			$env_id = $options->get_option('environment_id');
			if (!empty($env_id)) {
				$my_classes = [];
				// if not default, as in none selected
				if ($env_id !== 'def') {
					$my_classes[] = 'lhl_env_ind';
					$my_classes[] = 'lhl_env_pill';
					if ($env_id == 'auto') {
						$detector = new LHLEnvironmentDetectorService();
						$my_classes[] = 'lhl_' . $detector->detect_environment_id();
					} else {
						$my_classes[] = 'lhl_' . $env_id;
					}
				}
				$classes .= implode(' ', $my_classes);
			}
		}

		error_log(print_r($classes, true));

		return $classes;
	}
}

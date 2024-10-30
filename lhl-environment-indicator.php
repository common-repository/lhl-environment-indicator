<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.lehelmatyus.com
 * @since             1.0.0
 * @package           Lhl_Environment_Indicator
 *
 * @wordpress-plugin
 * Plugin Name:       LHL Environment Indicator and Email Redirect
 * Plugin URI:        https://www.lehelmatyus.com/lhl-environment-indicator
 * Description:       Simple environment indicator that lets amin users know which wordpress website environment they are on.
 * Version:           1.0.8
 * Author:            Lehel Matyus
 * Author URI:        https://www.lehelmatyus.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       lhl-environment-indicator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('LHL_ENVIRONMENT_INDICATOR_VERSION', '1.0.8');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-lhl-environment-indicator-activator.php
 */
function activate_lhl_environment_indicator() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-lhl-environment-indicator-activator.php';
	Lhl_Environment_Indicator_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-lhl-environment-indicator-deactivator.php
 */
function deactivate_lhl_environment_indicator() {
	require_once plugin_dir_path(__FILE__) . 'includes/class-lhl-environment-indicator-deactivator.php';
	Lhl_Environment_Indicator_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_lhl_environment_indicator');
register_deactivation_hook(__FILE__, 'deactivate_lhl_environment_indicator');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-lhl-environment-indicator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_lhl_environment_indicator() {

	$plugin = new Lhl_Environment_Indicator();
	$plugin->run();
}
run_lhl_environment_indicator();

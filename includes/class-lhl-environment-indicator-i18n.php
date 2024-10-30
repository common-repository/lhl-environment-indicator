<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.lehelmatyus.com
 * @since      1.0.0
 *
 * @package    Lhl_Environment_Indicator
 * @subpackage Lhl_Environment_Indicator/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Lhl_Environment_Indicator
 * @subpackage Lhl_Environment_Indicator/includes
 * @author     Lehel Matyus <contact@lehelmatyus.com>
 */
class Lhl_Environment_Indicator_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'lhl-environment-indicator',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}

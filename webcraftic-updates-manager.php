<?php
	/**
	 * Plugin Name: Webcraftic Updates manager
	 * Plugin URI: https://wordpress.org/plugins/webcraftic-updates-manager/
	 * Description: Manage all your WordPress updates, automatic updates, logs, and loads more.
	 * Author: Webcraftic <wordpress.webraftic@gmail.com>
	 * Version: 1.0.6
	 * Text Domain: webcraftic-updates-manager
	 * Domain Path: /languages/
	 * Author URI: http://webcraftic.com
	 */

	if( defined('WUP_PLUGIN_ACTIVE') || (defined('WCL_PLUGIN_ACTIVE') && !defined('LOADING_UPDATES_MANAGER_AS_ADDON')) ) {
		function wbcr_upm_admin_notice_error()
		{
			?>
			<div class="notice notice-error">
				<p><?php _e('We found that you have the "Clearfy - disable unused features" plugin installed, this plugin already has update manager functions, so you can deactivate plugin "Update manager"!'); ?></p>
			</div>
		<?php
		}

		add_action('admin_notices', 'wbcr_upm_admin_notice_error');

		return;
	} else {

		define('WUP_PLUGIN_ACTIVE', true);

		define('WUP_PLUGIN_DIR', dirname(__FILE__));
		define('WUP_PLUGIN_BASE', plugin_basename(__FILE__));
		define('WUP_PLUGIN_URL', plugins_url(null, __FILE__));

		#comp remove
		// the following constants are used to debug features of diffrent builds
		// on developer machines before compiling the plugin

		// build: free, premium, ultimate
		if( !defined('BUILD_TYPE') ) {
			define('BUILD_TYPE', 'free');
		}
		// language: en_US, ru_RU
		if( !defined('LANG_TYPE') ) {
			define('LANG_TYPE', 'en_EN');
		}
		// license: free, paid
		if( !defined('LICENSE_TYPE') ) {
			define('LICENSE_TYPE', 'free');
		}

		// wordpress language
		if( !defined('WPLANG') ) {
			define('WPLANG', LANG_TYPE);
		}
		// the compiler library provides a set of functions like onp_build and onp_license
		// to check how the plugin work for diffrent builds on developer machines

		if( !defined('LOADING_UPDATES_MANAGER_AS_ADDON') ) {
			require('libs/onepress/compiler/boot.php');
			// creating a plugin via the factory
		}
		// #fix compiller bug new Factory000_Plugin
		#endcomp

		if( !defined('LOADING_UPDATES_MANAGER_AS_ADDON') ) {
			require_once(WUP_PLUGIN_DIR . '/libs/factory/core/boot.php');
		}

		require_once(WUP_PLUGIN_DIR . '/includes/class.plugin.php');

		if( !defined('LOADING_UPDATES_MANAGER_AS_ADDON') ) {
			new WUP_Plugin(__FILE__, array(
				'prefix' => 'wbcr_updates_manager_',//wbcr_upm_
				'plugin_name' => 'wbcr_updates_manager',
				'plugin_title' => __('Webcraftic Updates Manager', 'webcraftic-updates-manager'),
				'plugin_version' => '1.0.6',
				'required_php_version' => '5.2',
				'required_wp_version' => '4.2',
				'plugin_build' => BUILD_TYPE,
				//'updates' => WUP_PLUGIN_DIR . '/updates/'
			));
		}
	}
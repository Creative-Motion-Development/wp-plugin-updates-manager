<?php

	// if uninstall.php is not called by WordPress, die
	if( !defined('WP_UNINSTALL_PLUGIN') ) {
		die;
	}

	// remove plugin options
	global $wpdb;

	// todo: перенести в экшен и добавить удаление для Clearfy Api
	if( is_multisite() && is_plugin_active_for_network(plugin_basename(dirname(__FILE__) . '/webcraftic-updates-manager.php')) ) {
		$wpdb->query("DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE 'wbcr_updates_manager_%';");
		$wpdb->query("DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE 'wbcr_upm_%';");
	} else {
		$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'wbcr_updates_manager_%';");
		$wpdb->query("DELETE FROM {$wpdb->prefix}options WHERE option_name LIKE 'wbcr_upm_%';");
	}

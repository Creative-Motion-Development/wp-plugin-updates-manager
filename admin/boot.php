<?php
	/**
	 * Admin boot
	 * @author Webcraftic <alex.kovalevv@gmail.com>
	 * @copyright Webcraftic 25.05.2017
	 * @version 1.0
	 */

	require_once(WBCR_UPM_PLUGIN_DIR . '/admin/pages/updates.php');
	require_once(WBCR_UPM_PLUGIN_DIR . '/admin/pages/plugins.php');
	require_once(WBCR_UPM_PLUGIN_DIR . '/admin/pages/advanced.php');

	if( !defined('LOADING_UPDATES_MANAGER_AS_ADDON') ) {
		require_once(WBCR_UPM_PLUGIN_DIR . '/admin/pages/more-features.php');
	}

	/*add_filter('wbcr_factory_imppage_bottom_sidebar_widgets', 'wbcr_upm_page_bottom_sidebar', 10, 2);

	function wbcr_upm_page_bottom_sidebar($widgets, $page_id)
	{
		if( in_array($page_id, array('updates', 'plugins', 'advanced')) ) {
			$widgets['rating_widget'] = $this->getRatingWidget('https://goo.gl/v4QkW5');
		}

		return $widgets;
	}*/

	function wbcr_upm_group_options($options)
	{
		$options[] = array(
			'name' => 'plugin_updates',
			'title' => __('Disable plugin updates', 'webcraftic-updates-manager'),
			'tags' => array('disable_all_updates'),
			'values' => array('disable_all_updates' => 'disable_plugin_updates')
		);
		$options[] = array(
			'name' => 'theme_updates',
			'title' => __('Disable theme updates', 'webcraftic-updates-manager'),
			'tags' => array('disable_all_updates'),
			'values' => array('disable_all_updates' => 'disable_theme_updates')
		);
		$options[] = array(
			'name' => 'auto_tran_update',
			'title' => __('Disable Automatic Translation Updates', 'webcraftic-updates-manager'),
			'tags' => array('disable_all_updates')
		);
		$options[] = array(
			'name' => 'wp_update_core',
			'title' => __('Disable wordPress core updates', 'webcraftic-updates-manager'),
			'tags' => array('disable_all_updates'),
			'values' => array('disable_all_updates' => 'disable_core_updates')
		);
		$options[] = array(
			'name' => 'enable_update_vcs',
			'title' => __('Enable updates for VCS Installations', 'webcraftic-updates-manager'),
			'tags' => array()
		);
		$options[] = array(
			'name' => 'plugins_update_filters',
			'title' => __('Plugin filters', 'webcraftic-updates-manager'),
			'tags' => array()
		);

		return $options;
	}

	add_filter("wbcr_clearfy_group_options", 'wbcr_upm_group_options');

	function wbcr_upm_allow_quick_mods($mods)
	{
		$mods['disable_all_updates'] = array(
			'title' => __('One click disable all updates', 'webcraftic-updates-manager'),
			'icon' => 'dashicons-update'
		);

		return $mods;
	}

	add_filter("wbcr_clearfy_allow_quick_mods", 'wbcr_upm_allow_quick_mods');

	function wbcr_ump_set_plugin_meta($links, $file)
	{
		if( $file == WBCR_UPM_PLUGIN_BASE ) {
			$links[] = '<a href="https://goo.gl/TcMcS4" style="color: #FF5722;font-weight: bold;" target="_blank">' . __('Get ultimate plugin free', 'webcraftic-updates-manager') . '</a>';
		}

		return $links;
	}

	if( !defined('LOADING_UPDATES_MANAGER_AS_ADDON') ) {
		add_filter('plugin_row_meta', 'wbcr_ump_set_plugin_meta', 10, 2);
	}






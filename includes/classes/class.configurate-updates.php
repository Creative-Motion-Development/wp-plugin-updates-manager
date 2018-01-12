<?php
	
	/**
	 * This class configures the parameters seo
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 2017 Webraftic Ltd
	 * @version 1.0
	 */
	class WbcrUpm_ConfigUpdates extends WbcrFactoryClearfy_Configurate {

		public function registerActionsAndFilters()
		{
			/**
			 * Plugin updates
			 */

			switch( $this->getOption('plugin_updates') ) {
				case 'disable_plugin_updates':
					$this->disableAllPluginUpdates();
					break;
				case 'enable_plugin_auto_updates':
					add_filter('auto_update_plugin', '__return_true', 1);
					break;
			}

			/**
			 * Theme updates
			 */

			switch( $this->getOption('theme_updates') ) {
				case 'disable_theme_updates':
					$this->disableAllThemesUpdates();
					break;
				case 'enable_theme_auto_updates':
					add_filter('auto_update_plugin', '__return_true', 1);
					break;
			}

			/**
			 * disable wp default translation update
			 */

			if( $this->getOption('auto_tran_update') ) {
				add_filter('auto_update_translation', '__return_false', 1);
			}

			/**
			 * control WP Auto core update
			 */

			switch( $this->getOption('wp_update_core') ) {
				case 'disable_core_updates':
					$this->disableAllCoreUpdates();
					break;
				case 'disable_core_auto_updates':
					add_filter('allow_major_auto_core_updates', '__return_false');
					add_filter('allow_dev_auto_core_updates', '__return_false');
					add_filter('allow_minor_auto_core_updates', '__return_false');
					break;
				case 'major':
					add_filter('allow_major_auto_core_updates', '__return_true');
					break;
				case 'development':
					add_filter('allow_dev_auto_core_updates', '__return_true');
					break;
				default:
					add_filter('allow_minor_auto_core_updates', '__return_true');
					break;
			}

			/**
			 * disable wp default translation update
			 */

			if( $this->getOption('enable_update_vcs') ) {
				add_filter('automatic_updates_is_vcs_checkout', '__return_false', 1);
			}
		}

		public function disableAllCoreUpdates()
		{
			add_action('admin_init', array($this, 'adminInitForCore'));

			/*
			 * Disable Core Updates
			 * 2.8 to 3.0
			 */
			add_filter('pre_transient_update_core', array($this, 'lastCheckedNow'));
			/*
			 * 3.0
			 */
			add_filter('pre_site_transient_update_core', array($this, 'lastCheckedNow'));

			/*
			 * Disable All Automatic Updates
			 * 3.7+
			 *
			 * @author	sLa NGjI's @ slangji.wordpress.com
			 */
			add_filter('automatic_updater_disabled', '__return_true');
			add_filter('allow_minor_auto_core_updates', '__return_false');
			add_filter('allow_major_auto_core_updates', '__return_false');
			add_filter('allow_dev_auto_core_updates', '__return_false');
			add_filter('auto_update_core', '__return_false');
			add_filter('wp_auto_update_core', '__return_false');
			add_filter('auto_core_update_send_email', '__return_false');
			add_filter('send_core_update_notification_email', '__return_false');
			add_filter('automatic_updates_send_debug_email', '__return_false');
			add_filter('automatic_updates_is_vcs_checkout', '__return_true');
		}

		public function disableAllPluginUpdates()
		{
			add_action('admin_init', array($this, 'adminInitForPlugins'));

			/*
			 * Disable Plugin Updates
			 * 2.8 to 3.0
			 */
			add_action('pre_transient_update_plugins', array($this, 'lastCheckedNow'));
			/*
			 * 3.0
			 */
			add_filter('pre_site_transient_update_plugins', array($this, 'lastCheckedNow'));

			/*
			 * Disable All Automatic Updates
			 * 3.7+
			 *
			 * @author	sLa NGjI's @ slangji.wordpress.com
			 */
			add_filter('auto_update_plugin', '__return_false');
		}

		public function disableAllThemesUpdates()
		{
			add_action('admin_init', array($this, 'adminInitForThemes'));

			/*
			 * Disable Theme Updates
			 * 2.8 to 3.0
			 */
			add_filter('pre_transient_update_themes', array($this, 'lastCheckedNow'));
			/*
			 * 3.0
			 */
			add_filter('pre_site_transient_update_themes', array($this, 'lastCheckedNow'));

			/*
			 * Disable All Automatic Updates
			 * 3.7+
			 *
			 * @author	sLa NGjI's @ slangji.wordpress.com
			 */
			add_filter('auto_update_theme', '__return_false');
		}

		public function disableAllTranslationUpdates()
		{
			/*
		 * Disable All Automatic Updates
		 * 3.7+
		 *
		 * @author	sLa NGjI's @ slangji.wordpress.com
		 */
			add_filter('auto_update_translation', '__return_false');

			/*
			 * Disable Theme Translations
			 * 2.8 to 3.0
			 */
			add_filter('transient_update_themes', array($this, 'removeTranslations'));
			/*
			 * 3.0
			 */
			add_filter('site_transient_update_themes', array($this, 'removeTranslations'));

			/*
			 * Disable Plugin Translations
			 * 2.8 to 3.0
			 */
			add_action('transient_update_plugins', array($this, 'removeTranslations'));
			/*
			 * 3.0
			 */
			add_filter('site_transient_update_plugins', array($this, 'removeTranslations'));

			/*
			 * Disable Core Translations
			 * 2.8 to 3.0
			 */
			add_filter('transient_update_core', array($this, 'removeTranslations'));
			/*
			 * 3.0
			 */
			add_filter('site_transient_update_core', array($this, 'removeTranslations'));
		}
		
		public function removeTranslations($transient)
		{
			
			if( is_object($transient) && isset($transient->translations) ) {
				$transient->translations = array();
			}

			return $transient;
		}


		/**
		 * Initialize and load the plugin stuff
		 *
		 * @since        1.3
		 * @author        scripts@schloebe.de
		 */
		function adminInitForPlugins()
		{
			/*
			 * Disable Plugin Updates
			 * 2.8 to 3.0
			 */
			remove_action('load-plugins.php', 'wp_update_plugins');
			remove_action('load-update.php', 'wp_update_plugins');
			remove_action('admin_init', '_maybe_update_plugins');
			remove_action('wp_update_plugins', 'wp_update_plugins');
			wp_clear_scheduled_hook('wp_update_plugins');

			/*
			 * 3.0
			 */
			remove_action('load-update-core.php', 'wp_update_plugins');
			wp_clear_scheduled_hook('wp_update_plugins');
		}

		function adminInitForThemes()
		{
			/*
			 * Disable Theme Updates
			 * 2.8 to 3.0
			 */
			remove_action('load-themes.php', 'wp_update_themes');
			remove_action('load-update.php', 'wp_update_themes');
			remove_action('admin_init', '_maybe_update_themes');
			remove_action('wp_update_themes', 'wp_update_themes');
			wp_clear_scheduled_hook('wp_update_themes');

			/*
			 * 3.0
			 */
			remove_action('load-update-core.php', 'wp_update_themes');
			wp_clear_scheduled_hook('wp_update_themes');
		}

		/**
		 * Initialize and load the plugin stuff
		 *
		 * @since        1.3
		 * @author        scripts@schloebe.de
		 */
		function adminInitForCore()
		{
			/*
			 * Disable Core Updates
			 * 2.8 to 3.0
			 */
			remove_action('wp_version_check', 'wp_version_check');
			remove_action('admin_init', '_maybe_update_core');
			wp_clear_scheduled_hook('wp_version_check');

			/*
			 * 3.0
			 */
			wp_clear_scheduled_hook('wp_version_check');

			/*
			 * 3.7+
			 */
			remove_action('wp_maybe_auto_update', 'wp_maybe_auto_update');
			remove_action('admin_init', 'wp_maybe_auto_update');
			remove_action('admin_init', 'wp_auto_update_core');
			wp_clear_scheduled_hook('wp_maybe_auto_update');
		}

		public function lastCheckedNow($transient)
		{
			global $wp_version;

			include ABSPATH . WPINC . '/version.php';
			$current = new stdClass;
			$current->updates = array();
			$current->version_checked = $wp_version;
			$current->last_checked = time();

			return $current;
		}
	}
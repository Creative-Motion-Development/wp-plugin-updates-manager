<?php

	/**
	 * The page Settings.
	 *
	 * @since 1.0.0
	 */
	class WbcrUpm_UpdatesPage extends FactoryPages000_ImpressiveThemplate {

		/**
		 * The id of the page in the admin menu.
		 *
		 * Mainly used to navigate between pages.
		 * @see FactoryPages000_AdminPage
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $id = "updates";

		public $page_menu_dashicon = 'dashicons-cloud';

		public function __construct(Factory000_Plugin $plugin)
		{
			$this->menuTitle = __('Updates manager', 'webcraftic-updates-manager');

			if( !defined('LOADING_UPDATES_MANAGER_AS_ADDON') ) {
				$this->internal = false;
				$this->menuTarget = 'options-general.php';
				$this->addLinkToPluginActions = true;
			}

			parent::__construct($plugin);
		}

		public function getMenuTitle()
		{
			return defined('LOADING_UPDATES_MANAGER_AS_ADDON')
				? __('Updates', 'webcraftic-updates-manager')
				: __('General', 'webcraftic-updates-manager');
		}

		/**
		 * Permalinks options.
		 *
		 * @since 1.0.0
		 * @return mixed[]
		 */
		public function getOptions()
		{
			$options = array();

			$options[] = array(
				'type' => 'dropdown',
				'name' => 'plugin_updates',
				'way' => 'buttons',
				'title' => __('Plugin Updates', 'webcraftic-updates-manager'),
				'data' => array(
					array('enable_plugin_monual_updates', __('Manual updates', 'webcraftic-updates-manager')),
					array('enable_plugin_auto_updates', __('Enable auto updates', 'webcraftic-updates-manager')),
					array('disable_plugin_updates', __('Disable updates', 'webcraftic-updates-manager'))
				),
				'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				'hint' => __('You can disable all plugin updates or choose manual or automatic update mode.', 'webcraftic-updates-manager'),
				'default' => 'enable_plugin_monual_updates'
			);

			$options[] = array(
				'type' => 'dropdown',
				'name' => 'theme_updates',
				'way' => 'buttons',
				'title' => __('Theme Updates', 'webcraftic-updates-manager'),
				'data' => array(
					array('enable_theme_monual_updates', __('Manual updates', 'webcraftic-updates-manager')),
					array('enable_theme_auto_updates', __('Enable auto updates', 'webcraftic-updates-manager')),
					array('disable_theme_updates', __('Disable updates', 'webcraftic-updates-manager'))
				),
				'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				'hint' => __('You can disable all themes updates or choose manual or automatic update mode.', 'webcraftic-updates-manager'),
				'default' => 'enable_theme_monual_updates'
			);

			$options[] = array(
				'type' => 'checkbox',
				'way' => 'buttons',
				'name' => 'auto_tran_update',
				'title' => __('Disable Automatic Translation Updates', 'webcraftic-updates-manager'),
				//'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				//'hint' => __('', 'webcraftic-updates-manager') . '<br><br><b>Clearfy: </b>' . __('', 'webcraftic-updates-manager'),
				'default' => false,
			);
			$options[] = array(
				'type' => 'dropdown',
				'name' => 'wp_update_core',
				'title' => __('WordPress Core Updates', 'webcraftic-updates-manager'),
				'data' => array(
					array('disable_core_updates', __('Disable updates', 'webcraftic-updates-manager')),
					array('disable_core_auto_updates', __('Disable auto updates', 'webcraftic-updates-manager')),
					array(
						'allow_minor_core_auto_updates',
						__('Allow minor auto updates', 'webcraftic-updates-manager')
					),
					array(
						'allow_major_core_auto_updates',
						__('Allow major auto updates', 'webcraftic-updates-manager')
					),
					array(
						'allow_dev_core_auto_updates',
						__('Allow development auto updates', 'webcraftic-updates-manager')
					)
				),
				'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				'hint' => __('You can disable all core WordPress updates, or disable only automatic updates. Also you can select the update mode. By default (minor)', 'webcraftic-updates-manager') . '<br>-' . __('Major - automatically update to major releases (e.g., 4.1, 4.2, 4.3).', 'webcraftic-updates-manager') . '<br>-' . __('Minor - automatically update to minor releases (e.g., 4.1.1, 4.1.2, 4.1.3)..', 'webcraftic-updates-manager') . '<br>-' . __('Development - update automatically to Bleeding Edge releases.', 'webcraftic-updates-manager'),
				'default' => 'allow_minor_core_auto_updates'
			);

			$options[] = array(
				'type' => 'checkbox',
				'way' => 'buttons',
				'name' => 'enable_update_vcs',
				'title' => __('Enable updates for VCS Installations', 'webcraftic-updates-manager'),
				'layout' => array('hint-type' => 'icon', 'hint-icon-color' => 'grey'),
				'hint' => __('Enable Automatic Updates even if a VCS folder (.git, .hg, .svn) was found in the WordPress directory', 'webcraftic-updates-manager'),
				'default' => false,
			);

			/*$options[] = array(
				'type' => 'separator',
				'cssClass' => 'factory-separator-dashed'
			);

			$options[] = array(
				'type' => 'html',
				'html' => array($this, '_showFormButton')
			);*/

			$formOptions = array();

			$formOptions[] = array(
				'type' => 'form-group',
				'items' => $options,
				//'cssClass' => 'postbox'
			);

			return apply_filters('wbcr_clr_seo_form_options', $formOptions);
		}
	}

	FactoryPages000::register($wbcr_update_services_plugin, 'WbcrUpm_UpdatesPage');

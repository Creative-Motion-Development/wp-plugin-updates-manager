<?php

	/**
	 * The page Settings.
	 *
	 * @since 1.0.0
	 */
	class WbcrUpm_AdvancedPage extends FactoryPages000_ImpressiveThemplate {

		/**
		 * The id of the page in the admin menu.
		 *
		 * Mainly used to navigate between pages.
		 * @see FactoryPages000_AdminPage
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $id = "advanced";

		public $type = "page";

		public $page_parent_page = 'updates';

		public $page_menu_dashicon = 'dashicons-cloud';

		public function __construct(Factory000_Plugin $plugin)
		{
			$this->menuTitle = __('Advanced', 'webcraftic-updates-manager');

			parent::__construct($plugin);
		}


		public function warningNotice()
		{
			parent::warningNotice();

			if( isset($_GET['wbcr_force_update']) ) {
				$concat = __('Пожалуйста, подождите 90 сек, что увидите результат принудетельного автоматического обновление.', 'webcraftic-updates-manager') . '<br>';

				$this->printWarningNotice($concat);
			}
		}

		public function showPageContent()
		{
			$this->warningNotice();
			?>
			<h4><?php _e('Force Automatic Updates', 'webcraftic-updates-manager'); ?></h4>
			<p><?php _e('This will attempt to force automatic updates. This is useful for debugging.', 'webcraftic-updates-manager'); ?></p>
			<a href="<?php $this->actionUrl('force-plugins-update') ?>" class="button button-default"><?php _e('Force update', 'webcraftic-updates-manager'); ?></a>
		<?php
		}

		public function forcePluginsUpdateAction()
		{
			if( !current_user_can('install_plugins') ) {
				return;
			}

			wp_schedule_single_event(time() + 10, 'wp_update_plugins');
			wp_schedule_single_event(time() + 10, 'wp_version_check');
			wp_schedule_single_event(time() + 10, 'wp_update_themes');
			wp_schedule_single_event(time() + 45, 'wp_maybe_auto_update');

			if( get_option('auto_updater.lock', false) ) {
				update_option('auto_updater.lock', time() - HOUR_IN_SECONDS * 2);
			}

			$this->redirectToAction('index', array('wbcr_force_update' => 1));
		}
	}

	FactoryPages000::register($wbcr_update_services_plugin, 'WbcrUpm_AdvancedPage');

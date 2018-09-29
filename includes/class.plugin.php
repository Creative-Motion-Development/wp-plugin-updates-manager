<?php
	/**
	 * Hide my wp core class
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 19.02.2018, Webcraftic
	 * @version 1.0
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	if( !class_exists('WUPM_Plugin') ) {
		
		if( !class_exists('WUPM_PluginFactory') ) {
			if( defined('LOADING_UPDATES_MANAGER_AS_ADDON') ) {
				class WUPM_PluginFactory {
					
				}
			} else {
				class WUPM_PluginFactory extends Wbcr_Factory000_Plugin {
					
				}
			}
		}
		
		class WUPM_Plugin extends WUPM_PluginFactory {
			
			/**
			 * @var Wbcr_Factory000_Plugin
			 */
			private static $app;
			
			/**
			 * @var bool
			 */
			private $as_addon;
			
			/**
			 * @param string $plugin_path
			 * @param array $data
			 * @throws Exception
			 */
			public function __construct($plugin_path, $data)
			{
				$this->as_addon = isset($data['as_addon']);
				
				if( $this->as_addon ) {
					$plugin_parent = isset($data['plugin_parent'])
						? $data['plugin_parent']
						: null;
					
					if( !($plugin_parent instanceof Wbcr_Factory000_Plugin) ) {
						throw new Exception('An invalid instance of the class was passed.');
					}
					
					self::$app = $plugin_parent;
				} else {
					self::$app = $this;
				}
				
				if( !$this->as_addon ) {
					parent::__construct($plugin_path, $data);
				}

				$this->setTextDomain();
				$this->setModules();
				
				$this->globalScripts();
				
				if( is_admin() ) {
					$this->adminScripts();
				}

				add_action('plugins_loaded', array($this, 'pluginsLoaded'));
			}

			/**
			 * @return Wbcr_Factory000_Plugin
			 */
			public static function app()
			{
				return self::$app;
			}

			// todo: перенести этот медот в фреймворк
			protected function setTextDomain()
			{
				// Localization plugin
				//load_plugin_textdomain('webcraftic-updates-manager', false, dirname(WCL_PLUGIN_BASE) . '/languages/');

				$domain = 'webcraftic-updates-manager';
				$locale = apply_filters('plugin_locale', is_admin()
					? get_user_locale()
					: get_locale(), $domain);
				$mofile = $domain . '-' . $locale . '.mo';

				if( !load_textdomain($domain, WUP_PLUGIN_DIR . '/languages/' . $mofile) ) {
					load_muplugin_textdomain($domain);
				}
			}
			
			protected function setModules()
			{
				if( !$this->as_addon ) {
					self::app()->load(array(
						array('libs/factory/bootstrap', 'factory_bootstrap_000', 'admin'),
						array('libs/factory/forms', 'factory_forms_000', 'admin'),
						array('libs/factory/pages', 'factory_pages_000', 'admin'),
						array('libs/factory/clearfy', 'factory_clearfy_000', 'all'),
						array('libs/factory/notices', 'factory_notices_000', 'admin')
					));
				}
			}
			
			private function registerPages()
			{

				$admin_path = WUP_PLUGIN_DIR . '/admin/pages';

				self::app()->registerPage('WUPM_UpdatesPage', $admin_path . '/updates.php');
				self::app()->registerPage('WUPM_PluginsPage', $admin_path . '/plugins.php');
				self::app()->registerPage('WUPM_ThemesPage', $admin_path . '/themes.php');
				self::app()->registerPage('WUPM_AdvancedPage', $admin_path . '/advanced.php');


				if( !$this->as_addon ) {
					self::app()->registerPage('WbcrUpm_MoreFeaturesPage', $admin_path . '/more-features.php');
				}
			}
			
			private function adminScripts()
			{
                require_once(WUP_PLUGIN_DIR . '/admin/activation.php');

                if( defined('DOING_AJAX') && DOING_AJAX && isset($_REQUEST['action']) ) {
                    if( $_REQUEST['action'] == 'wbcr_upm_change_flag' ) {
                        require(WUP_PLUGIN_DIR . '/admin/ajax/change-flag.php');
                    }
                }

				require_once(WUP_PLUGIN_DIR . '/admin/boot.php');

                $this->initActivation();
				$this->registerPages();
			}
			
			private function globalScripts()
			{
			}

			public function pluginsLoaded()
			{
				require(WUP_PLUGIN_DIR . '/includes/classes/class.configurate-updates.php');
				new WUPM_ConfigUpdates(self::$app);
			}



            protected function initActivation()
            {
                include_once(WUP_PLUGIN_DIR . '/admin/activation.php');
                $this->registerActivation('WUPM_Activation');
            }
		}


	}
<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin class
 *
 * @author        Alex Kovalev <alex.kovalevv@gmail.com>
 * @copyright (c) 19.02.2018, Webcraftic
 */
class WUPM_Plugin extends Wbcr_Factory000_Plugin {

	/**
	 * @var Wbcr_Factory000_Plugin
	 */
	private static $app;

	/**
	 * @since  1.1.0
	 * @var array
	 */
	private $plugin_data;

	/**
	 * WUPM_Plugin constructor.
	 *
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 *
	 * @param string $plugin_path
	 * @param array  $data
	 *
	 * @throws Exception
	 */
	public function __construct( $plugin_path, $data ) {
		parent::__construct( $plugin_path, $data );

		self::$app         = $this;
		$this->plugin_data = $data;

		if ( is_admin() ) {
			$this->admin_scripts();
		}

		add_action( 'plugins_loaded', [ $this, 'plugins_loaded' ] );
	}

	/**
	 * @return Wbcr_Factory000_Plugin
	 */
	public static function app() {
		return self::$app;
	}


	public function plugins_loaded() {
		if ( is_admin() ) {
			$this->register_pages();
		}

		require( WUPM_PLUGIN_DIR . '/includes/classes/class-configurate-updates.php' );
		new WUPM_ConfigUpdates( self::$app );
	}

	private function register_pages() {
		$admin_path = WUPM_PLUGIN_DIR . '/admin/pages';

		self::app()->registerPage( 'WUPM_UpdatesPage', $admin_path . '/class-page-updates.php' );
		self::app()->registerPage( 'WUPM_PluginsPage', $admin_path . '/class-page-plugins.php' );
		self::app()->registerPage( 'WUPM_ThemesPage', $admin_path . '/class-page-themes.php' );
		self::app()->registerPage( 'WUPM_AdvancedPage', $admin_path . '/class-page-advanced.php' );
		self::app()->registerPage( 'WUPM_MoreFeaturesPage', $admin_path . '/class-page-more-features.php' );

		$this->register_adverts_blocks();
	}

	/**
	 * Регистрирует рекламные объявления от студии Webcraftic
	 *
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 * @since  1.1.0
	 */
	private function register_adverts_blocks() {
		global $wdan_adverts;

		$wdan_adverts = new WBCR\Factory_Adverts_000\Base( __FILE__, array_merge( $this->plugin_data, [
			'dashboard_widget' => true, // show dashboard widget (default: false)
			'right_sidebar'    => true, // show adverts sidebar (default: false)
			'notice'           => false, // show notice message (default: false)
		] ) );
	}

	private function admin_scripts() {
		require_once( WUPM_PLUGIN_DIR . '/admin/activation.php' );

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			require_once( WUPM_PLUGIN_DIR . '/admin/ajax/change-flag.php' );
		}

		require_once( WUPM_PLUGIN_DIR . '/admin/boot.php' );

		$this->init_activation();
	}

	protected function init_activation() {
		include_once( WUPM_PLUGIN_DIR . '/admin/activation.php' );
		self::app()->registerActivation( 'WUPM_Activation' );
	}
}

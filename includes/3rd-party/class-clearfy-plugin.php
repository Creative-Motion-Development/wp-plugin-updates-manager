<?php
/**
 * Local Google Analytic
 *
 * @author        Alexander Kovalev <alex.kovalevv@gmail.com>
 * @copyright (c) 2018 Webraftic Ltd
 * @version       1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WGA_Plugin {

	/**
	 * @var WCL_Plugin
	 */
	private static $app;

	/**
	 * Конструктор
	 * Вы
	 *
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 * @throws \Exception
	 */
	public function __construct() {
		if ( ! class_exists( 'WCL_Plugin' ) ) {
			throw new Exception( 'Plugin Clearfy is not installed!' );
		}

		self::$app = WCL_Plugin::app();

		$this->global_scripts();

		if ( is_admin() ) {
			$this->init_activation();
			$this->admin_scripts();
		}
	}

	/**
	 * @return WCL_Plugin
	 */
	public static function app() {
		return self::$app;
	}

	/**
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 * @since  3.0.0
	 */
	private function init_activation() {
		require_once( WGA_PLUGIN_DIR . '/admin/activation.php' );
		self::app()->registerActivation( 'WGA_Activation' );
	}

	/**
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 * @since  3.0.0
	 * @throws \Exception
	 */
	private function admin_scripts() {
		require( WGA_PLUGIN_DIR . '/admin/options.php' );
		require( WGA_PLUGIN_DIR . '/admin/boot.php' );
	}

	/**
	 * @author Alexander Kovalev <alex.kovalevv@gmail.com>
	 * @since  3.0.0
	 */
	private function global_scripts() {
		require( WGA_PLUGIN_DIR . '/includes/classes/class.configurate-ga.php' );
		new WGA_ConfigGACache( self::$app );
	}
}
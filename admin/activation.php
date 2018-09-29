<?php

	/**
	 * Activator for the Update manager
	 * @author Webcraftic <wordpress.webraftic@gmail.com>
	 * @copyright (c) 09.09.2017, Webcraftic
	 * @see Factory000_Activator
	 * @version 1.0
	 */

	// Exit if accessed directly
	if( !defined('ABSPATH') ) {
		exit;
	}

	class WUPM_Activation extends Wbcr_Factory000_Activator {

		/**
		 * Runs activation actions.
		 *
		 * @since 1.0.0
		 */
		public function activate()
		{
            // schedule event for sending updates to email
            if (! wp_next_scheduled ( 'wud_mail_updates' )){
                wp_schedule_event( time(), 'daily', 'wud_mail_updates');
            }
		}

		/**
		 * Runs deactivation actions.
		 *
		 * @since 1.0.0
		 */
		public function deactivate()
		{
            wp_clear_scheduled_hook('wud_mail_updates');
		}


	}

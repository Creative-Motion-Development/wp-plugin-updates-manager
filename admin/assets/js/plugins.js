/**
 * Plugins interface
 * @author Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 10.09.2017, Webcraftic
 * @version 1.0
 */

jQuery(function($) {
	'use strict';

	window.um_add_plugin_icons = function(info) {
		$('#the-list tr[data-plugin]').each(function(k, v) {

			var plugin_path = $(v).data('plugin'),
				slug_parts = plugin_path.split('/'),
				slug = slug_parts[0],
				update_class = '',
				is_auto_update = false,
				is_update_disabled = false;

			if( (info['filters']['disable_auto_updates'] === undefined || !info['filters']['disable_auto_updates'][slug]) && info['auto_update_allowed'] ) {
				is_auto_update = true;
			}
			if( info['updates_disabled'] || (info['filters']['disable_updates'] !== undefined && info['filters']['disable_updates'][slug]) ) {
				is_update_disabled = true;
			}

			if( is_auto_update ) {
				update_class = 'wbcr-upm-purple';
			}
			if( is_update_disabled ) {
				update_class = 'wbcr-upm-red';
			}

			$(v).find('.check-column').addClass('hide-placeholder').append('<span class="dashicons dashicons-update wbcr-upm-plugin-status ' + update_class + '"></span>');
		});
	};

    window.um_add_plugin_actions = function(name, url){
        let btn = '<a href="'+ url +'" class="hide-if-no-js page-title-action">'+ name +'</a>';
        $(btn).insertAfter('#wpbody .page-title-action');

    }
});
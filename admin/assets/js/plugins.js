'use strict';
jQuery(function ($) {
    window.um_add_plugin_icons = function (info) {
        $('#the-list tr[data-plugin]').each(function (k, v) {
            let plugin_path = $(v).data('plugin');
            let slug_parts = plugin_path.split('/');
            let slug = slug_parts[0];

            let update_class = '';

            let is_auto_update = false;
            let is_update_disabled = false;
            if ((info['filters']['disable_auto_updates'] === undefined || !info['filters']['disable_auto_updates'][slug]) && info['auto_update_allowed']) {
                is_auto_update = true;
            }
            if (info['updates_disabled'] || (info['filters']['disable_updates'] !== undefined && info['filters']['disable_updates'][slug])) {
                is_update_disabled = true;
            }

            if (is_auto_update) {
                update_class = 'purple';
            }
            if (is_update_disabled) {
                update_class = 'red';
            }

            $(v).find('.check-column').addClass('hide-placeholder').append('<span class="dashicons dashicons-update um-plugin-status ' + update_class + '"></span>');

        });
    }
});
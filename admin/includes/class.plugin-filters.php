<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

require_once "class.abstract-filters.php";


class WbcrUpm_PluginFilters extends WbcrUpm_AbstractFilters
{

    public function load()
    {
        $updates_mode = $this->plugin->getOption('plugin_updates');

        $this->is_disable_updates = $updates_mode == 'disable_plugin_updates';
        $this->is_auto_updates = $updates_mode == 'enable_plugin_auto_updates';
        $this->is_disable_translation_updates = $this->plugin->getOption('auto_tran_update');

        $default_options = $this->getDefaultOptions();
        $options = $this->plugin->getOption('plugins_update_filters');
        $this->update_filters = array_merge($default_options, (array)$options);
    }

    public function save()
    {
        $this->plugin->updateOption('plugins_update_filters', $this->update_filters);
    }

}
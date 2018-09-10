<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

abstract class WbcrUpm_AbstractFilters
{
    protected $plugin;
    protected $update_filters;
    protected $is_disable_updates;
    protected $is_auto_updates;
    protected $is_disable_translation_updates;


    function __construct(Wbcr_Factory000_Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->load();
    }

    public function disableUpdates($item_slug)
    {
        if (!$this->is_disable_updates) {


            if (!empty($item_slug)) {
                if (isset($this->update_filters['disable_updates'])) {
                    if (!isset($this->update_filters['disable_updates'][$item_slug])) {
                        $this->update_filters['disable_updates'][$item_slug] = true;
                    }
                } else {
                    $this->update_filters['disable_updates'] = array();
                    $this->update_filters['disable_updates'][$item_slug] = true;
                }

                $this->save();
            }
        }
    }

    public function enableUpdates($item_slug)
    {
        if (!$this->is_disable_updates) {
            if (!empty($item_slug)) {
                if (isset($this->update_filters['disable_updates']) && isset($this->update_filters['disable_updates'][$item_slug])) {
                    unset($this->update_filters['disable_updates'][$item_slug]);
                    $this->save();
                }
            }
        }
    }

    public function disableAutoUpdates($item_slug)
    {
        if ($this->is_auto_updates) {
            if (!empty($item_slug)) {
                if (isset($this->update_filters['disable_auto_updates'])) {
                    if (!isset($this->update_filters['disable_auto_updates'][$item_slug])) {
                        $this->update_filters['disable_auto_updates'][$item_slug] = true;
                    }
                } else {
                    $this->update_filters['disable_auto_updates'] = array();
                    $this->update_filters['disable_auto_updates'][$item_slug] = true;
                }
                $this->save();
            }
        }
    }

    public function enableAutoUpdates($item_slug)
    {
        if ($this->is_auto_updates) {
            if (!empty($item_slug)) {
                if (isset($this->update_filters['disable_auto_updates']) && isset($this->update_filters['disable_auto_updates'][$item_slug])) {
                    unset($this->update_filters['disable_auto_updates'][$item_slug]);
                    $this->save();
                }
            }
        }
    }

    public function enableTranslationUpdates($item_slug)
    {
        if (!$this->is_disable_translation_updates) {
            if (!empty($item_slug)) {
                if (isset($this->update_filters['disable_translation_updates']) && isset($this->update_filters['disable_translation_updates'][$item_slug])) {
                    unset($this->update_filters['disable_translation_updates'][$item_slug]);
                    $this->save();
                }
            }
        }
    }

    public function disableTranslationUpdates($item_slug)
    {
        if (!$this->is_disable_translation_updates) {
            if (!empty($item_slug)) {
                if (!isset($this->update_filters['disable_translation_updates'])) {
                    $this->update_filters['disable_translation_updates'] = array();
                }
                $this->update_filters['disable_translation_updates'][$item_slug] = true;
                $this->save();
            }
        }
    }

    protected function getDefaultOptions(){
        return array(
            'disable_updates' => array(),
            'disable_auto_updates' => array(),
            'disable_translation_updates' => array(),
            'disable_display' => array(),
        );

    }


    abstract public function load();

    abstract public function save();


}
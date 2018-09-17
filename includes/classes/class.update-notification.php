<?php

/**
 * This class configures the parameters seo
 * @author Webcraftic <wordpress.webraftic@gmail.com>
 * @copyright (c) 2017 Webraftic Ltd
 * @version 1.0
 */

// Exit if accessed directly
if( !defined('ABSPATH') ) {
    exit;
}

class WbcrUpm_UpdateNotification {
    static public function check_updates_mail()
    {
        require_once $_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/plugin.php'; //require for get_plugins()
        $notify_update_available = WUP_Plugin::app()->getOption('notify_update_available');
        $notify_updated = WUP_Plugin::app()->getOption('notify_updated');

        if ($notify_update_available) {
            // check available updates
            self::list_theme_updates();
            self::list_plugin_updates();
        }
        // check completed updates
        if ($notify_updated){
            self::plugin_updated();
        }
    }



    static private function get_emails()
    {
        $emailArray = array();

        $notify_email = WUP_Plugin::app()->getOption('notify_email');
        if ($notify_email == '') {
            array_push($emailArray, get_option('admin_email'));
        } else {
            $list = explode(", ", $notify_email);
            foreach ($list as $email) {
                array_push($emailArray, $email);
            }
        }
        return $emailArray;

    }



    static private function pending_message($single, $plural)
    {

        return sprintf(esc_html__(
            'Available updates for follow plugins on your site %2$s.', 'webcraftic-updates-manager'
        ), $single, get_site_url(), $plural);

    }


    static private function updated_message($type, $updatedList)
    {

        $text = sprintf(esc_html__(
            'The following %1$s have been updated:', 'webcraftic-updates-manager'
        ), $type, get_site_url());

        $text .= $updatedList;

        return $text;

    }




    static private function list_theme_updates()
    {

        $update_mode = WUP_Plugin::app()->getOption('theme_updates');
        $auto_update_themes = 'enable_theme_auto_updates' == $update_mode;

        if (!$auto_update_themes) {

            require_once ABSPATH . '/wp-admin/includes/update.php';
            $themes = get_theme_updates();

            if (!empty($themes)) {

                $subject = '[' . get_bloginfo('name') . '] ' . __('Theme update available.', 'webcraftic-updates-manager');
                $type = __('theme', 'webcraftic-updates-manager');
                $type_plural = __('themes', 'webcraftic-updates-manager');
                $message = self::pending_message($type, $type_plural);

                foreach (self::get_emails() as $key => $email) {
                    wp_mail($email, $subject, $message, $headers);
                }
            }

        }


    }


    static private function list_plugin_updates()
    {
        $update_mode = WUP_Plugin::app()->getOption('plugin_updates');
        $auto_update_plugins = 'enable_plugin_auto_updates' == $update_mode;

        if (!$auto_update_plugins) {

            require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
            $plugins = get_plugin_updates();

            if (!empty($plugins)) {

                $subject = '[' . get_bloginfo('name') . '] ' . __('Plugin update available.', 'webcraftic-updates-manager');
                $type = __('plugin', 'webcraftic-updates-manager');
                $type_plural = __('plugins', 'webcraftic-updates-manager');
                $message = self::pending_message($type, $type_plural);

                foreach (self::get_emails() as $key => $email) {
                    wp_mail($email, $subject, $message, $headers);
                }
            }

        }

    }





    static private function plugin_updated()
    {

        // Create arrays
        $pluginNames = array();
        $pluginDates = array();
        $pluginVersion = array();
        $themeNames = array();
        $themeDates = array();

        // Where to look for plugins
        $plugdir = plugin_dir_path(WUP_PLUGIN_DIR);
        $allPlugins = get_plugins();

        // Where to look for themes
        $themedir = get_theme_root();
        $allThemes = wp_get_themes();

        // Loop trough all plugins
        foreach ($allPlugins as $key => $value) {

            // Get plugin data
            $fullPath = $plugdir . '/' . $key;
            $getFile = $path_parts = pathinfo($fullPath);
            $pluginData = get_plugin_data($fullPath);

            // Get last update date
            $fileDate = date('YmdHi', filemtime($fullPath));
            $mailSched = wp_get_schedule('wud_mail_updates');

            if ($mailSched == 'hourly') {
                $lastday = date('YmdHi', strtotime('-1 hour'));
            } elseif ($mailSched == 'twicedaily') {
                $lastday = date('YmdHi', strtotime('-12 hours'));
            } elseif ($mailSched == 'daily') {
                $lastday = date('YmdHi', strtotime('-1 day'));
            }

            if ($fileDate >= $lastday) {

                // Get plugin name
                foreach ($pluginData as $dataKey => $dataValue) {
                    if ($dataKey == 'Name') {
                        array_push($pluginNames, $dataValue);
                    }
                    if ($dataKey == 'Version') {
                        array_push($pluginVersion, $dataValue);
                    }
                }

                array_push($pluginDates, $fileDate);
            }

        }

        foreach ($allThemes as $key => $value) {

            $fullPath = $themedir . '/' . $key;
            $getFile = $path_parts = pathinfo($fullPath);

            $dateFormat = get_option('date_format');
            $fileDate = date('YmdHi', filemtime($fullPath));
            $mailSched = wp_get_schedule('wud_mail_updates');

            if ($mailSched == 'hourly') {
                $lastday = date('YmdHi', strtotime('-1 hour'));
            } elseif ($mailSched == 'twicedaily') {
                $lastday = date('YmdHi', strtotime('-12 hours'));
            } elseif ($mailSched == 'daily') {
                $lastday = date('YmdHi', strtotime('-1 day'));
            }

            if ($fileDate >= $lastday) {
                array_push($themeNames, $path_parts['filename']);
                array_push($themeDates, $fileDate);
            }


        }

        $totalNumP = 0;
        $totalNumT = 0;
        $updatedListP = '';
        $updatedListT = '';

        foreach ($pluginDates as $key => $value) {

            $updatedListP .= "- " . $pluginNames[$key] . " to version " . $pluginVersion[$key] . "\n";
            $totalNumP++;

        }
        foreach ($themeNames as $key => $value) {

            $updatedListT .= "- " . $themeNames[$key] . "\n";
            $totalNumT++;

        }


        // If plugins have been updated, send email
        if ($totalNumP > 0) {

            $subject = '[' . get_bloginfo('name') . '] ' . __('Plugins have been updated.', 'webcraftic-updates-manager');
            $type = __('plugins', 'webcraftic-updates-manager');
            $message = self::updated_message($type, "\n" . $updatedListP);

            foreach (self::get_emails() as $key => $email) {
                wp_mail($email, $subject, $message, $headers);
            }

        }

        // If themes have been updated, send email
        if ($totalNumT > 0) {

            $subject = '[' . get_bloginfo('name') . '] ' . __('Themes have been updated.', 'webcraftic-updates-manager');
            $type = __('themes', 'webcraftic-updates-manager');
            $message = self::updated_message($type, "\n" . $updatedListT);

            foreach (self::get_emails() as $key => $email) {
                wp_mail($email, $subject, $message, $headers);
            }

        }

    }



}
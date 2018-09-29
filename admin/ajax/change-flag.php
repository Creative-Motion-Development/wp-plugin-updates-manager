<?php

// Exit if accessed directly
if( !defined('ABSPATH') ) {
    exit;
}


/**
 * ajax action for switch option
 */
function wbcr_upm_change_flag(){
    $is_theme = false;
    $app = WUPM_Plugin::app();
    $slug = $app->request->post('theme');
    if(!empty($slug)){
        $is_theme = true;
    }else{
        $slug = $app->request->post('plugin');
    }
    $slug = filter_var($slug, FILTER_SANITIZE_STRING);

    $flag = $app->request->post('flag');
    $flag = filter_var($flag, FILTER_SANITIZE_STRING);
    $new_value = (bool) $app->request->post('value');
    if(empty($slug) or empty($flag)){
        echo json_encode(array('error'=> array('empty arguments')));
        exit();
    }

    if($is_theme){
        $plugin_filters = new WUPM_ThemeFilters($app);
    }else{
        $plugin_filters = new WUPM_PluginFilters($app);
    }


    $method = (($new_value)? 'disable': 'enable') . $flag;
    if(!method_exists($plugin_filters, $method)){
        echo json_encode(
            array(
                'error'=> array(sprintf('Method %s not found', $method))
            ));
        exit();
    }

    $plugin_filters->$method($slug);

    $plugin_filters->save();
    echo json_encode(array());
    exit();

}


add_action('wp_ajax_wbcr_upm_change_flag', 'wbcr_upm_change_flag');
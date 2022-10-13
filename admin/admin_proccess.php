<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


add_action('admin_menu', function (){
    global $iqaPageHook;

    $iqaPageHook = add_menu_page(
        __('Main Settings', 'socialite_lan'),
        __('Main Settings', 'socialite_lan'),
        'administrator',
        'wp_iqa',
        function(){include(IQA_ADMIN_VIEW . 'settings.php');},
        'dashicons-post-status'
    );


//    add_submenu_page(
//        'wpsoc',
//        __('Agencies', 'socialite_lan'),
//        __('Agencies', 'socialite_lan'),
//        'edit_posts',
//        'rad_agencies',
//        function(){include(RAD_ADMIN_VIEW . 'agencies_settings.php');}
//    );
});



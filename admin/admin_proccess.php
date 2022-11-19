<?php defined('ABSPATH') or die('No script kiddies please!');


add_action('admin_menu', function () {
    global $iqaPageHook;

    $iqaPageHook = add_menu_page(
        __('Intel Q/A', 'intl_qa_lan'),
        __('Intel Q/A', 'intl_qa_lan'),
        'administrator',
        'iqa',
        function () {
            include(IQA_ADMIN_VIEW . 'settings.php');
        },
        'dashicons-lightbulb'
    );

    add_submenu_page(
        null,
        __('Agencies', 'intl_qa_lan'),
        __('Agencies', 'socialite_lan'),
        'administrator',
        'iqa_editQA',
        function () {
            include(IQA_ADMIN_VIEW . 'update_qa.php');
        }
    );
});

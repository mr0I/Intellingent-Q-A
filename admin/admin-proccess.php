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
        __('Edit QA', 'intl_qa_lan'),
        __('Edit QA', 'intl_qa_lan'),
        'administrator',
        'iqa_editQA',
        function () {
            include(IQA_ADMIN_VIEW . 'qa-update.php');
        }
    );

    add_submenu_page(
        'iqa',
        __('User Reports', 'intl_qa_lan'),
        __('User Reports', 'intl_qa_lan'),
        'administrator',
        'iqa-user-reports',
        function () {
            include(IQA_ADMIN_VIEW . 'user-reports.php');
        }
    );
});

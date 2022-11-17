<?php

/**
 * Plugin Name: Intellingent Q/A
 * Plugin URI: http://localhost
 * Description:
 * Version: 1.0.0
 * Author: ZeroOne
 * Author URI: http://localhost
 * Text Domain: intl_qa_lan
 * Domain Path: /languages
 */


defined('ABSPATH') or die('No script kiddies please!');

define('IQA_ROOT_DIR', plugin_dir_path(__FILE__));
define('IQA_ROOT_URL', plugin_dir_url(__FILE__));
define('IQA_CSS', plugin_dir_url(__FILE__) . 'site/static/css/');
define('IQA_JS', plugin_dir_url(__FILE__) . 'site/static/js/');
define('IQA_INC', IQA_ROOT_DIR . 'inc/');
define('IQA_ADMIN', IQA_ROOT_DIR . 'admin/');
define('IQA_ADMIN_VIEW', IQA_ROOT_DIR . 'admin/views/');
define('IQA_ADMIN_CSS', plugin_dir_url(__FILE__) . 'admin/static/css/');
define('IQA_ADMIN_JS', plugin_dir_url(__FILE__) . 'admin/static/js/');
define('IQA_ADMIN_LIBS', plugin_dir_url(__FILE__) . 'admin/static/libs/');
define('QA_TABLE', 'qa');
define('REPORT_TABLE', 'report_sqs');

$name = $_POST['sadda'];

add_action('plugins_loaded', function () {
    load_plugin_textdomain('intl_qa_lan', false, basename(IQA_ROOT_DIR) . '/languages/');
});

/*
 * load css & js
 */
add_action('wp_enqueue_scripts', function () {
    //    wp_enqueue_script('popper', PL_JS.'popper.min.js' , array('jquery', 'media-upload'));
    //    wp_enqueue_script('bootstrap', PL_JS.'bootstrap.min.js' , array('jquery'));
    wp_enqueue_script('main-script', IQA_JS . 'scripts.js', array('jquery'), '1.0.0');
    wp_localize_script('main-script', 'IQA_Ajax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('mnhUciSW!Zk/oBB'),
        'NO_RESULT' => __('No Result Found', 'intl_qa_lan'),
        'SUCCESS_MESSAGE' => __('Successful Operation', 'intl_qa_lan'),
        'FAILURE_MESSAGE' => __('Error In Operation ', 'intl_qa_lan'),
        'REQUEST_TIMEOUT' => 30000
    ));
    wp_enqueue_media();
    wp_enqueue_style('main-styles', IQA_CSS . 'styles.css', '1.0.0');
});
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_script('tagify', IQA_ADMIN_LIBS . 'jQuery.tagify.min.js', array('jquery'), '4.8.1');
    wp_enqueue_script('admin-script', IQA_ADMIN_JS . 'admin-scripts.js', '1.0.0');
    wp_localize_script('admin-script', 'IQA_ADMIN_Ajax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'security' => wp_create_nonce('OwpCojMcdGJ-k-o'),
        'SAVE_TEXT' => __('Save', 'intl_qa_lan'),
        'UPDATE_TEXT' => __('Update', 'intl_qa_lan'),
        'SAVING_TEXT' => __('Saving...', 'intl_qa_lan'),
        'UPDATING_TEXT' => __('Updating...', 'intl_qa_lan'),
        'CONFIRM_TEXT' => __('Are you sure you want to delete this item?', 'intl_qa_lan'),
        'FORBIDDEN_TEXT' => __('Forbidden', 'intl_qa_lan'),
        'FAILURE_MESSAGE' => __('Error In Operation ', 'intl_qa_lan'),
        'SUCCESS_MESSAGE' => __('Successful Operation', 'intl_qa_lan'),
        'REQUEST_TIMEOUT' => 30000
    ));
    wp_enqueue_style('tagify-styles', IQA_ADMIN_LIBS . 'tagify.min.css', '1.0.0');
    wp_enqueue_style('admin-styles', IQA_ADMIN_CSS . 'admin-styles.css', '1.0.0');
});

/*
 * init & includes
 */
include(IQA_ROOT_DIR . 'base_functions.php');
register_activation_hook(__FILE__, 'IQA_activate_function');
register_deactivation_hook(__FILE__, 'IQA_deactivate_function');
include(IQA_INC . 'shortcodes.php');
if (is_admin()) {
    include(IQA_ADMIN . 'admin_proccess.php');
    include(IQA_ADMIN . 'ajax_requests.php');
}

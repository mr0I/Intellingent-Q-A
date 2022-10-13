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


defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define('IQA_ROOT_DIR', plugin_dir_path(__FILE__) );
//define('IQA_ADMIN', IQA_ROOT_DIR . 'admin/');
//define('IQA_ADMIN_VIEW', IQA_ROOT_DIR . 'admin/view/');
//define('IQA_INCS', IQA_ROOT_DIR . 'inc/');
define('IQA_CSS', plugin_dir_url(__FILE__) . 'static/css/');
//define('IQA_ADMIN_CSS', plugin_dir_url(__FILE__) . 'admin/css/');
define('IQA_JS', plugin_dir_url(__FILE__) . 'static/js/');
//define('IQA_ADMIN_JS', plugin_dir_url(__FILE__) . 'admin/js/');
//define('IQA_ASSETS', plugin_dir_url(__FILE__) . 'assets/');

add_action('plugins_loaded', function(){
    load_plugin_textdomain('intl_qa_lan', false, basename(IQA_ROOT_DIR) . '/languages/');
});

/*
 * load css & js
 */
add_action( 'wp_enqueue_scripts', function(){
//    wp_enqueue_script('popper', PL_JS.'popper.min.js' , array('jquery', 'media-upload'));
//    wp_enqueue_script('bootstrap', PL_JS.'bootstrap.min.js' , array('jquery'));
    wp_enqueue_script('main-script', IQA_JS.'scripts.js' , '1.0.0');
    wp_localize_script( 'main-script', 'IQA_Ajax', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'security' => wp_create_nonce( 'OwpCojMcdGJ-k-o' ),
//        'login_frm_submit_btn_txt' => __('Login', 'radshid_lan')
    ));
    wp_enqueue_media();
//    wp_enqueue_style( 'bootstrap', PL_CSS . 'bootstrap.min.css');
    wp_enqueue_style( 'main-styles', IQA_CSS . 'styles.css','1.0.0');
});

/*
 * inits
 */
include(IQA_ROOT_DIR. 'base_functions.php' );
register_activation_hook( __FILE__, 'IQA_activate_function');
register_deactivation_hook( __FILE__, 'IQA_deactivate_function');
if ( is_admin() ){
    include(PL_ADMIN . 'admin_proccess.php');
    include(PL_ADMIN . 'ajax_requests.php');
}

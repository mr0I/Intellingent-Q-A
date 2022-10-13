<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function IQA_activate_function(){
    ob_start();
    //register_uninstall_hook(__FILE__, 'my_plugin_uninstall');


    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    //	$agencies_orders_table = $wpdb->prefix . AGENCIES_ORDERS_TABLE;
    //
    //	$createTableQuery1 =
    //		"
    //		CREATE TABLE IF NOT EXISTS `{$agencies_orders_table}` (
    //		  `id` int(11) NOT NULL AUTO_INCREMENT,
    //		  `user_id` int(11) NOT NULL,
    //		  `order_detail` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
    //		  `order_number` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
    //		  `total_price` float(10,0) COLLATE utf8_unicode_ci NOT NULL,
    //		  `approved` tinyint(1) NOT NULL,
    //		  `date` datetime(6) DEFAULT NULL,
    //		  PRIMARY KEY (`id`)
    //		) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
    //		";
    //
    //
    //	dbDelta($createTableQuery1);
    flush_rewrite_rules();
}

function IQA_deactivate_function(){
    flush_rewrite_rules();
}



<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function IQA_activate_function(){
    ob_start();
    global $wpdb;
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $qaTable = $wpdb->prefix . QA_TABLE;
    $createQaTable = "
                    CREATE TABLE IF NOT EXISTS `{$qaTable}` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `question` text NOT NULL,
                  `answer` text NOT NULL,
                  `keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`keywords`)),
                  `created_at` timestamp NULL DEFAULT NULL,
                  `updated_at` timestamp NULL DEFAULT NULL,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 ";

    dbDelta($createQaTable);

    register_uninstall_hook(__FILE__, 'iqa_uninstall');
    flush_rewrite_rules();
}

function IQA_deactivate_function(){
    flush_rewrite_rules();
}

function iqa_uninstall()
{
    //global $wpdb;
//$table1 = $wpdb->prefix . AGENCIES_ORDERS_TABLE;
//$wpdb->query( "DROP TABLE IF EXISTS {$table1}" );
//
//delete_option('RADtools_random_posts_cat');
}

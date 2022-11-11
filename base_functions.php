<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function IQA_activate_function(){
    ob_start();
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    global $wpdb;
    $qaTable = $wpdb->prefix . QA_TABLE;
    $createQaTable = "
                    CREATE TABLE IF NOT EXISTS `{$qaTable}` (
                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                  `question` varchar(255) NOT NULL,
                  `answer` text NOT NULL,
                  `keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`keywords`)),
                  `views` int(10) unsigned DEFAULT 0,
                  `enabled` tinyint(4) DEFAULT 1,
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
    if (get_option('should_delete_iqa_db')) {
        global $wpdb;
        $table = $wpdb->prefix . QA_TABLE;
        $wpdb->query( "DROP TABLE IF EXISTS ${table}" );
    }
    delete_option('iqa_stopwords');
}

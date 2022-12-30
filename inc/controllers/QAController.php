<?php defined('ABSPATH') or die('No script kiddies please!');


function getAllQAs()
{
    global $wpdb;
    $qaTable = $wpdb->prefix . QA_TABLE;
    $qanswes = $wpdb->get_results(" SELECT * FROM ${qaTable} ORDER BY updated_at DESC  ");

    $result = new stdClass();
    $result->data = $qanswes;
    return $result;
}

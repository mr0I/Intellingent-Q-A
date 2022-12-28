<?php defined('ABSPATH') or die('No script kiddies please!');


function getReports($offset, $limit)
{
    global $wpdb;
    $reportsTable = $wpdb->prefix . REPORT_TABLE;

    $reportsCount = $wpdb->get_var(" SELECT COUNT(id) FROM ${reportsTable} ");
    $reports =  $wpdb->get_results(" SELECT * FROM ${reportsTable} ORDER BY updated_at DESC LIMIT ${offset},${limit}");

    $result = new stdClass();
    $result->metadata = new stdClass();
    $result->data = $reports;
    $result->metadata->last_page = ceil(intval($reportsCount) / $limit);
    $result->metadata->current_page = ($offset / $limit) + 1;

    return $result;
}

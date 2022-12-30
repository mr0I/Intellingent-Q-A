<?php defined('ABSPATH') or die('No script kiddies please!');


function getAllQAs()
{
    global $wpdb;
    $qaTable = $wpdb->prefix . QA_TABLE;
    $qanswes = $wpdb->get_results(" SELECT * FROM ${qaTable} ORDER BY updated_at DESC  ");
    // $qanswesCount = $wpdb->get_var(" SELECT COUNT(id) FROM ${qaTable} ");
    // $reports = $wpdb->get_results(" SELECT * FROM ${reportsTable} ORDER BY updated_at DESC ");

    // $paginatedQAnswers = new stdClass();
    // $paginatedQAnswers->data = $qanswes;
    // $paginatedQAnswers->paginate = [
    //     'new_offset' => $offset,
    //     'current_page' => ($offset / $limit) + 1,
    //     'last_page' => ceil(intval($qanswesCount) / $limit)
    // ];

    $result = new stdClass();
    $result->data = $qanswes;

    return $result;
}

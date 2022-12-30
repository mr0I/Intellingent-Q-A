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

function getMostPopularQuestions($count = 5)
{
    global $wpdb;
    $table = $wpdb->prefix . QA_TABLE;

    return $wpdb->get_results(
        $wpdb->prepare(
            "SELECT question,answer FROM ${table} WHERE enabled=%d 
                        ORDER BY views DESC, updated_at DESC LIMIT 0,${count}",
            array(1)
        )
    );
}

// function countSumViews()
// {
//     global $wpdb;
//     $table = $wpdb->prefix . QA_TABLE;

//     return $wpdb->get_var(
//         $wpdb->prepare(
//             "SELECT SUM(views) FROM ${table} WHERE enabled=%d",
//             array(1)
//         )
//     );
// }
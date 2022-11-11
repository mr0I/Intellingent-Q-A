<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function arrayUnique($arr, $flag=SORT_NUMERIC){
    return array_unique($arr, $flag);
}

function arrayFlatten($array) {
    if (!is_array($array)) {
        return false;
    }
    $result = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, arrayFlatten($value));
        } else {
            $result = array_merge($result, array($key => $value));
        }
    }
    return $result;
}

function sendResponse(...$res_data){
    wp_send_json(...$res_data);
    exit();
}

function countSumViews(){
    global $wpdb;
    $table = $wpdb->prefix . QA_TABLE;

    return $wpdb->get_var(
        $wpdb->prepare("SELECT SUM(views) FROM ${table} WHERE enabled=%d",
            array(1))
    );
}

function getMostPopularQuestions($count=5){
    global $wpdb;
    $table = $wpdb->prefix . QA_TABLE;

    return $wpdb->get_results(
        $wpdb->prepare("SELECT question,answer FROM ${table} WHERE enabled=%d 
                        ORDER BY views DESC, updated_at DESC LIMIT 0,${count}",
            array(1))
    );
}
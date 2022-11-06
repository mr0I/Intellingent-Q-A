<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once IQA_INC . 'helpers.php';


function addQa_callback(){

    if ( !wp_verify_nonce($_POST['nonce'], 'add_qa') || !check_ajax_referer( 'OwpCojMcdGJ-k-o', 'security' )) {
        wp_send_json_error('Forbidden',403);
        exit();
    }

    global $wpdb;
    $question = sanitize_text_field($_POST['question']);
    $answer = sanitize_text_field($_POST['answer']);
    $keywords = sanitize_text_field(stripslashes($_POST['keywords']));
    $qaTable = $wpdb->prefix . QA_TABLE;
    $insert = $wpdb->insert( $qaTable, [
        'question' => $question,
        'answer' => $answer,
        'keywords' => $keywords,
        'created_at' => current_time('mysql'),
        'updated_at' => current_time('mysql'),
    ], [ '%s', '%s', '%s', '%s', '%s' ]);

    if (!$insert) {
        wp_send_json(['success' => false]);
        exit();
    }

    wp_send_json(['success' => true]);
    exit();

}
add_action( 'wp_ajax_addQa', 'addQa_callback' );
add_action( 'wp_ajax_nopriv_addQa', 'addQa_callback' );

function searchQa_callback(){
    if ( !wp_verify_nonce($_POST['nonce'], 'search_qa') || !check_ajax_referer( 'mnhUciSW!Zk/oBB', 'security' )) {
        wp_send_json_error('Forbidden',403);
        exit();
    }

    global $wpdb;
    $table = $wpdb->prefix . QA_TABLE;
    $query = $wpdb->prepare("SELECT * FROM $table WHERE enabled=%s", array(true));
    $rows = $wpdb->get_results( $query );
    $tokenizeInput = explode(' ', $_POST['input']);

    if (count($rows) === 0){
        wp_send_json(['success' => false]);
        exit();
    }

    $i = 0;
    $score = 0;
    $eligibleRows = [];
    while ($i < count($rows)) {
        $keywords = json_decode($rows[$i]->keywords);
        $flattenKeywords = arrayFlatten(array_map( function($item){
            return explode(' ',$item);
        }, $keywords));
        foreach ($tokenizeInput as $item){
            foreach ($flattenKeywords as $keyword){
                if (preg_match("/$keyword/i", $item)) {
                    $score++;
                }
            }
        }
        if ($score > 0) {
            array_push($eligibleRows, [
                'id' => $rows[$i]->id,
                'primary_score' => $score + ($score/sizeof($flattenKeywords)),
                'answer' => $rows[$i]->answer
            ]);
        }
        $i++;
        $score = 0;
    }

    wp_send_json([
        'success' => true,
        'result'=> $eligibleRows
    ]);
    exit();
}
add_action( 'wp_ajax_searchQa', 'searchQa_callback' );
add_action( 'wp_ajax_nopriv_searchQa', 'searchQa_callback' );

function addStopWord_callback(){
    if ( !wp_verify_nonce($_POST['nonce'], 'stopwords') || !check_ajax_referer( 'OwpCojMcdGJ-k-o', 'security' )) {
        wp_send_json_error('Forbidden',403);
        exit();
    }

    $stopwords = sanitize_text_field(stripslashes($_POST['stopwords']));
    $res = update_option('iqa_stopwords', json_decode($stopwords));

    if (!$res){
        wp_send_json([
            'success' => false
        ]);
        exit();
    }
    wp_send_json([
        'success' => true,
        'result'=> $stopwords
    ]);
    exit();
}
add_action( 'wp_ajax_addStopWord', 'addStopWord_callback' );
add_action( 'wp_ajax_nopriv_addStopWord', 'addStopWord_callback' );


function incrementViewsCount_callback(){
    if ( !wp_verify_nonce($_POST['nonce'], 'search_qa') || !check_ajax_referer( 'mnhUciSW!Zk/oBB', 'security' )) {
        wp_send_json_error('Forbidden',403);
        exit();
    }

    global $wpdb;
    $table = $wpdb->prefix . QA_TABLE;
    $sortedRows = json_decode(stripslashes($_POST['rows']), false);
    $i = 0; $j = 0;
    foreach ($sortedRows as $row){
        if ($i < 2) {
            $res = $wpdb->query(
                $wpdb->prepare("UPDATE ${table} SET views=views+1 WHERE id=%d",
                    array($row->id))
            );
            if ($res) $j++;
        }
        $i++;
    }

//    if ($i !== $j) {
//        // log to file
//    }

    wp_send_json([
        'success' => true
    ]);
    exit();
}
add_action( 'wp_ajax_incrementViewsCount', 'incrementViewsCount_callback' );
add_action( 'wp_ajax_nopriv_incrementViewsCount', 'incrementViewsCount_callback' );


function SetResultNum_callback(){
    if ( !wp_verify_nonce($_POST['nonce'], 'results_num') || !check_ajax_referer( 'OwpCojMcdGJ-k-o', 'security' )) {
        wp_send_json_error('Forbidden',403);
        exit();
    }

    $num = absint($_POST['results_num']);
    $res = update_option('results_num', $num);

    if (!$res){
        wp_send_json([
            'success' => false
        ]);
        exit();
    }
    wp_send_json([
        'success' => true
    ]);
    exit();
}
add_action( 'wp_ajax_SetResultNum', 'SetResultNum_callback' );
add_action( 'wp_ajax_nopriv_SetResultNum', 'SetResultNum_callback' );


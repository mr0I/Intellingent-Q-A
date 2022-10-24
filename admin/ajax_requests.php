<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


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

    $tokenizeInput = explode(' ', $_POST['input']);

    wp_send_json([
        'success' => true,
        'input'=> $tokenizeInput,
    ]);
    exit();

}
add_action( 'wp_ajax_searchQa', 'searchQa_callback' );
add_action( 'wp_ajax_nopriv_searchQa', 'searchQa_callback' );


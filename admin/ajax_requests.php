<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function addQa_callback(){

    if ( !wp_verify_nonce($_POST['nonce'], 'add_qa') || !check_ajax_referer( 'OwpCojMcdGJ-k-o', 'security' )) {
        wp_send_json_error('Forbidden',403);
    }

    $question = sanitize_text_field($_POST['question']);
    $answer = sanitize_text_field($_POST['answer']);
    $keywords = sanitize_text_field(stripslashes($_POST['keywords']));
    global $wpdb;
    $qaTable = $wpdb->prefix . QA_TABLE;
    $insert = $wpdb->insert( $qaTable, [
        'question' => $question,
        'answer' => $answer,
        'keywords' => $keywords,
        'created_at' => current_time('mysql'),
        'updated_at' => current_time('mysql'),
    ], [ '%s', '%s', '%s', '%s', '%s' ]);

    if (!$insert) {
        $response = ['success' => false];
        echo json_encode($response);
        exit();
    }

    $response = ['success' => true];
    echo json_encode($response);
    exit();

}
add_action( 'wp_ajax_addQa', 'addQa_callback' );
add_action( 'wp_ajax_nopriv_addQa', 'addQa_callback' );
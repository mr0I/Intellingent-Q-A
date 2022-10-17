<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function addQa_callback(){

    if ( !wp_verify_nonce($_POST['nonce'], 'add_qa') || !check_ajax_referer( 'OwpCojMcdGJ-k-o', 'security' )) {
        wp_send_json_error('Forbidden',403);
    }

    $response = [
        'success' => true,
        'message' => [
            'q' => sanitize_text_field($_POST['question']),
            'a' => sanitize_text_field($_POST['answer'])
        ]
    ];
    echo json_encode($response);
    exit();
}
add_action( 'wp_ajax_addQa', 'addQa_callback' );
add_action( 'wp_ajax_nopriv_addQa', 'addQa_callback' );
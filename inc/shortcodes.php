<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


add_action('init', function () {
    add_shortcode('search_qa' , 'searchQaFunc');
});

function searchQaFunc($atts, $content = null){
    ob_start();
    include(IQA_ROOT_DIR . './site/views/search-qa.php');
    return do_shortcode(ob_get_clean());
}

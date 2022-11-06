<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

echo "<link rel='stylesheet' id='front-pico-css'  href='".IQA_ROOT_URL."static/css/pico.min.css?ver=6.0.1' type='text/css' media='all' />";
require_once IQA_INC . 'helpers.php';
?>


<section id="qa_search_page" data-theme="light">
    <div class="container">

        <div class="row">
            <form action="" method="get" onsubmit="searchQA(event)">
                <div class="form-group">
                    <input type="text" name="search"
                           placeholder="<?= __('Input your question...', 'intl_qa_lan') ?>">
                    <i class="fa fa-times"
                       onclick="document.querySelector('input[name=search]').value = '';
                       document.querySelector('input[name=search]').focus();">
                    </i>
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>
                <input type="hidden" name="nonce" value="<?= wp_create_nonce('search_qa') ?>">
            </form>
        </div>

        <div class="alert alert-outline">
            <span class="loading-dots"></span>
            <p><?= __('Processing founded results', 'intl_qa_lan') ?></p>
        </div>

        <div class="row">
            <div class="answers-list"><ul></ul></div>
        </div>

        <div class="row">
            <div class="most-popular-questions">
                <h2><?= __('The most popular questions', 'intl_qa_lan') ?></h2>
                <details>
                    <summary>dadad</summary>
                    <p>sdsadad</p>
                </details>
                <details>
                    <summary>dadad</summary>
                    <p>sdsadad</p>
                </details>
            </div>
        </div>
    </div>
</section>


<?php
    $stopWords = json_encode(get_option('iqa_stopwords'));
    $resultsNum = get_option('results_num');
?>
<script type="application/ld+json" id="stop_words_array">
    {
        "stop_words" : <?= $stopWords; ?>,
        "results_num" : <?= $resultsNum; ?>
    }
</script>
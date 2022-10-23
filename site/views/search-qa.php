<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?>


<section id="qa_search_page" data-theme="light">
    <div class="container">

        <div class="row">
            <form action="" method="get" onsubmit="searchQA(event)">
                <div class="form-group">
                    <input type="text" name="search"
                           placeholder="<?= __('Input your question...', 'intl_qa_lan') ?>">
                    <i class="fa fa-times"
                       onclick="document.querySelector('input[name=search]').value = ''"></i>
                    <button type="submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
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

        <div class="row">
            <div class="answers">
                <ul>
                    <li>
                        <span>1</span>
                        <span>answer1</span>
                    </li>
                    <li>
                        <span>2</span>
                        <span>answer2</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</section>
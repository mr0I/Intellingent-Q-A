<?php defined('ABSPATH') or die('No script kiddies please!'); ?>

<?php
global $wpdb;
$reportsTable = $wpdb->prefix . REPORT_TABLE;
$qaTable = $wpdb->prefix . QA_TABLE;
$reports = $wpdb->get_results("SELECT * FROM ${reportsTable} ORDER BY updated_at DESC ");
$qanswes = $wpdb->get_results("SELECT * FROM ${qaTable} ORDER BY updated_at DESC ");
?>

<div class="menu-wrapper">
    <input class="radio" id="r_one" name="group" type="radio" checked>
    <input class="radio" id="r_two" name="group" type="radio">
    <input class="radio" id="r_three" name="group" type="radio">

    <div class="tabs">
        <label class="tab" id="tab_one" for="r_one"><?= __('General Settings', 'intl_qa_lan') ?></label>
        <label class="tab" id="tab_two" for="r_two"><?= __('Q/A List', 'intl_qa_lan') ?></label>
        <label class="tab" id="tab_three" for="r_three"><?= __('Users Reports', 'intl_qa_lan') ?></label>
    </div>

    <div class="panels">

        <div class="panel" id="panel_one">
            <div class="panel-title"><?= __('Add Question and Answer', 'intl_qa_lan') ?></div>
            <form method="post" action="" name="add_qa_frm" id="add_qa_frm">
                <table class="form-table" id="top_menu_links_tbl" role="presentation">
                    <tbody>
                        <tr>
                            <td><input type="text" name="tags" placeholder="sddad..."></td>
                        </tr>
                        <tr>
                            <td>
                                <textarea class="left-align" name="question" placeholder="<?= __('Enter Your Question...', 'intl_qa_lan') ?>" rows="3"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php wp_editor(__('Enter Your Answer...', 'intl_qa_lan'), 'id1', array(
                                    'textarea_rows' => 2,
                                    'textarea_name' => 'answer'
                                )) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="hidden" name="nonce" value="<?= wp_create_nonce('add_qa') ?>">
                    <input type="submit" class="button button-primary pull-left" name="submit" value="<?= __('Save', 'intl_qa_lan') ?>">
                </p>
            </form>

            <hr>

            <div class="panel-title"><?= __('Stopwords', 'intl_qa_lan') ?></div>
            <form method="post" action="" name="stopwords_frm" id="stopwords_frm">
                <table class="form-table" id="" role="presentation">
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" name="stopwords" value="<?= implode(',', get_option('iqa_stopwords', [])); ?>" placeholder="Enter Stopwords...">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="hidden" name="nonce" value="<?= wp_create_nonce('stopwords') ?>">
                    <input type="submit" class="button button-primary pull-left" name="submit" value="<?= __('Update', 'intl_qa_lan') ?>">
                </p>
            </form>

            <hr>

            <div class="panel-title"><?= __('Results Num', 'intl_qa_lan') ?></div>
            <form method="post" action="" name="results_num_frm" id="results_num_frm">
                <table class="form-table" id="" role="presentation">
                    <tbody>
                        <tr>
                            <td>
                                <input type="number" name="results_num" value="<?= get_option('results_num', 3); ?>" placeholder="Enter Results Number..." min="1" max="10">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="hidden" name="nonce" value="<?= wp_create_nonce('results_num') ?>">
                    <input type="submit" class="button button-primary pull-left" name="submit" value="<?= __('Save', 'intl_qa_lan') ?>">
                </p>
            </form>
        </div>

        <div class="panel" id="panel_two">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?= __('Question', 'intl_qa_lan') ?></th>
                        <th scope="col"><?= __('Keywords', 'intl_qa_lan') ?></th>
                        <th scope="col"><?= __('Views', 'intl_qa_lan') ?></th>
                        <th scope="col"><?= __('Enabled', 'intl_qa_lan') ?></th>
                        <th scope="col"><?= __('Date', 'intl_qa_lan') ?></th>
                        <th scope="col"><?= __('Operation', 'intl_qa_lan') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 1;
                    $nonce = wp_create_nonce('delete_qa');
                    foreach ($qanswes as $qa) : ?>
                        <tr>
                            <th scope="row"><?= $counter++; ?></th>
                            <td><?= $qa->question ?></td>
                            <td><?= $qa->keywords ?></td>
                            <td><?= $qa->views ?></td>
                            <td><?= $qa->enabled ?></td>
                            <td><?= $qa->updated_at ?></td>
                            <td>
                                <button type="button" class="button button-outline-danger" onclick="deleteQA(this)" data-id="<?= $qa->id ?>" data-nonce="<?= $nonce ?>">
                                    Delete
                                </button>
                                <button type="button" class="button button-outline-primary">Edit</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="panel" id="panel_three">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"><?= __('Search Phrase', 'intl_qa_lan') ?></th>
                        <th scope="col"><?= __('Count', 'intl_qa_lan') ?></th>
                        <th scope="col"><?= __('Date', 'intl_qa_lan') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 1;
                    foreach ($reports as $report) : ?>
                        <tr>
                            <th scope="row"><?= $counter++; ?></th>
                            <td><?= $report->input ?></td>
                            <td><?= $report->count ?></td>
                            <td><?= $report->updated_at ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
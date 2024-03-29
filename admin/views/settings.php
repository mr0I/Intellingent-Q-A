<?php defined('ABSPATH') or die('No script kiddies please!');
require_once(IQA_INC . 'controllers/QAController.php');
require_once(IQA_INC . 'helpers.php');
require_once(IQA_ROOT_DIR . 'lib/jdatetime.class.php');
$date = new jDateTime(true, true, 'Asia/Tehran');
$QAnswers = getAllQAs();
?>


<div class="menu-wrapper">
    <input class="radio" id="r_one" name="group" type="radio" checked>
    <input class="radio" id="r_two" name="group" type="radio">

    <div class="tabs">
        <label class="tab" id="tab_one" for="r_one"><?= __('General Settings', 'intl_qa_lan') ?></label>
        <label class="tab" id="tab_two" for="r_two"><?= __('Q/A List', 'intl_qa_lan') ?></label>
    </div>

    <div class="panels">

        <div class="panel" id="panel_one">
            <div class="panel-title"><?= __('Add Question and Answer', 'intl_qa_lan') ?></div>
            <form method="post" action="" name="add_qa_frm" id="add_qa_frm">
                <?php wp_nonce_field('add_qa', 'nonce') ?>
                <table class="form-table" id="top_menu_links_tbl" role="presentation">
                    <tbody>
                        <tr>
                            <td>
                                <textarea class="left-align" name="question" placeholder="<?= __('Enter Your Question...', 'intl_qa_lan') ?>" rows="3"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?php wp_editor(__('Enter Your Answer...', 'intl_qa_lan'), 'addqa_wpe', array(
                                    'textarea_rows' => 2,
                                    'textarea_name' => 'answer'
                                )) ?>
                            </td>
                        </tr>
                        <tr>
                            <td><input type="text" name="tags" placeholder="<?= __('Enter Keywords...', 'intl_qa_lan') ?>"></td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="submit" class="button button-primary pull-left" name="submit" value="<?= __('Save', 'intl_qa_lan') ?>">
                </p>
            </form>
            <hr>

            <div class="panel-title"><?= __('Stopwords', 'intl_qa_lan') ?></div>
            <form method="post" action="" name="stopwords_frm" id="stopwords_frm">
                <?php wp_nonce_field('stopwords', 'nonce') ?>
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
                    <input type="submit" class="button button-primary pull-left" name="submit" value="<?= __('Update', 'intl_qa_lan') ?>">
                </p>
            </form>
            <hr>

            <div class="panel-title"><?= __('Results Num', 'intl_qa_lan') ?></div>
            <form method="post" action="" name="results_num_frm" id="results_num_frm">
                <?php wp_nonce_field('results_num', 'nonce') ?>
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
                    <button type="submit" class="btn btn-primary btn-sm px-2" name="submit">
                        <?= __('Save', 'intl_qa_lan') ?>
                    </button>
                </p>
            </form>
        </div>

        <div class="panel" id="panel_two">
            <?php if (sizeof($QAnswers->data) === 0) : ?>
                <div class="toast">
                    <span class="icon-bell"></span>
                    <?= __('There is no item to show!', 'intl_qa_lan') ?>
                </div>
            <?php else : ?>
                <div class="container">
                    <div class="columns">
                        <div class="column col-12">
                            <table class="table table-striped table-hover" id="qanswers_tbl">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col"><?= __('Question', 'intl_qa_lan') ?></th>
                                        <th scope="col"><?= __('Views', 'intl_qa_lan') ?></th>
                                        <th scope="col"><?= __('Enabled', 'intl_qa_lan') ?></th>
                                        <th scope="col"><?= __('Date', 'intl_qa_lan') ?></th>
                                        <th scope="col"><?= __('Operation', 'intl_qa_lan') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $counter = 1;
                                    $nonce = wp_create_nonce('delete_qa');
                                    foreach ($QAnswers->data as $qa) : ?>
                                        <tr>
                                            <th scope="row"><?= $counter++; ?></th>
                                            <td><?= $qa->question ?></td>
                                            <td><?= $qa->views ?></td>
                                            <td><?= $qa->enabled ?></td>
                                            <td><?= $date->date("l - j/F/Y", strtotime($qa->updated_at)) ?></td>
                                            <td>
                                                <button class="button" data-id="qainfomodal" data-qaid="<?= $qa->id ?>" onclick="QaInfo(this)"><?= __('Info', 'intl_qa_lan') ?></button>
                                                <button type="button" class="button" onclick="deleteQA(this)" data-id="<?= $qa->id ?>" data-nonce="<?= $nonce ?>">
                                                    <?= __('Delete', 'intl_qa_lan') ?>
                                                </button>
                                                <a href="<?= ADMIN_URL . 'admin.php?page=iqa_editQA&id=' . $qa->id ?>" class="button button-outline-primary" data-id="<?= $qa->id ?>">
                                                    <?= __('Edit', 'intl_qa_lan') ?>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- modal -->
        <?php require_once(IQA_ADMIN . 'components/qa-info-modal.php'); ?>

    </div>
</div>
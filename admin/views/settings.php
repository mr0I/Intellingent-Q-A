<?php defined('ABSPATH') or die('No script kiddies please!');
require_once(IQA_INC . 'helpers.php');
require_once(IQA_ROOT_DIR . 'lib/jdatetime.class.php');
$date = new jDateTime(true, true, 'Asia/Tehran');
?>

<?php
global $wpdb;
$reportsTable = $wpdb->prefix . REPORT_TABLE;
$reports = $wpdb->get_results(" SELECT * FROM ${reportsTable} ORDER BY updated_at DESC ");
// $qaTable = $wpdb->prefix . QA_TABLE;
$offset = 0;
$limit = 5;
// $qanswes = $wpdb->get_results(" SELECT * FROM ${qaTable} ORDER BY updated_at DESC  LIMIT ${offset},${limit} ");
// $qanswesCount = $wpdb->get_var(" SELECT COUNT(id) FROM ${qaTable} ");

// $paginatedQAnswers = new stdClass();
// $paginatedQAnswers->results = $qanswes;
// $paginatedQAnswers->paginate = [
//     'current_page' => ($offset / $limit) + 1,
//     'last_page' => ceil(intval($qanswesCount) / $limit)
// ];

$paginatedQAnswers = getPaginatedQAnswers($offset, $limit);

// wp_die(json_encode($paginatedQAnswers->paginate['last_page'], JSON_PRETTY_PRINT));
// exit();
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
                    <input type="submit" class="btn btn-primary pull-left" name="submit" value="<?= __('Save', 'intl_qa_lan') ?>">
                </p>
            </form>
        </div>


        <div class="panel" id="panel_two">
            <?php if (sizeof($paginatedQAnswers->data) === 0) : ?>
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
                                    foreach ($paginatedQAnswers->data as $qa) : ?>
                                        <tr>
                                            <th scope="row"><?= $counter++; ?></th>
                                            <td><?= $qa->question ?></td>
                                            <td><?= $qa->views ?></td>
                                            <td><?= $qa->enabled ?></td>
                                            <td><?= $date->date("l - j/F/Y", strtotime($qa->updated_at)) ?></td>
                                            <td>
                                                <button type="button" class="button button-outline-primary" onclick="QaInfo(this)" data-id="<?= $qa->id ?>">
                                                    <?= __('Info', 'intl_qa_lan') ?>
                                                </button>
                                                <button type="button" class="button button-outline-danger" onclick="deleteQA(this)" data-id="<?= $qa->id ?>" data-nonce="<?= $nonce ?>">
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

                <div class="pagination">
                    <?php
                    $currentPage = $paginatedQAnswers->paginate['current_page'];
                    $lastPage = $paginatedQAnswers->paginate['last_page'];
                    ?>
                    <ul>
                        <li>
                            <a href="javascript:void(0)" class="qanswers-np-btn" data-offset="0" data-limit="5" data-cp="<?= $paginatedQAnswers->paginate['current_page'] ?>" onclick="goToNextPrevPage('qanswers', 'prev', this)"><i class="fa fa-arrow-right"></i></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="qanswers-np-btn" data-offset="0" data-limit="5" data-cp="<?= $paginatedQAnswers->paginate['current_page'] ?>" onclick="goToNextPrevPage('qanswers', 'next', this)">
                                <i class="fa fa-arrow-left"></i>
                            </a>
                        </li>
                        <li>
                            <span><?= __('Page', 'intl_qa_lan') ?></span>
                            <span>&nbsp;<?= $currentPage ?></span>
                            <span><?= __('From', 'intl_qa_lan') ?></span>
                            <span>&nbsp;<?= $lastPage ?></span>
                        </li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <div class="panel" id="panel_three">
            <?php if (sizeof($reports) === 0) : ?>
                <div class="empty">
                    <div class="empty-icon">
                        <i class="icon icon-people"></i>
                    </div>
                    <p class="empty-title h5">You have no new messages</p>
                    <p class="empty-subtitle">Click the button to start a conversation.</p>
                    <div class="empty-action">
                        <button class="btn btn-primary">Send a message</button>
                    </div>
                </div>
            <?php else : ?>
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
                                <td><?= $date->date("l - j/F/Y - H:i", strtotime($report->updated_at)) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif ?>




        </div>

        <!-- Modal -->
        <?php require_once(IQA_ADMIN . 'components/qa-info-modal.php'); ?>

    </div>
</div>
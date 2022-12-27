<?php defined('ABSPATH') or die('No script kiddies please!'); ?>


<?php
$constants = require_once(IQA_ROOT_DIR . 'config.php');
require_once(IQA_ROOT_DIR . 'lib/jdatetime.class.php');
$date = new jDateTime(true, true, 'Asia/Tehran');
require_once(IQA_INC . 'controllers/ReportsController.php');

$page = (isset($_GET['p'])) ? absint($_GET['p'])  : 1;
$limit = absint($constants['DEFAULT_PAGINATE_LIMIT']);
$offset = ($page - 1) * $limit;
$reports = getReports($offset,  $limit);
// wp_die($_SERVER['REQUEST_URI']);
?>


<div class="container">
    <?php if (sizeof($reports->data) === 0) : ?>
        <div class="empty">
            <div class="empty-icon">
                <span class="icon-frown" style="font-size: 50px;"></span>
            </div>
            <p class="empty-title h5"><?= __('There is no item to show!', 'intl_qa_lan') ?></p>
        </div>
    <?php else : ?>
        <h1><?= __('User Reports', 'intl_qa_lan') ?></h1>
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
                foreach ($reports->data as $report) : ?>
                    <tr>
                        <th scope="row"><?= $counter++; ?></th>
                        <td><?= $report->input ?></td>
                        <td><?= $report->count ?></td>
                        <td><?= $date->date("l - j/F/Y - H:i", strtotime($report->updated_at)) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- paginate -->
        <?php
        $items = $reports;
        require_once(IQA_ADMIN . 'components/full-paginate.php');
        ?>

    <?php endif ?>
</div>
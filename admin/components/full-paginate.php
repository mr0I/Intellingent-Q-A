<?php defined('ABSPATH') or die('No script kiddies please!');

$currentUrl = $_SERVER['REQUEST_URI'];

?>

<div class="pagination">
    <ul class="pagination">
        <li class="page-item <?= ($items->current_page == 1) ? 'disabled' : ''; ?>">
            <a href="#" tabindex="-1"><?= __('Previous', 'intl_qa_lan') ?></a>
        </li>
        <li class="page-item active">
            <a href="#">1</a>
        </li>
        <li class="page-item">
            <a href="#">2</a>
        </li>
        <li class="page-item">
            <a href="#">3</a>
        </li>
        <li class="page-item">
            <span>...</span>
        </li>
        <li class="page-item">
            <a href="#">12</a>
        </li>
        <li class="page-item">
            <a href="#">Next</a>
        </li>
    </ul>
</div>
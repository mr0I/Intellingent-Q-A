<?php defined('ABSPATH') or die('No script kiddies please!'); ?>

<?php
$prevUrl = sprintf("&p=%u", $page - 1);
$firstUrl = sprintf("&p=%u", 1);
$whichHalf = (abs($items->current_page - $items->last_page) > abs($items->current_page - 1))
    ? 'firstHalf'
    : 'secondHalf';
$lastUrl = sprintf("&p=%u", $items->last_page);
$nextUrl = sprintf("&p=%u", $page + 1);
?>


<div class="pagination">
    <ul class="pagination">
        <?php if ($items->current_page != 1) : ?>
            <li class="page-item <?= ($items->current_page == 1) ? 'disabled' : ''; ?>">
                <a href="<?= $pagePath . $prevUrl ?>" tabindex="-1"><?= __('Previous', 'intl_qa_lan') ?></a>
            </li>
        <?php endif; ?>
        <li class="page-item <?= ($items->current_page == 1) ? 'active' : ''; ?>">
            <a href="<?= $pagePath . $firstUrl ?>">1</a>
        </li>
        <?php if ($whichHalf === 'secondHalf') : ?>
            <li class="page-item">
                <span>...</span>
            </li>
        <?php endif; ?>

        <?php
        for ($i = 2; $i < $items->last_page; $i++) :
            if (abs($items->current_page - $i) < 3) :
                $pageUrl = sprintf('&p=%u', $i); ?>
                <li class="page-item <?= ($items->current_page == $i) ? 'active' : ''; ?>">
                    <a href="<?= $pagePath . $pageUrl ?>"><?= $i; ?></a>
                </li>
        <?php endif;
        endfor; ?>

        <?php if ($whichHalf === 'firstHalf') : ?>
            <li class="page-item">
                <span>...</span>
            </li>
        <?php endif; ?>
        <li class="page-item <?= ($items->current_page == $items->last_page) ? 'active' : ''; ?>">
            <a href="<?= $pagePath . $lastUrl ?>">
                <?= $items->last_page; ?>
            </a>
        </li>
        <?php if ($items->current_page != $items->last_page) : ?>
            <li class="page-item">
                <a href="<?= $pagePath . $nextUrl ?>"><?= __('Next', 'intl_qa_lan') ?></a>
            </li>
        <?php endif ?>
    </ul>
</div>
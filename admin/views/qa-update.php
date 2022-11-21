<?php defined('ABSPATH') or die('No script kiddies please!'); ?>

<?php
global $wpdb;
$qaID = absint($_GET['id']);
$qaTable = $wpdb->prefix . QA_TABLE;
$res = $wpdb->get_results(
    $wpdb->prepare("SELECT question, answer, keywords FROM ${qaTable} WHERE id=%d ", array($qaID))
);

$keywordsArray = json_decode($res[0]->keywords);
?>


<div class="container">
    <a href="javascript:history.back()">
        <?= __('Back', 'intl_qa_lan') ?>
        <i class="fa fa-back"></i>
    </a>

    <form method="post" action="" name="edit_qa_frm" id="edit_qa_frm">
        <?php wp_nonce_field('edit_qa', 'nonce'); ?>
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <td>
                        <textarea class="left-align" name="question" id="editQA_question" placeholder="<?= __('Enter Your Question...', 'intl_qa_lan') ?>" rows="3"><?= $res[0]->question ?></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php wp_editor($res[0]->answer, 'editqa_wpe', array(
                            'textarea_rows' => 2,
                            'textarea_name' => 'answer',
                            // 'quicktags' => false
                        )) ?>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" name="tags" id="editQA_tags" placeholder="<?= __('Enter Keywords...', 'intl_qa_lan') ?>" value="<?= implode(',', $keywordsArray) ?>"></td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="hidden" name="qa_id" value="<?= $qaID ?>">
            <!-- <input type="hidden" name="nonce" value="<?= sanitize_text_field($_GET['_wpnonce']) ?>"> -->
            <input type="submit" class="button button-primary pull-left" name="submit" value="<?= __('Edit', 'intl_qa_lan') ?>">
        </p>
    </form>
</div>
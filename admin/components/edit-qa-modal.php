<?php defined('ABSPATH') or die('No script kiddies please!'); ?>


<div class="modal" id="simple_modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>

        <table class="form-table" id="edit_qa_tbl" role="presentation">
            <tbody>
                <tr>
                    <td><input type="text" name="tags" id="editQA_tags" placeholder="<?= __('Enter Keywords...', 'intl_qa_lan') ?>"></td>
                </tr>
                <tr>
                    <td>
                        <textarea class="left-align" name="question" id="editQA_question" placeholder="<?= __('Enter Your Question...', 'intl_qa_lan') ?>" rows="3"></textarea>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php wp_editor(__('Enter Your Answer...', 'intl_qa_lan'), 'editqa_wpe', array(
                            'textarea_rows' => 2,
                            'textarea_name' => 'answer'
                        )) ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="hidden" name="nonce" value="<?= wp_create_nonce('edit_qa') ?>">
            <input type="submit" class="button button-primary pull-left" name="submit" value="<?= __('Edit', 'intl_qa_lan') ?>">
        </p>
    </div>
</div>
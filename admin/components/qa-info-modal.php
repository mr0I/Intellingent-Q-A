<?php defined('ABSPATH') or die('No script kiddies please!'); ?>


<div class="modal" id="qainfomodal">
    <a href="#close" class="modal-overlay" aria-label="Close"></a>
    <div class="modal-container">
        <div class="modal-header">
            <a href="#close" class="btn btn-clear float-right" aria-label="Close" onclick="closeModal('qainfomodal')"></a>
            <div class="modal-title h5"><?= __('QA Info', 'intl_qa_lan') ?></div>
        </div>
        <div class="modal-body">
            <div class="content">
                <div class="loading loading-lg" id="modal_loading"></div>

                <table class="table" id="modal_tbl" style="display: none;">
                    <tbody>
                        <tr>
                            <th scope="col"><?= __('Question', 'intl_qa_lan') ?></th>
                        </tr>
                        <tr>
                            <td id="qa_info_tbl_question"></td>
                        </tr>
                        <tr>

                        <tr>
                            <th scope="col"><?= __('Answer', 'intl_qa_lan') ?></th>
                        </tr>
                        <tr>
                            <td id="qa_info_tbl_answer"></td>
                        </tr>

                        <th scope="col"><?= __('Keywords', 'intl_qa_lan') ?></th>
                        </tr>
                        <tr>
                            <td id="qa_info_tbl_keywords"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-link text-bold text-error" onclick="closeModal('qainfomodal')">
                <?= __('close', 'intl_qa_lan') ?>
            </button>
        </div>
    </div>
</div>
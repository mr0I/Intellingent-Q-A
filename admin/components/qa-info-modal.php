<?php defined('ABSPATH') or die('No script kiddies please!'); ?>


<div class="modal" id="simple_modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>

        <table class="table">
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
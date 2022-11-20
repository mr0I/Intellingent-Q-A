(function ($) {
    $(window).on("load", function () {
        "use strict";

        // tagify
        $('input[name=tags]').tagify({
            duplicates: false,
            maxTags: 10
        });
        $('input[name=stopwords]').tagify({
            duplicates: false
        });

        // modal
        window.modal = document.getElementById("simple_modal");
        window.onclick = function (event) {
            if (event.target == modal) modal.style.display = "none";
        }

    });
})(jQuery);
jQuery(document).ready(function ($) {

    // constants
    window.jq = $;

    // add Q/A form
    let qaForm = document.getElementsByName('add_qa_frm');
    $(qaForm).on('submit', function (e) {
        e.preventDefault();

        const submitBtn = document.forms['add_qa_frm']['submit'];
        let formObj = {};
        const formArray = $(this).serializeArray();
        $(formArray).each((index, elm) => {
            formObj[elm.name] = elm.value;
        });
        const { question, answer, nonce, tags } = formObj;
        const tagsArray = Object.values(JSON.parse(tags)).map(tag => {
            return tag.value
        });

        $.ajax({
            url: IQA_ADMIN_Ajax.ajaxurl,
            type: 'POST',
            data: {
                security: IQA_ADMIN_Ajax.security,
                action: 'addQa',
                nonce: nonce,
                question: question,
                answer: answer,
                keywords: JSON.stringify(tagsArray)
            },
            beforeSend: () => {
                $(submitBtn).val(IQA_ADMIN_Ajax.SAVING_TEXT).attr('disabled', true);
            },
            success: (res, xhr) => {
                if (xhr == 'success' && res.success) {
                    alert(IQA_ADMIN_Ajax.SUCCESS_MESSAGE);
                    $(qaForm).trigger('reset');
                } else {
                    alert(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                alert(jqXHR.responseJSON.data);
            },
            complete: () => {
                $(submitBtn).val(IQA_ADMIN_Ajax.SAVE_TEXT).attr('disabled', false);
            },
            timeout: IQA_ADMIN_Ajax.REQUEST_TIMEOUT
        });
    });

    // add stop-words form
    let stopwordsForm = document.getElementsByName('stopwords_frm');
    $(stopwordsForm).on('submit', function (e) {
        e.preventDefault();

        const submitBtn = document.forms['stopwords_frm']['submit'];
        let formObj = {};
        const formArray = $(this).serializeArray();
        $(formArray).each((index, elm) => {
            formObj[elm.name] = elm.value;
        });
        const { stopwords, nonce } = formObj;
        const stopwordsArray = Object.values(JSON.parse(stopwords)).map(stopword => {
            return stopword.value
        });

        $.ajax({
            url: IQA_ADMIN_Ajax.ajaxurl,
            type: 'POST',
            data: {
                security: IQA_ADMIN_Ajax.security,
                action: 'addStopWord',
                stopwords: JSON.stringify(stopwordsArray),
                nonce: nonce
            },
            beforeSend: () => {
                $(submitBtn).val(IQA_ADMIN_Ajax.UPDATING_TEXT).attr('disabled', true);
            },
            success: (res, xhr) => {
                if (xhr == 'success' && res.success) alert(IQA_ADMIN_Ajax.SUCCESS_MESSAGE);
                else alert(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
            },
            error: (jqXHR, textStatus, errorThrown) => {
                alert(jqXHR.responseJSON.data);
            },
            complete: () => {
                $(submitBtn).val(IQA_ADMIN_Ajax.UPDATE_TEXT).attr('disabled', false);
            },
            timeout: IQA_ADMIN_Ajax.REQUEST_TIMEOUT
        });
    })

    // set results number form
    let resultsNumForm = document.getElementsByName('results_num_frm');
    $(resultsNumForm).on('submit', function (e) {
        e.preventDefault();

        const submitBtn = document.forms['results_num_frm']['submit'];
        let formObj = {};
        const formArray = $(this).serializeArray();
        $(formArray).each((index, elm) => {
            formObj[elm.name] = elm.value;
        });
        const { results_num, nonce } = formObj;

        $.ajax({
            url: IQA_ADMIN_Ajax.ajaxurl,
            type: 'POST',
            data: {
                security: IQA_ADMIN_Ajax.security,
                action: 'SetResultNum',
                results_num: Number(results_num),
                nonce: nonce
            },
            beforeSend: () => {
                $(submitBtn).val(IQA_ADMIN_Ajax.SAVING_TEXT).attr('disabled', true);
            },
            success: (res, xhr) => {
                if (xhr == 'success' && res.success) alert(IQA_ADMIN_Ajax.SUCCESS_MESSAGE);
                else alert(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
            },
            error: (jqXHR, textStatus, errorThrown) => {
                alert(jqXHR.responseJSON.data);
            },
            complete: () => {
                $(submitBtn).val(IQA_ADMIN_Ajax.SAVE_TEXT).attr('disabled', false);
            },
            timeout: IQA_ADMIN_Ajax.REQUEST_TIMEOUT
        });
    })

    // edit Q/A form
    let editQAForm = document.getElementsByName('edit_qa_frm');
    $(editQAForm).on('submit', function (e) {
        e.preventDefault();

        const submitBtn = document.forms['edit_qa_frm']['submit'];
        let formObj = {};
        const formArray = $(this).serializeArray();
        $(formArray).each((index, elm) => {
            formObj[elm.name] = elm.value;
        });
        const { question, answer, nonce, tags, qa_id } = formObj;
        const tagsArray = Object.values(JSON.parse(tags)).map(tag => {
            return tag.value
        });

        $.ajax({
            url: IQA_ADMIN_Ajax.ajaxurl,
            type: 'POST',
            data: {
                security: IQA_ADMIN_Ajax.security,
                action: 'editQa',
                nonce: nonce,
                qa_id: qa_id,
                question: question,
                answer: answer,
                keywords: JSON.stringify(tagsArray)
            },
            beforeSend: () => {
                $(submitBtn).val(IQA_ADMIN_Ajax.UPDATING_TEXT).attr('disabled', true);
            },
            success: (res, xhr) => {
                if (xhr == 'success' && res.success) {
                    alert(IQA_ADMIN_Ajax.SUCCESS_MESSAGE);
                    if ('referrer' in document) window.location = document.referrer;
                    else window.history.back();
                } else {
                    alert(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                alert(jqXHR.responseJSON.data);
            },
            complete: () => {
                $(submitBtn).val(IQA_ADMIN_Ajax.UPDATE_TEXT).attr('disabled', false);
            },
            timeout: IQA_ADMIN_Ajax.REQUEST_TIMEOUT
        });
    });

});


function deleteQA(d) {
    if (window.confirm(IQA_ADMIN_Ajax.CONFIRM_TEXT)) {
        const qaID = d.getAttribute('data-id');
        const nonce = d.getAttribute('data-nonce');

        jq.ajax({
            url: IQA_ADMIN_Ajax.ajaxurl,
            type: 'POST',
            data: {
                security: IQA_ADMIN_Ajax.security,
                action: 'DeleteQA',
                qa_id: Number(qaID),
                nonce: nonce
            },
            beforeSend: () => {
                // $(submitBtn).val(IQA_ADMIN_Ajax.SAVING_TEXT).attr('disabled', true);
            },
            success: (res, xhr) => {
                if (xhr == 'success' && res.success) {
                    alert(IQA_ADMIN_Ajax.SUCCESS_MESSAGE);
                    window.location.reload();
                }
                else alert(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
            },
            error: (jqXHR, textStatus, errorThrown) => {
                alert(jqXHR.responseJSON.data);
            },
            complete: () => {
                // $(submitBtn).val(IQA_ADMIN_Ajax.SAVE_TEXT).attr('disabled', false);
            },
            timeout: IQA_ADMIN_Ajax.REQUEST_TIMEOUT
        });
    }

}

function QaInfo(d) {
    const qaID = d.getAttribute('data-id');
    if (qaID === '') {
        alert(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
        return;
    }
    modal.style.display = "block";

    fetch(IQA_ADMIN_Ajax.ajaxurl, {
        method: 'POST',
        credentials: 'same-origin',
        headers: new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'
        }),
        body: new URLSearchParams({
            security: IQA_ADMIN_Ajax.security,
            action: 'getQAData',
            qa_id: qaID
        })
    }).then(async (response) => {
        const res = await response.json();
        if (!res.success) {
            alert(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
            return;
        }

        await delay(500);
        const questionCol = document.getElementById('qa_info_tbl_question');
        const keywordsCol = document.getElementById('qa_info_tbl_keywords');
        const answerCol = document.getElementById('qa_info_tbl_answer');
        keywordsCol.innerHTML = res.qa_data[0].keywords;
        questionCol.innerHTML = res.qa_data[0].question;
        answerCol.innerHTML = res.qa_data[0].answer;
    })
}

const closeModal = () => {
    modal.style.display = "none";
}

const delay = (ms) => new Promise((resolve, reject) => {
    setTimeout(resolve, ms);
});
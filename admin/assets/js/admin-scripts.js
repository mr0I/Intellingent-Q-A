(function ($) {
    $(window).on("load", function () {
        "use strict";

        // inits
        $('input[name=tags]').tagify({
            duplicates: false,
            maxTags: 10
        });
        $('input[name=stopwords]').tagify({
            duplicates: false
        });
        $('#qanswers_tbl').DataTable({
            scrollY: 300,
            select: true,
            language: {
                "zeroRecords": 'موردی برای نمایش یافت نشد!',
                "search": 'جستجو',
                "info": "نمایش _START_ تا _END_ از _TOTAL_ ورودی",
                "lengthMenu": "نمایش _MENU_ ورودی",
                "paginate": {
                    "first": "اولین",
                    "last": "آخرین",
                    "next": "بعدی",
                    "previous": "قبلی"
                },
            }
        });
        Toastify({
            duration: 2000,
            newWindow: true,
            close: false,
            gravity: "top", // `top` or `bottom`
            position: "center", // `left`, `center` or `right`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            onClick: function () { } // Callback after click
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
            url: IQA_ADMIN_Ajax.AJAXURL,
            type: 'POST',
            data: {
                SECURITY: IQA_ADMIN_Ajax.SECURITY,
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
                    showSuccessToast(IQA_ADMIN_Ajax.SUCCESS_MESSAGE);
                    $(qaForm).trigger('reset');
                } else {
                    showErrorToast(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                showErrorToast(jqXHR.responseJSON.data);
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
            url: IQA_ADMIN_Ajax.AJAXURL,
            type: 'POST',
            data: {
                SECURITY: IQA_ADMIN_Ajax.SECURITY,
                action: 'addStopWord',
                stopwords: JSON.stringify(stopwordsArray),
                nonce: nonce
            },
            beforeSend: () => {
                $(submitBtn).val(IQA_ADMIN_Ajax.UPDATING_TEXT).attr('disabled', true);
            },
            success: (res, xhr) => {
                if (xhr == 'success' && res.success) showSuccessToast(IQA_ADMIN_Ajax.SUCCESS_MESSAGE);
                else showErrorToast(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
            },
            error: (jqXHR, textStatus, errorThrown) => {
                showErrorToast(jqXHR.responseJSON.data);
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
            url: IQA_ADMIN_Ajax.AJAXURL,
            type: 'POST',
            data: {
                SECURITY: IQA_ADMIN_Ajax.SECURITY,
                action: 'SetResultNum',
                results_num: Number(results_num),
                nonce: nonce
            },
            beforeSend: () => {
                $(submitBtn).addClass('loading').attr('disabled', true);
            },
            success: (res, xhr) => {
                if (xhr == 'success' && res.success) showSuccessToast(IQA_ADMIN_Ajax.SUCCESS_MESSAGE);
                else showErrorToast(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
            },
            error: (jqXHR, textStatus, errorThrown) => {
                showErrorToast(jqXHR.responseJSON.data);
            },
            complete: () => {
                $(submitBtn).removeClass('loading').attr('disabled', false);
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
            url: IQA_ADMIN_Ajax.AJAXURL,
            type: 'POST',
            data: {
                SECURITY: IQA_ADMIN_Ajax.SECURITY,
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
                    showSuccessToast(IQA_ADMIN_Ajax.SUCCESS_MESSAGE);
                    if ('referrer' in document) window.location = document.referrer;
                    else window.history.back();
                } else {
                    showErrorToast(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                showErrorToast(jqXHR.responseJSON.data);
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
            url: IQA_ADMIN_Ajax.AJAXURL,
            type: 'POST',
            data: {
                SECURITY: IQA_ADMIN_Ajax.SECURITY,
                action: 'DeleteQA',
                qa_id: Number(qaID),
                nonce: nonce
            },
            beforeSend: () => {
                // $(submitBtn).val(IQA_ADMIN_Ajax.SAVING_TEXT).attr('disabled', true);
            },
            success: (res, xhr) => {
                if (xhr == 'success' && res.success) {
                    showSuccessToast(IQA_ADMIN_Ajax.SUCCESS_MESSAGE);
                    window.location.reload();
                }
                else showErrorToast(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
            },
            error: (jqXHR, textStatus, errorThrown) => {
                showErrorToast(jqXHR.responseJSON.data);
            },
            complete: () => {
                // $(submitBtn).val(IQA_ADMIN_Ajax.SAVE_TEXT).attr('disabled', false);
            },
            timeout: IQA_ADMIN_Ajax.REQUEST_TIMEOUT
        });
    }
}

function QaInfo(d) {
    const qaID = d.getAttribute('data-qaid');
    const modalID = d.getAttribute('data-id');
    console.log(modalID);
    if (qaID === '') {
        showErrorToast(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
        return;
    }
    const modal = document.getElementById(modalID);
    modal.className = 'modal active';

    fetch(IQA_ADMIN_Ajax.AJAXURL, {
        method: 'POST',
        credentials: 'same-origin',
        headers: new Headers({
            'Content-Type': 'application/x-www-form-urlencoded'
        }),
        body: new URLSearchParams({
            SECURITY: IQA_ADMIN_Ajax.SECURITY,
            action: 'getQAData',
            qa_id: qaID
        })
    }).then(async (response) => {
        const res = await response.json();
        if (!res.success) {
            showErrorToast(IQA_ADMIN_Ajax.FAILURE_MESSAGE);
            return;
        }

        const modalLoading = document.getElementById('modal_loading');
        const modalTable = document.getElementById('modal_tbl');
        const questionCol = document.getElementById('qa_info_tbl_question');
        const keywordsCol = document.getElementById('qa_info_tbl_keywords');
        const answerCol = document.getElementById('qa_info_tbl_answer');
        await delay(500);
        modalLoading.style.display = 'none';
        modalTable.style.display = 'block';
        keywordsCol.innerHTML = res.qa_data[0].keywords;
        questionCol.innerHTML = res.qa_data[0].question;
        answerCol.innerHTML = res.qa_data[0].answer;
    })
}


const closeModal = (modalID) => {
    const modal = document.getElementById(modalID);
    modal.className = 'modal';
    const modalLoading = document.getElementById('modal_loading');
    const modalTable = document.getElementById('modal_tbl');
    modalLoading.style.display = 'block';
    modalTable.style.display = 'none';
}

const delay = (ms) => new Promise((resolve, reject) => {
    setTimeout(resolve, ms);
});

const showSuccessToast = (msg) => {
    Toastify({
        text: msg,
        position: "center",
        style: {
            background: "#00C851",
        }
    }).showToast();
};
const showErrorToast = (msg) => {
    Toastify({
        text: msg,
        position: "center",
        style: {
            background: "#f44",
        }
    }).showToast();
}
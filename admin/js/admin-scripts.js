(function($) {
    $(window).on("load", function() {
        "use strict";
    });
})(jQuery);
jQuery(document).ready(function($){
    // Add Q/A form submit
    const qaForm = document.getElementsByName('add_qa_frm');
    $(qaForm).on('submit', function (e) {
        const submitBtn = document.forms['add_qa_frm']['submit'];
        e.preventDefault();let formObj = {};
        const formArray = $(this).serializeArray();
        $(formArray).each((index,elm) => {
            formObj[elm.name] = elm.value;
        });
        const {question, answer, nonce} = formObj;

        $.ajax({
            url: IQA_ADMIN_Ajax.ajaxurl,
            type: 'POST',
            data: {
                security : IQA_ADMIN_Ajax.security,
                action: 'addQa',
                nonce: 'dsdd',
                question: question,
                answer: answer
            },
            beforeSend: function () {
                submitBtn.value = IQA_ADMIN_Ajax.saving_text;
                submitBtn.disabled= true;
            },
            success: function (res , xhr) {
                console.log('resss: ',res);
                // if (xhr === 'success' && res.success){
                //
                // }
            },error:function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseJSON.data);
            }
            ,complete:function () {
                submitBtn.value = 'Save';
                submitBtn.disabled = false;
            },
            timeout:IQA_ADMIN_Ajax.request_timeout
        });
    })
});
(function($) {
    $(window).on("load", function() {
        "use strict";

        $('input[name=tags]').tagify({
            duplicates:false,
            maxTags:10
        });
    });
})(jQuery);
jQuery(document).ready(function($){

    // Add Q/A form submit
    let qaForm = document.getElementsByName('add_qa_frm');
    $(qaForm).on('submit', function (e) {
        e.preventDefault();

        const submitBtn = document.forms['add_qa_frm']['submit'];
        let formObj = {};
        const formArray = $(this).serializeArray();
        $(formArray).each((index, elm) => {
            formObj[elm.name] = elm.value;
        });
        const {question, answer, nonce, tags} = formObj;
        const tagsArray = Object.values(JSON.parse(tags)).map(tag => {
            return tag.value
        });

        $.ajax({
            url: IQA_ADMIN_Ajax.ajaxurl,
            type: 'POST',
            data: {
                security : IQA_ADMIN_Ajax.security,
                action: 'addQa',
                nonce: nonce,
                question: question,
                answer: answer,
                keywords: JSON.stringify(tagsArray)
            },
            beforeSend: () => {
                $(submitBtn).val(IQA_ADMIN_Ajax.SAVING_TEXT).attr('disabled',true);
            },
            success: (res ,xhr) => {
                if (xhr == 'success' && res.success){
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
                $(submitBtn).val('Save').attr('disabled',false);
            },
            timeout:IQA_ADMIN_Ajax.REQUEST_TIMEOUT
        });
    })

});
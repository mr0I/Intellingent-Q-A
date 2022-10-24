(function($) {
    $(window).on("load", function() {
        "use strict";
    });
})(jQuery);
jQuery(document).ready(function($) {
    window.jq = $;

});


function searchQA(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    const input = normalizeText(formData.get('search'));
    const nonce = formData.get('nonce');
   // const tokenizedInput = input.split(' ');

    window.jq.ajax({
        url: IQA_Ajax.ajaxurl,
        type: 'POST',
        data: {
            security : IQA_Ajax.security,
            action: 'searchQa',
            nonce: nonce,
            input: input
        },
        beforeSend: () => {
            // $(submitBtn).val(IQA_Ajax.saving_text).attr('disabled',true);
        },
        success: (res ,xhr) => {
            console.log(res);
            // if (xhr == 'success' && res.success){
            //     alert(IQA_Ajax.success_message);
            //     $(qaForm).trigger('reset');
            // } else {
            //     alert(IQA_Ajax.failure_message);
            // }
        },
        error: (jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
        },
        complete: () => {
            // $(submitBtn).val('Save').attr('disabled',false);
        },
        timeout:IQA_Ajax.request_timeout
    });

}
const normalizeText = (input) => {
    //remove special characters
    input = input.replace(/([^\u0621-\u063A\u0641-\u064A\u0660-\u0669a-zA-Z 0-9])/g, '');
    //normalize Arabic
    input = input.replace(/(آ|إ|أ)/g, 'ا')
        .replace(/(ة)/g, 'ه')
        .replace(/(ؤ)/g, 'و')
        .replace(/(ي|ئ|ء)/g, 'ی')
        .replace(/(ك)/g, 'ک');
    //convert arabic numerals to english counterparts.
    let starter = 0x660;
    for (let i = 0; i < 10; i++) {
        input.replace(String.fromCharCode(starter + i), String.fromCharCode(48 + i));
    }

    return input.trim();
};
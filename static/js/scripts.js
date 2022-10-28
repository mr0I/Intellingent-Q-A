(function($) {
    $(window).on("load", function() {
        "use strict";
    });
})(jQuery);
jQuery(document).ready(function($) {
    window.jq = $;

});


const searchQA = (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const input = normalizeText(formData.get('search'));
    const nonce = formData.get('nonce');

    jq.ajax({
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
            if (xhr == 'success' && res.success){
                const eligibleRows = res.result;
                const sortedRows = eligibleRows.sort((r1,r2) => {
                    return (r1.score < r2.score) ? 1 :  (r1.score > r2.score) ? -1 : 0;
                });
                const tokenizeInput = input.split(' ');

                let i = 0, secondaryScore = 0, finalRows = [];
                while ( (sortedRows.length>4) ? i <= 4 : i < sortedRows.length ){
                    const currentAnswer = sortedRows[i].answer;
                    const tokenizeAnswer = currentAnswer.split(' ');
                    for (let item of tokenizeInput){
                        for (let answer of tokenizeAnswer){
                            // console.log(answer);
                            // console.log(item);
                            const pattern = `/${answer}/`;
                            console.log(pattern);
                            if (pattern.test(item)) secondaryScore++;
                            // if (item.match('/ردیاب/g')) secondaryScore++;
                        }
                    }
                    if (secondaryScore > 0){
                        finalRows.push(secondaryScore);
                    }

                    i++;
                }
                console.log(finalRows);

            } else {
                alert(IQA_Ajax.failure_message);
            }
        },
        error: (jqXHR, textStatus, errorThrown) => {
            console.log(jqXHR);
        },
        complete: () => {
            // $(submitBtn).val('Save').attr('disabled',false);
        },
        timeout:IQA_Ajax.request_timeout
    });

};
const normalizeText = (input) => {
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
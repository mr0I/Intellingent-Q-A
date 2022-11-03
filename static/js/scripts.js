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
    const answersList = jq('.answers-list').find('ul');
    showHideResults(answersList, 'fast');

    const formData = new FormData(e.target);
    const input = normalizeText(formData.get('search'));
    const nonce = formData.get('nonce');

    return new Promise( (resolve, reject) => {
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
                    // show processing loader
                    jq('.alert').css('display', 'block');


                    const eligibleRows = res.result;
                    const sortedRows = eligibleRows.sort((r1,r2) => {
                        return (r1.primary_score < r2.primary_score) ? 1 :  (r1.primary_score > r2.primary_score) ? -1 : 0;
                    });
                    const tokenizeInput = input.split(' ');

                    let i = 0, secondaryScore = 0, finalRows = [];
                    while ( (sortedRows.length > 4) ? i <= 4 : i < sortedRows.length ){
                        const currentAnswer = normalizeText(sortedRows[i].answer);
                        const tokenizeAnswer = currentAnswer.split(' ');
                        const answersArray = removeStopWords(tokenizeAnswer);
                        console.log('ps',sortedRows[i].primary_score);

                        for (let item of tokenizeInput){
                            for (let answer of answersArray){
                                const pattern = new RegExp( answer , 'i');
                                if (item.match(pattern)) {
                                    secondaryScore++;
                                    console.log(item + '---' + answer);
                                }
                            }
                        }
                        if (secondaryScore > 0){
                            finalRows.push({
                                'id': sortedRows[i].id,
                                'overall_score': Math.ceil((Number(sortedRows[i].primary_score) * 3)
                                    + (Number(secondaryScore) * 1)),
                                'answer': sortedRows[i].answer,
                            });
                        }

                        i++;
                        secondaryScore = 0;
                    }

                    if (finalRows.length === 0){
                        jq('.alert').css('display', 'none');
                        showHideResults(answersList);
                        jq(answersList).append(`
                          <li>
                            <span>No Result!!!</span>
                          </li>  
                        `);
                        return;
                    }


                    // increment views count
                    // jq.ajax({
                    //     url: IQA_Ajax.ajaxurl,
                    //     type: 'POST',
                    //     data: {
                    //         security : IQA_Ajax.security,
                    //         action: 'searchQa',
                    //         nonce: nonce,
                    //         input: input
                    //     },
                    //     beforeSend: () => {
                    //         // $(submitBtn).val(IQA_Ajax.saving_text).attr('disabled',true);
                    //     },
                    //     success: (res ,xhr) => {
                    //         console.log(res);
                    //
                    //     },
                    //     error: (jqXHR, textStatus, errorThrown) => {
                    //         console.log(jqXHR);
                    //     },
                    //     complete: () => {
                    //         // $(submitBtn).val('Save').attr('disabled',false);
                    //     },
                    //     timeout:IQA_Ajax.REQUEST_TIMEOUT
                    // });

                    const sortedFinalRows = finalRows.sort((r1, r2) => {
                        return (r1.overall_score < r2.overall_score) ? 1 :  (r1.overall_score > r2.overall_score) ? -1 : 0;
                    });
                    sortedFinalRows.forEach((row, index) => {
                        console.log('sorted', index + '---' + row);
                        if (index < 2){
                            jq(answersList).append(`
                          <li>
                            <span>${++index}</span>
                            <span>${row.answer}</span>
                          </li>  
                        `)
                        }
                    });
                    showHideResults(answersList);
                } else {
                    alert(IQA_Ajax.NO_RESULT);
                }
            },
            error: (jqXHR, textStatus, errorThrown) => {
                console.log(jqXHR);
            },
            complete: () => {
                // $(submitBtn).val('Save').attr('disabled',false);
            },
            timeout:IQA_Ajax.REQUEST_TIMEOUT
        });
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

const removeStopWords = (array) => {
    const jsonData = JSON.parse(document.getElementById("stop_words_array").innerHTML,false);
    const stopWords = jsonData.stop_words;
    console.log('sww',stopWords);

    array.forEach((item, index) => {
        if (stopWords.includes(item)) {
            array.splice(index, 1);
        }
    });

    return array;
};

const showHideResults = (elm, speed='slow') => {
    jq(elm).html('').slideDown(speed);
};
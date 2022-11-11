(function($) {
    $(window).on("load", function() {
        "use strict";
    });
})(jQuery);
jQuery(document).ready(function($) {
    window.jq = $;
    window.jsonData = JSON.parse(document.getElementById("stop_words_array")
        .innerHTML,false);
});


const searchQA = async (e) => {
    e.preventDefault();
    const answersList = jq('.answers-list').find('ul');
    const formData = new FormData(e.target);
    const input = normalizeText(formData.get('search'));
    const nonce = formData.get('nonce');
    showHideResults(answersList,'hide','fast');

    return new Promise( (resolve, reject) => {
        // $(submitBtn).val(IQA_Ajax.saving_text).attr('disabled',true);

        fetch(IQA_Ajax.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: new Headers({
                'Content-Type': 'application/x-www-form-urlencoded',
                //'Cache-Control': 'no-cache'
            }),
            body: new URLSearchParams({
                security : IQA_Ajax.security,
                action: 'searchQa',
                nonce: nonce,
                input: input
            })
        }).then(async (response) => {
            const res = await response.json();
            if (!res.success){
                reject(IQA_Ajax.NO_RESULT);
            }
            // show processing loader
            showHideProccessingLoader('.alert', 'flex');

            const eligibleRows = res.result;
            const sortedRows = eligibleRows.sort((r1,r2) => {
                return (r1.primary_score < r2.primary_score) ? 1 :  (r1.primary_score > r2.primary_score) ? -1 : 0;
            });
            const tokenizeInput = input.split(' ');

            let i = 0, secondaryScore = 0, finalRows = [];
            const resultsNum = jsonData.results_num;
            while ( (sortedRows.length > (resultsNum*2) ) ? i <= (resultsNum*2) : i < sortedRows.length ){
                const currentAnswer = normalizeText(sortedRows[i].answer);
                const tokenizeAnswer = currentAnswer.split(' ');
                const answersArray = removeStopWords(tokenizeAnswer);
                console.log('arr',answersArray);

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
                            + (Number(secondaryScore))),
                        'answer': sortedRows[i].answer,
                    });
                }

                i++;
                secondaryScore = 0;
            }

            if (finalRows.length === 0){
                showHideProccessingLoader('.alert', 'none');
                showHideResults(answersList,'show');
                jq(answersList).append(` <li> <span>No Result!!!</span> </li> `);
                return;
            }

            const sortedFinalRows = finalRows.sort((r1, r2) => {
                return (r1.overall_score < r2.overall_score) ? 1 :  (r1.overall_score > r2.overall_score) ? -1 : 0;
            });
            resolve({
                'final_rows': sortedFinalRows,
                'results_num': resultsNum,
                'input': input
            });
        });
    }).then((resolve_data) => {
        const sortedFinalRows = resolve_data.final_rows;
        fetch(IQA_Ajax.ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: new Headers({
                'Content-Type': 'application/x-www-form-urlencoded'
            }),
            body: new URLSearchParams({
                security: IQA_Ajax.security,
                action: 'incrementViewsCount',
                nonce: nonce,
                rows: JSON.stringify(sortedFinalRows),
                input: input
            })
        }).then(async (response2) => {
            const res2 = await response2.json();
            if (!res2.success) {
                alert('error');
                return false;
            }

            showHideResults(answersList,'show');
            showHideProccessingLoader('.alert', 'none');
            sortedFinalRows.forEach((row, index) => {
                // console.log('sorted', index + '---' + row);
                if (index < (resolve_data.results_num)){
                    jq(answersList).append(`
                          <li>
                            <span>${++index}</span>
                            <span>${row.answer}</span>
                          </li>  
                        `)
                }
            });
        });
    }).catch(e => {
        alert(e);
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
    const stopWords = jsonData.stop_words;

    array.forEach((item, index) => {
        if (stopWords.includes(item)) {
            array.splice(index, 1);
        }
    });
    return array;
};

const showHideResults = (elm, type, speed='slow') => {
    switch (type) {
        case 'show':
            jq(elm).html('').slideDown(speed);
            break;
        case 'hide':
            jq(elm).html('').slideUp(speed);
            break;
        default:
            jq(elm).html('').slideDown(speed);
    }
};

const showHideProccessingLoader = (elm, type) => {
    jq(elm).css('display', type);
};
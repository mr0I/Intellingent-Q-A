<?php defined('ABSPATH') or die('No script kiddies please!');

require_once IQA_INC . 'helpers.php';


function addQa_callback()
{
    if (!wp_verify_nonce($_POST['nonce'], 'add_qa') || !check_ajax_referer('OwpCojMcdGJ-k-o', 'security')) {
        wp_send_json_error('Forbidden', 403);
        exit();
    }

    global $wpdb;
    $question = sanitize_text_field($_POST['question']);
    $answer = stripslashes($_POST['answer']);
    $keywords = sanitize_text_field(stripslashes($_POST['keywords']));
    $qaTable = $wpdb->prefix . QA_TABLE;
    $insert = $wpdb->insert($qaTable, [
        'question' => $question,
        'answer' => $answer,
        'keywords' => $keywords,
        'created_at' => current_time('mysql'),
        'updated_at' => current_time('mysql'),
    ], ['%s', '%s', '%s', '%s', '%s']);

    if (!$insert) {
        sendResponse(['success' => false]);
    }

    sendResponse(['success' => true]);
}
add_action('wp_ajax_addQa', 'addQa_callback');
add_action('wp_ajax_nopriv_addQa', 'addQa_callback');

function searchQa_callback()
{
    if (!wp_verify_nonce($_POST['nonce'], 'search_qa') || !check_ajax_referer('mnhUciSW!Zk/oBB', 'security')) {
        wp_send_json_error('Forbidden', 403);
        exit();
    }

    global $wpdb;
    $table = $wpdb->prefix . QA_TABLE;
    $query = $wpdb->prepare("SELECT * FROM $table WHERE enabled=%s", array(true));
    $rows = $wpdb->get_results($query);
    $tokenizeInput = explode(' ', $_POST['input']);

    if (count($rows) === 0) {
        sendResponse(['success' => false]);
    }

    $i = 0;
    $score = 0;
    $eligibleRows = [];
    while ($i < count($rows)) {
        $keywords = json_decode($rows[$i]->keywords);
        $flattenKeywords = arrayFlatten(array_map(function ($item) {
            return explode(' ', $item);
        }, $keywords));
        foreach ($tokenizeInput as $item) {
            foreach ($flattenKeywords as $keyword) {
                if (preg_match("/$keyword/i", $item)) {
                    $score++;
                }
            }
        }
        if ($score > 0) {
            array_push($eligibleRows, [
                'id' => $rows[$i]->id,
                'primary_score' => $score + ($score / sizeof($flattenKeywords)),
                'answer' => $rows[$i]->answer
            ]);
        }
        $i++;
        $score = 0;
    }

    sendResponse([
        'success' => true,
        'result' => $eligibleRows
    ]);
}
add_action('wp_ajax_searchQa', 'searchQa_callback');
add_action('wp_ajax_nopriv_searchQa', 'searchQa_callback');

function addStopWord_callback()
{
    if (!wp_verify_nonce($_POST['nonce'], 'stopwords') || !check_ajax_referer('OwpCojMcdGJ-k-o', 'security')) {
        wp_send_json_error('Forbidden', 403);
        exit();
    }

    $stopwords = sanitize_text_field(stripslashes($_POST['stopwords']));
    $res = update_option('iqa_stopwords', json_decode($stopwords));

    if (!$res) {
        sendResponse([
            'success' => false
        ]);
    }
    sendResponse([
        'success' => true,
        'result' => $stopwords
    ]);
}
add_action('wp_ajax_addStopWord', 'addStopWord_callback');
add_action('wp_ajax_nopriv_addStopWord', 'addStopWord_callback');


function incrementViewsCount_callback()
{
    if (!wp_verify_nonce($_POST['nonce'], 'search_qa') || !check_ajax_referer('mnhUciSW!Zk/oBB', 'security')) {
        wp_send_json_error('Forbidden', 403);
        exit();
    }

    global $wpdb;
    $table = $wpdb->prefix . QA_TABLE;
    $sortedRows = json_decode(stripslashes($_POST['rows']), false);
    $i = 0;
    $j = 0;
    $resultsNum = intval(get_option('results_num'));

    foreach ($sortedRows as $row) {
        if ($i < $resultsNum) {
            $res = $wpdb->query(
                $wpdb->prepare(
                    "UPDATE ${table} SET views=views+1 WHERE id=%d",
                    array($row->id)
                )
            );
            if ($res) $j++;
            $i++;
        }
    }

    if ($i !== $j) {
        $date = date('Y/m/d - h:i:sa');
        $searchQuery = sanitize_text_field($_POST['input']);
        $debugFile = fopen(IQA_ROOT_DIR . 'debug.log', 'a')
            or die(__('Unable to open file!', 'intl_qa_lan'));
        fwrite($debugFile, "[${date}] Increment Views Error: sq=${searchQuery} \n");
        fclose($debugFile);
    }

    sendResponse([
        'success' => true
    ]);
}
add_action('wp_ajax_incrementViewsCount', 'incrementViewsCount_callback');
add_action('wp_ajax_nopriv_incrementViewsCount', 'incrementViewsCount_callback');


function SetResultNum_callback()
{
    if (!wp_verify_nonce($_POST['nonce'], 'results_num') || !check_ajax_referer('OwpCojMcdGJ-k-o', 'security')) {
        wp_send_json_error('Forbidden', 403);
        exit();
    }

    $num = absint($_POST['results_num']);
    $res = update_option('results_num', $num);

    if (!$res) {
        sendResponse([
            'success' => false
        ]);
    }

    sendResponse([
        'success' => true
    ]);
}
add_action('wp_ajax_SetResultNum', 'SetResultNum_callback');
add_action('wp_ajax_nopriv_SetResultNum', 'SetResultNum_callback');


function reportNonExistence_callback()
{
    if (!check_ajax_referer('mnhUciSW!Zk/oBB', 'security')) {
        wp_send_json_error('Forbidden', 403);
        exit();
    }

    global $wpdb;
    $input = sanitize_text_field($_POST['input']);
    $reportTable = $wpdb->prefix . REPORT_TABLE;

    $isExisted = $wpdb->get_var(
        $wpdb->prepare("SELECT COUNT(id) FROM ${reportTable} WHERE input=%s ", array($input))
    );

    if (intval($isExisted) !== 0) {
        $update = $wpdb->query(
            $wpdb->prepare(
                "UPDATE ${reportTable} SET count=count+1, updated_at=%s WHERE input=%s",
                array(current_time('mysql'), $input)
            )
        );
        if (!$update) sendResponse(['success' => false]);
    } else {
        $insert = $wpdb->insert($reportTable, [
            'input' => $input,
            'count' => 1,
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        ], ['%s', '%d', '%s', '%s']);
        if (!$insert) sendResponse(['success' => false]);
    }

    sendResponse(['success' => true]);
}
add_action('wp_ajax_reportNonExistence', 'reportNonExistence_callback');
add_action('wp_ajax_nopriv_reportNonExistence', 'reportNonExistence_callback');


function DeleteQA_callback()
{
    if (!wp_verify_nonce($_POST['nonce'], 'delete_qa') || !check_ajax_referer('OwpCojMcdGJ-k-o', 'security')) {
        wp_send_json_error('Forbidden', 403);
        exit();
    }

    global $wpdb;
    $qaID = $_POST['qa_id'];
    $res = $wpdb->delete($wpdb->prefix . QA_TABLE, [
        'id' => $qaID
    ], ['%d']);

    if (!$res) sendResponse(['success' => false]);
    sendResponse(['success' => true]);
}
add_action('wp_ajax_DeleteQA', 'DeleteQA_callback');
add_action('wp_ajax_nopriv_DeleteQA', 'DeleteQA_callback');


function getQAData_callback()
{
    if (!check_ajax_referer('OwpCojMcdGJ-k-o', 'security')) {
        wp_send_json_error('Forbidden', 403);
        exit();
    }

    global $wpdb;
    $qaID = absint($_POST['qa_id']);
    $qaTable = $wpdb->prefix . QA_TABLE;
    $res = $wpdb->get_results(
        $wpdb->prepare("SELECT question, answer, keywords FROM ${qaTable} WHERE id=%d ", array($qaID))
    );

    if (!res) sendResponse(['success' => false]);
    sendResponse(['success' => true, 'qa_data' => $res]);
}
add_action('wp_ajax_getQAData', 'getQAData_callback');
add_action('wp_ajax_nopriv_getQAData', 'getQAData_callback');


function editQa_callback()
{
    if (!wp_verify_nonce($_POST['nonce'], 'edit_qa') || !check_ajax_referer('OwpCojMcdGJ-k-o', 'security')) {
        wp_send_json_error('Forbidden', 403);
        exit();
    }

    global $wpdb;
    $qa_id = sanitize_text_field($_POST['qa_id']);
    $question = sanitize_text_field($_POST['question']);
    $answer = stripslashes($_POST['answer']);
    $keywords = sanitize_text_field(stripslashes($_POST['keywords']));

    $update = $wpdb->update($wpdb->prefix . QA_TABLE, [
        'question' => $question,
        'answer' => $answer,
        'keywords' => $keywords,
        'updated_at' => current_time('mysql'),
    ], [
        'id' => $qa_id
    ], ['%s', '%s', '%s', '%s'], '%d');

    if (!$update) sendResponse(['success' => false]);
    sendResponse(['success' => true]);
}
add_action('wp_ajax_editQa', 'editQa_callback');
add_action('wp_ajax_nopriv_editQa', 'editQa_callback');

function goToNextPrevPage_callback()
{
    if (!check_ajax_referer('OwpCojMcdGJ-k-o', 'security')) {
        wp_send_json_error('Forbidden', 403);
        exit();
    }

    $type = $_POST['type'];
    $offset = absint($_POST['offset']) + absint($_POST['limit']);
    $limit = absint($_POST['limit']);

    switch ($type) {
        case 'qanswers':
            $res = getPaginatedQAnswers($offset, $limit);
            break;
    }

    sendResponse(['success' => $res]);
}
add_action('wp_ajax_goToNextPrevPage', 'goToNextPrevPage_callback');
add_action('wp_ajax_nopriv_goToNextPrevPage', 'goToNextPrevPage_callback');

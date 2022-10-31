<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function arrayUnique($arr, $flag=SORT_NUMERIC){
    return array_unique($arr, $flag);
}

function arrayFlatten($array) {
    if (!is_array($array)) {
        return false;
    }
    $result = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $result = array_merge($result, arrayFlatten($value));
        } else {
            $result = array_merge($result, array($key => $value));
        }
    }
    return $result;
}

function getStopWords(){
    return array( 'یا','يا', 'و', 'از', 'که', 'این', 'با', 'در', 'به', 'ها');
}

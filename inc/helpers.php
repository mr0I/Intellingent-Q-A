<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


function arrayUnique($arr, $flag=SORT_NUMERIC){
    return array_unique($arr, $flag);
}
<?php
/* Init of advanced functions of theme */
require_once (TEMPLATEPATH . '/advance/apollo13.php');


//for front-end translations
function _fe($str){
    _e($str, A13_TPL_SLUG);
}
function __fe($str){
    return __($str, A13_TPL_SLUG);
}

//for back-end translations
function _be($str){
    _e($str, A13_TPL_SLUG.'_admin');
}
function __be($str){
    return  __($str, A13_TPL_SLUG.'_admin');
}

$apollo13 = new Apollo13();
//start theme engine
$apollo13->start();

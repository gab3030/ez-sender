<?php

// don't change anything

require_once('modules/random.php');

date_default_timezone_set("Asia/Jakarta");

function replace ($sumber,$email,$links) {
    
    preg_match_all("/##random_number_(\d+)##/", $sumber, $match_number);
    preg_match_all("/##random_string_up_(\d+)##/", $sumber, $match_string_up);
    preg_match_all("/##random_string_low_(\d+)##/", $sumber, $match_string_low);
    preg_match_all("/##random_string_uplow_(\d+)##/", $sumber, $match_string_uplow);
    preg_match_all("/##random_mix_(\d+)##/", $sumber, $match_mix);

    $dari = [
        @$match_number[0][0], 
        @$match_string_up[0][0], 
        @$match_string_low[0][0], 
        @$match_string_uplow[0][0], 
        @$match_mix[0][0],
        @"##random_ip##",
        @"##random_negara##",
        @"##random_device##",
        @"##email##",
        @"##date##",
        @"##random_link##"
        
    ];

    $ke   = [
        random_number(@$match_number[1][0]), 
        random_string_up(@$match_string_up[1][0]), 
        random_string_low(@$match_string_low[1][0]), 
        random_string_uplow(@$match_string_uplow[1][0]), 
        random_mix(@$match_mix[1][0]),
        random_ip(),
        random_negara(),
        random_device(),
        $email,
        date("F j, Y, g:i a"),
        random_array_value($links)

    ];
    
    return str_replace($dari, $ke, $sumber);

};